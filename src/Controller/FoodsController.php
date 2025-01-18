<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Fruit;
use App\Entity\Vegetable;
use Doctrine\ORM\EntityManagerInterface;

#[Route(path: '/food/{food}', requirements: ['food' => 'fruit|vegetable'])]
class FoodsController extends AbstractController
{
    private function getFoodClass(string $food): string
    {
        return 'fruit' === $food ? Fruit::class : Vegetable::class;
    }

    #[Route(path: '/{id}', methods: [Request::METHOD_GET])]
    public function show(EntityManagerInterface $entityManager, int $id, string $food): JsonResponse
    {
        $food = $entityManager->getRepository($this->getFoodClass($food))->find($id);

        if (!$food) {
            throw $this->createNotFoundException('Food not found');
        }

        return $this->json($food);
    }

    #[Route(path: '', methods: [Request::METHOD_GET])]
    public function list(EntityManagerInterface $entityManager, string $food, Request $request): JsonResponse
    {
        $foods = $entityManager->getRepository($this->getFoodClass($food))->findByName($request->query->get('name'));

        return $this->json($foods);
    }

    #[Route(path: '', methods: [Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $entityManager, string $food): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $food = 'food' === $food ? new Fruit() : new Vegetable();
        $food->setName($data['name']);
        $food->setQuantity($data['quantity']);

        $entityManager->persist($food);
        $entityManager->flush();

        return $this->json($food);
    }
}