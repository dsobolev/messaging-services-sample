<?php

namespace App\Controller;

use App\DTO\CreateProduct as ProductDTO;
use App\Entity\Inventory;
use App\Entity\Product;
use App\Service\DataExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class MainController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private DataExtractor $extractor
    ) {}

    #[Route('/products', methods: ['GET'], name: 'list_products', format: 'json')]
    public function index(DataExtractor $extractor): JsonResponse
    {
        $products = $this->em->getRepository(Product::class)->getAll();

        $data = array_map(
            fn ($product) => $this->extractor->productDataWithIncome($product),
            $products
        );

        return $this->json([
            'data' => $data
        ]);
    }

    #[Route('/products/{uuid}', methods: ['GET'], name: 'get_product', format: 'json')]
    public function single(string $uuid): JsonResponse
    {
        if (! Uuid::isValid($uuid)) {

            return $this->json([
                'message' => 'Invalid product id format'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $productId = Uuid::fromString($uuid);
        $product = $this->em->getRepository(Product::class)->getOneWithInventory($productId);

        if (is_null($product)) {

            return $this->json([
                'message' => 'Product not found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'data' => $this->extractor->productData($product)
        ]);
    }

    #[Route('/products', methods: ['POST'], name: 'create_product', format: 'json')]
    public function create(
        #[MapRequestPayload] ProductDTO $productPayload
    ): JsonResponse {
        $inventory = new Inventory();
        $inventory->setQty($productPayload->qty);
        $this->em->persist($inventory);

        $product = new Product();
        $product
            ->setName($productPayload->name)
            ->setPrice($productPayload->price)
            ->setInventory($inventory)
        ;
        $this->em->persist($product);

        $this->em->flush();

        return $this->json(
            $this->extractor->productDataWithIncome($product)
        );
    }
}
