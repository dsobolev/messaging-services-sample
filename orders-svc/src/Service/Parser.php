<?php

namespace App\Service;

use App\DTO\Product;
use Symfony\Component\Uid\Uuid;

class Parser
{
    public function parseProduct($data): Product
    {
        return new Product(
            id: Uuid::fromString($data->id),
            name: $data->name,
            price: $data->price,
            qty: $data->qty,
        );
    }
}
