<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class FoodRepository extends ServiceEntityRepository
{
    public function findByName(string $name = null): array
    {
        $queryBuilder = $this->createQueryBuilder('f');

        if(!is_null($name) && '' !== $name) {
            $queryBuilder
                ->andWhere('f.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    // just for test cleanup
    public function deleteAll(): void
    {
        $this->createQueryBuilder('f')
            ->delete()
            ->getQuery()
            ->execute();
    }
}