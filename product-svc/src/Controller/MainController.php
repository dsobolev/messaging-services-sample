<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\DataExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    #[Route('/products', name: 'list_products')]
    public function index(DataExtractor $extractor): JsonResponse
    {
        $products = $this->em->getRepository(Product::class)->getAll();

        $data = array_map(
            fn ($product) => $extractor->extractProductData($product),
            $products
        );

        return $this->json([
            'data' => $data
        ]);
    }
}
