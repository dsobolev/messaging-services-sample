<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function getAll(): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.inventory', 'iry')
            ->leftJoin('p.income', 'icm')
            ->select('p, iry, icm')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getOneWithInventory(Uuid $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->join('p.inventory', 'iry')
            ->select('p, iry')
            ->where('p.id = :id')
            ->setParameter('id', $id->toBinary())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
