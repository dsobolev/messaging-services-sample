<?php

namespace App\Service;

use App\DTO\Product as ProductDTO;
use App\Entity\Product;

class DataExtractor
{
    public function extractProductData(Product $product): ProductDTO
    {
        return new ProductDTO(
            id: $product->getId(),
            name: $product->getName(),
            price: $product->getPrice(),
            qty: $product->getInventory()->getQty(),
            income: $product->getIncome()?->getIncome() ?? 0
        );
    }
}
