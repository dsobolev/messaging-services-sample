<?php

namespace App\Entity;

use App\Repository\IncomeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IncomeRepository::class)]
class Income
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $income = null;

    #[ORM\OneToOne(mappedBy: 'income')]
    private Product $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIncome(): ?float
    {
        return $this->income;
    }

    public function setIncome(float $income): static
    {
        $this->income = $income;

        return $this;
    }
}
