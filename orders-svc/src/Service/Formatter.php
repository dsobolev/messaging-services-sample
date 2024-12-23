<?php

namespace App\Service;

use App\DTO\Order as OrderDTO;
use App\DTO\Product as ProductDTO;
use App\Entity\Order;

class Formatter
{
    public function orderData(Order $order, ProductDTO $product): OrderDTO
    {
        return new OrderDTO(
            id: $order->getId(),
            qty: $order->getQty(),
            amount: $order->getAmount(),
            product: $product
        );
    }
}
