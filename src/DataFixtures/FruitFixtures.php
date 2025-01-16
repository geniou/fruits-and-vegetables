<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Fruit;

class FruitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fruit = new Fruit();
        $fruit->setName('Banana');
        $fruit->setQuantity(150);
        $manager->persist($fruit);

        $fruit = new Fruit();
        $fruit->setName('Apples');
        $fruit->setQuantity(500);
        $manager->persist($fruit);
    
        $manager->flush();
    }
}
