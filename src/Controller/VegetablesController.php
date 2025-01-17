<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Vegetable;
use Doctrine\ORM\EntityManagerInterface;

# TODO: both controllers are almost identical, consider refactoring them
#[Route(path: '/vegetable')]
class VegetablesController extends AbstractController
{
    #[Route(path: '/{id}', methods: [Request::METHOD_GET])]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $vegetable = $entityManager->getRepository(Vegetable::class)->find($id);

        if (!$vegetable) {
            throw $this->createNotFoundException('Vegetable not found');
        }

        return $this->json($vegetable);
    }

    #[Route(path: '', methods: [Request::METHOD_GET])]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $vegetables = $entityManager->getRepository(Vegetable::class)->findAll();

        return $this->json($vegetables);
    }

    #[Route(path: '', methods: [Request::METHOD_POST])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $vegetable = new Vegetable();
        $vegetable->setName($data['name']);
        $vegetable->setQuantity($data['quantity']);

        $entityManager->persist($vegetable);
        $entityManager->flush();

        return $this->json($vegetable);
    }
}