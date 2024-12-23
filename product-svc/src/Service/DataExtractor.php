<?php

namespace App\Service;

use App\DTO\ProductIncome as ProductIncomeDTO;
use App\Entity\Product;

class DataExtractor
{
    public function productDataWithIncome(Product $product): ProductIncomeDTO
    {
        return new ProductIncomeDTO(
            id: $product->getId(),
            name: $product->getName(),
            price: $product->getPrice(),
            qty: $product->getInventory()->getQty(),
            income: $product->getIncome()?->getIncome() ?? 0
        );
    }
}
