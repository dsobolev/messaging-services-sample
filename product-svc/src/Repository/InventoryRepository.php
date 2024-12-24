<?php

namespace App\Repository;

use App\Entity\Inventory;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Inventory>
 */
class InventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventory::class);
    }

    public function getOneForProduct(Uuid $productId): ?Inventory
    {
        return $this->createQueryBuilder('i')
            ->join('i.product', 'p')
            ->select('i')
            ->where('p.id = :id')
            ->setParameter(':id', $productId->toBinary())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
