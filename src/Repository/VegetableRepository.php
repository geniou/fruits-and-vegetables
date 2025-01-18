<?php

namespace App\Repository;

use App\Entity\Vegetable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\FoodRepository;

/**
 * @extends ServiceEntityRepository<Vegetable>
 */
class VegetableRepository extends FoodRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vegetable::class);
    }
}
