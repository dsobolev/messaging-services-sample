<?php

namespace App\Controller;

use App\DTO\ApiProductResponse;
use App\DTO\MakeOrder as OrderPayloadDTO;
use App\Entity\Order;
use App\Message\UpdateProductIncome as ProductMessage;
use App\Service\Formatter;
use App\Service\OrdersService;
use App\Service\ProductApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class MainController extends AbstractController
{
    public function __construct(
        private Formatter $formatter,
        private ProductApiClient $api
    ) {}

    #[Route('/orders', methods: ['GET'], name: 'list_orders', format: 'json')]
    public function index(
        EntityManagerInterface $em
    ): JsonResponse
    {
        $orders = $em->getRepository(Order::class)->findAll();

        $data = [];
        foreach ($orders as $order) {
            /** @var ApiProductResponse */
            $productApiResult = $this->api->getProduct($order->getProductId());

            /** @var App\DTO\Product */
            $product = $productApiResult->product;

            $data[] = $this->formatter->orderData($order, $product);
        }

        return $this->json([
            'data' => $data
        ]);
    }

    #[Route('/orders', methods: ['POST'], name: 'create_order', format: 'json')]
    public function create(
        #[MapRequestPayload] OrderPayloadDTO $payload,
        OrdersService $orderMaker,
        MessageBusInterface $bus,
    ): JsonResponse
    {
        $id = Uuid::fromString($payload->product);
        /** @var ApiProductResponse */
        $result = $this->api->getProduct($id);

        if ($result->httpCode !== 200) {
            throw new Exception\BadRequestHttpException($result->message);
        }

        /** @var App\DTO\Product */
        $product = $result->product;

        $qtyAvailable = $product->qty;
        if ($qtyAvailable === 0) {
            throw new Exception\BadRequestHttpException('Product is out of stock');
        }

        $qtyOrdered = $payload->qty;
        if ($qtyOrdered > $qtyAvailable) {
            throw new Exception\ConflictHttpException("Not possible to order {$qtyOrdered} products. There are only {$qtyAvailable} in stock");
        }

        $updatedQty = $qtyAvailable - $qtyOrdered;
        $isQtyUpdateSuccessful = $this->api->updateProductInventory(
            productId: $product->id,
            qty: $updatedQty
        );

        if (! $isQtyUpdateSuccessful) {
            throw new Exception\UnprocessableEntityHttpException('Can not update product inventory');
        }

        $product->setQty($updatedQty);
        $order = $orderMaker->makeOrder($product, $qtyOrdered);

        // TODO postPersist on orders might work
        $bus->dispatch(new ProductMessage(
            productId: $product->id,
            income: $order->getAmount()
        ));

        return $this->json(
            $this->formatter->orderData($order, $product)
        );
    }
}
