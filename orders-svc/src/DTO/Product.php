<?php

namespace App\DTO;

use Symfony\Component\Uid\Uuid;

class Product
{
    public function __construct(
        public readonly Uuid $id,
        public readonly string $name,
        public readonly float $price,
        public readonly int $qty
    ){}
}
