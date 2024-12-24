<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Inventory
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public readonly int $qty
    ){}
}
