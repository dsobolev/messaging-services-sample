<?php

namespace App\DTO;

class ApiProductResponse
{
    public function __construct(
        public readonly int $httpCode,
        public ?string $message = null,
        public ?Product $product = null
    ){}

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}