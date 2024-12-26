<?php

namespace App\Message;

use Symfony\Component\Uid\Uuid;

class UpdateProductIncome
{
    public function __construct(
        private Uuid $productId,
        private float $income
    ){}

    public function getProductId(): Uuid
    {
        return $this->productId;
    }

    public function getIncome(): float
    {
        return $this->income;
    }
}