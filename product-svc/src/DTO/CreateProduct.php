<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Uuid;

class CreateProduct
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(
            min: 3,
            max: 30,
            minMessage: 'Product name must be at list {{ limit }} characters long',
            maxMessage: 'Product name cannot be longer than {{ limit }} characters. It is {{ value_length }} characters now.',
        )]
        public readonly string $name,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly float $price,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $qty
    ){}
}
