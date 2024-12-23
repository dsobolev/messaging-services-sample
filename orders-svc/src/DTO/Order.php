<?php

namespace App\DTO;

use Symfony\Component\Uid\Uuid;

class Order
{
    public function __construct(
        public readonly Uuid $id,
        public readonly int $qty,
        public readonly float $amount,
        public readonly Product $product
    ){}
}
