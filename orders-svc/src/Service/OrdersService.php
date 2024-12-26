<?php

namespace App\Service;

use App\DTO\Product as ProductDTO;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class OrdersService
{
    public function __construct(
        private EntityManagerInterface $em
    ){}

    public function makeOrder(ProductDTO $product, int $qty): Order
    {
        $amount = round($qty * $product->price, 2);

        $order = new Order();
        $order
            ->setProductId($product->id)
            ->setQty($qty)
            ->setAmount($amount)
        ;
        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }
}
