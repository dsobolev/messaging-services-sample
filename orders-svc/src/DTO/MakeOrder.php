<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class MakeOrder
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $product,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $qty
    ){}
}
