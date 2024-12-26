<?php

namespace App\Repository;

use App\Entity\Income;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Income>
 */
class IncomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Income::class);
    }

    public function getOneForProduct(Uuid $productId): ?Income
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
