<?php

namespace Tests\App\Controller;

use App\DataFixtures\FruitFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Fruit;

class FruitControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = self::getContainer()->get('doctrine')->getManager();

        // empty table before each test
        $this->entityManager->getRepository(Fruit::class)->deleteAll();
        $fixture = new FruitFixtures();
        $fixture->load($this->entityManager);
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/food/fruit');

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
        $id = $this->entityManager->getRepository(Fruit::class)->findAll()[0]->getId();
        $this->client->request('GET', '/food/fruit/'.$id);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('quantity', $responseData);
    }

    public function testCreate(): void
    {
        $this->client->request('POST', '/food/fruit', [], [], [], json_encode([
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