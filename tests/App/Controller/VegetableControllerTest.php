<?php

namespace Tests\App\Controller;

use App\DataFixtures\VegetableFixtures;
use App\Entity\Vegetable;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VegetableControllerTest extends WebTestCase
{
    private $client;
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = self::getContainer()->get('doctrine')->getManager();

        $fixture = new VegetableFixtures();
        $fixture->load($this->entityManager);
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/food/vegetable');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('name', $responseData[0]);
        $this->assertArrayHasKey('quantity', $responseData[0]);
    }

    public function testShow(): void
    {
        // get id of first fruit
        $id = $this->entityManager->getRepository(Vegetable::class)->findAll()[0]->getId();
        $this->client->request('GET', '/food/vegetable/'.$id);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('quantity', $responseData);
    }

    public function testCreate(): void
    {
        $this->client->request('POST', '/food/vegetable', [], [], [], json_encode([
            'name' => 'Orange',
            'quantity' => 100
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('quantity', $responseData);

        $this->assertSame('Orange', $responseData['name']);
        $this->assertSame(100, $responseData['quantity']);
    }
}