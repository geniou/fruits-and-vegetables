<?php

namespace App\Tests\Repository;

use App\Entity\Vegetable;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\DataFixtures\VegetableFixtures;

class VegetableRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // empty table before each test
        $this->entityManager->getRepository(Vegetable::class)->deleteAll();
        $fixture = new VegetableFixtures();
        $fixture->load($this->entityManager);
    }

    public function testFindAll(): void
    {
        $vegetables = $this->entityManager
            ->getRepository(Vegetable::class)
            ->findByName('');

        $this->assertSame(2, count($vegetables));
    }

    public function testFindByName(): void
    {
        $vegetables = $this->entityManager
            ->getRepository(Vegetable::class)
            ->findByName('Carrot');

        $this->assertSame(1, count($vegetables));

        $this->assertSame('Carrot', $vegetables[0]->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}