<?php

namespace App\DTO;

use Symfony\Component\Uid\Uuid;

class ProductIncome
{
    public function __construct(
        public readonly Uuid $id,
        public readonly string $name,
        public readonly float $price,
        public readonly float $qty,
        public readonly float $income = 0
    ){}
}
