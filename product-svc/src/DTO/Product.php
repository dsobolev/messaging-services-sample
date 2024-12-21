<?php

namespace App\DTO;

use Symfony\Component\Uid\Uuid;

class Product
{
    public function __construct(
        public Uuid $id,
        public string $name,
        public float $price,
        public float $qty,
        public float $income = 0
    ){}
}
