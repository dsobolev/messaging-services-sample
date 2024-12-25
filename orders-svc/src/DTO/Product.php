<?php

namespace App\DTO;

use Symfony\Component\Uid\Uuid;

class Product
{
    public function __construct(
        public readonly Uuid $id,
        public readonly string $name,
        public readonly float $price,
        public int $qty
    ){}

    public function setQty(int $value): void
    {
        $this->qty = $value;
    }
}
