<?php

namespace App\DataFixtures;

use App\Entity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $inventory = new Entity\Inventory();
            $inventory->setQty($faker->numberBetween(5, 30));

            $product = new Entity\Product();
            $product
                ->setName($faker->sentence(2))
                ->setPrice($faker->randomFloat(2, 0.01, 1000))
                ->setInventory($inventory)
            ;
            $manager->persist($inventory);

            if ($i % 3 === 0) {
                $income = new Entity\Income();
                $income->setIncome($faker->randomFloat(2, 10, 1000));
                $product->setIncome($income);

                $manager->persist($income);
            }

            $manager->persist($product);
        }


        $manager->flush();
    }
}
