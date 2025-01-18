<?php

namespace App\Tests\Repository;

use App\Entity\Fruit;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\DataFixtures\FruitFixtures;

class FruitRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // empty table before each test
        $this->entityManager->getRepository(Fruit::class)->deleteAll();
        $fixture = new FruitFixtures();
        $fixture->load($this->entityManager);
    }

    public function testFindAll(): void
    {
        $fruits = $this->entityManager
            ->getRepository(Fruit::class)
            ->findByName('');

        $this->assertSame(2, count($fruits));
    }

    public function testFindByName(): void
    {
        $fruits = $this->entityManager
            ->getRepository(Fruit::class)
            ->findByName('Apple');

        $this->assertSame(1, count($fruits));

        $this->assertSame('Apples', $fruits[0]->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}