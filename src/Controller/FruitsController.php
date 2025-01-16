<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;

#[Route(path: '/fruit')]
class FruitsController extends AbstractController
{
    #[Route(path: '/{id}', methods: [Request::METHOD_GET])]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $fruit = $entityManager->getRepository(Fruit::class)->find($id);

        if (!$fruit) {
            throw $this->createNotFoundException('Fruit not found');
        }

        return $this->json($fruit);
    }

    #[Route(path: '', methods: [Request::METHOD_GET])]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $fruits = $entityManager->getRepository(Fruit::class)->findAll();

        return $this->json($fruits);
    }
}