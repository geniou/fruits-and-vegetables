<?php

namespace App\Tests\App\Command;

use App\Entity\Fruit;
use App\Entity\Vegetable;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use App\Command\ImportCommand;

class ImportCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;
    private MockObject $entityManager;

    protected function setUp(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $application = new Application($kernel);
        # TODO: Type-check fails Mock vs Interface
        $application->add(new ImportCommand($this->entityManager));
        $command = $application->find('app:import-data');
        $this->commandTester = new CommandTester($command);
    }

    /** @test */
    public function notExistFile()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->never())
            ->method('persist');

        $this->commandTester->execute([
            'file_path' => 'not-existing.json'
        ]);
        $statusCode = $this->commandTester->getStatusCode();

        // expect Failure
        Assert::assertEquals(1, $statusCode);
    }

    /** @test */
    public function emptyFile()
    {
        $this->commandTester->execute([
            'file_path' => 'tests\App\Command\testData\empty.json'
        ]);
        $statusCode = $this->commandTester->getStatusCode();

        // expect Failure
        Assert::assertEquals(1, $statusCode);
    }

    /** @test */
    public function wrongType()
    {
        $this->commandTester->execute([
            'file_path' => 'tests\App\Command\testData\wrong_type.json'
        ]);
        $statusCode = $this->commandTester->getStatusCode();

        // expect Failure
        Assert::assertEquals(1, $statusCode);
    }

    /** @test */
    public function missingAttribute()
    {
        $this->commandTester->execute([
            'file_path' => 'tests\App\Command\testData\missing_attribute.json'
        ]);
        $statusCode = $this->commandTester->getStatusCode();

        // expect Failure
        Assert::assertEquals(1, $statusCode);
    }

    /** @test */
    public function happyCase()
    {
        $this->entityManager
            ->expects($this->exactly(2))
            ->method('persist')
            ->with($this->callback(function ($entity) {
                if ($entity instanceof Fruit) {
                    return 2 === $entity->getId() && 'Apples' === $entity->getName() && 20000 === $entity->getQuantity();
                } elseif ($entity instanceof Vegetable) {
                    return 1 === $entity->getId() && 'Carrot' === $entity->getName() && 10922 === $entity->getQuantity();
                } else {
                    return false;
                }
            }));

        $this->commandTester->execute([
            'file_path' => 'tests\App\Command\testData\data.json'
        ]);

        $this->commandTester->assertCommandIsSuccessful();
    }
}
