<?php

namespace Tests\App\Controller;

use App\DataFixtures\FruitFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FruitControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $entityManager = self::getContainer()->get('doctrine')->getManager();

        $fixture = new FruitFixtures();
        $fixture->load($entityManager);
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/fruit');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('name', $responseData[0]);
        $this->assertArrayHasKey('quantity', $responseData[0]);
    }

    public function testShow(): void
    {
        $this->client->request('GET', '/fruit/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertArrayHasKey('quantity', $responseData);
    }


}