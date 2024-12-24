<?php

namespace App\Controller;

use App\DTO\Inventory as InventoryPayload;
use App\Entity\Inventory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;

class InventoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    #[Route('/products/{id}',
        methods: ['PUT'],
        name: 'update_inventory',
        format: 'json'
    )]
    public function update(
        string $id,
        #[MapRequestPayload] InventoryPayload $payload
    ): JsonResponse
    {
        if (! Uuid::isValid($id)) {

            return $this->json([
                'message' => 'Invalid product id format'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $productInventory = $this->em->getRepository(Inventory::class)
            ->getOneForProduct(Uuid::fromString($id));

        $inventory = new Inventory();
        $productInventory->setQty($payload->qty);

        $this->em->flush();

        return $this->json(['message' => 'Done!']);
    }
}
