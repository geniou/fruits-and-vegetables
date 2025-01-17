<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Vegetable;

class VegetableFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $vegetable = new Vegetable();
        $vegetable->setName('Carrot');
        $vegetable->setQuantity(150);
        $manager->persist($vegetable);

        $vegetable = new Vegetable();
        $vegetable->setName('Beans');
        $vegetable->setQuantity(500);
        $manager->persist($vegetable);
    
        $manager->flush();
    }
}
