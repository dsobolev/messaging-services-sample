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
            fn ($product) => $this->extractor->extractProductData($product),
            $products
        );

        return $this->json([
            'data' => $data
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
            $this->extractor->extractProductData($product)
        );
    }
}
