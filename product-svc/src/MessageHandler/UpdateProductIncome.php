<?php

namespace App\MessageHandler;

use App\Entity\Income;
use App\Entity\Product;
use App\Message\UpdateProductIncome as Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateProductIncome
{
    public function __construct(
        private EntityManagerInterface $em
    ){}

    public function __invoke(Message $msg): void
    {
        $incomeEntity = $this->em->getRepository(Income::class)
            ->getOneForProduct($msg->getProductId());

        if (is_null($incomeEntity)) {
            $incomeEntity = new Income();

            $product = $this->em->getRepository(Product::class)->find($msg->getProductId());
            $product->setIncome($incomeEntity);

            $this->em->persist($incomeEntity);
        }

        $incomeWas = $incomeEntity->getIncome() ?? 0;
        $incomeEntity->setIncome($incomeWas + $msg->getIncome());

        $this->em->flush();
    }
}