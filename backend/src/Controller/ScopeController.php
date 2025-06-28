<?php

namespace App\Controller;

use App\DTO\Mappers\ScopeMapper;
use App\DTO\ModelDTO;
use App\DTO\ScopeDTO;
use App\Entity\Scope;
use App\Repository\ScopeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class ScopeController extends AbstractController
{
    public function __construct(private readonly ScopeRepository $scopeRepository, private readonly EntityManagerInterface $entityManager) { }
    #[OA\Post(
        path: '/api/scopes',
        description: 'Create a new scope',
        summary: 'Create scope',
        tags: ['Scope']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Scope created successfully",
        content: new OA\JsonContent(
            ref: new Model(type: ScopeDTO::class)
        )
    )]
    #[OA\Response(response: 400, description: "Invalid input")]
    #[Route('/api/scopes', name: 'app_scope_create', methods: ['POST'])]
    public function createScope(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name'])) {
            return $this->json(['message' => 'Name is required'], 400);
        }

        $scope = new \App\Entity\Scope();
        $scope->setName($data['name']);

        $this->entityManager->persist($scope);
        $this->entityManager->flush();

        return $this->json(ScopeMapper::mapToDTO($scope), 201);
    }


    #[OA\Get(
        path: '/api/scopes',
        description: 'Get all scopes with their prompts.',
        summary: 'Fetch all scopes with prompts',
        tags: ['Scope']
    )]

    #[OA\Response(
        response: 200,
        description: "List of scopes",
        content: new OA\JsonContent(
            ref: new Model(type: ScopeDTO::class),
        )
    )]
    #[Route('/api/scopes', name: 'app_scope_list', methods: ['GET'])]
    public function getScopes(): Response
    {
        $scopes = $this->scopeRepository->findAll();
        return $this->json(ScopeMapper::mapToDTOArray($scopes));
    }

    #[OA\Get(
        path: '/api/scopes/{id}',
        description: 'Get a scope by its ID',
        summary: 'Fetch scope by ID',
        tags: ['Scope']
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: "The requested scope",
        content: new OA\JsonContent(
            ref: new Model(type: ScopeDTO::class)
        )
    )]
    #[OA\Response(response: 404, description: "Scope not found")]
    #[Route('/api/scopes/{id}', name: 'app_scope_show', methods: ['GET'])]
    public function getScope(int $id): Response
    {
        $scope = $this->scopeRepository->find($id);

        if (!$scope) {
            return $this->json(['message' => 'Scope not found'], 404);
        }

        return $this->json(ScopeMapper::mapToDTO($scope));
    }


    #[OA\Put(
        path: '/api/scopes/{id}',
        description: 'Fully update a scope by its ID',
        summary: 'Update scope',
        tags: ['Scope']
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Scope updated successfully",
        content: new OA\JsonContent(
            ref: new Model(type: ScopeDTO::class)
        )
    )]
    #[OA\Response(response: 400, description: "Invalid input")]
    #[OA\Response(response: 404, description: "Scope not found")]
    #[Route('/api/scopes/{id}', name: 'app_scope_update', methods: ['PUT'])]
    public function updateScope(int $id, Request $request): Response
    {
        $scope = $this->scopeRepository->find($id);

        if (!$scope) {
            return $this->json(['message' => 'Scope not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['name'])) {
            return $this->json(['message' => 'Name is required'], 400);
        }

        $scope->setName($data['name']);
        $this->entityManager->flush();

        return $this->json(ScopeMapper::mapToDTO($scope));
    }


    #[OA\Delete(
        path: '/api/scopes/{id}',
        description: 'Delete a scope by its ID',
        summary: 'Delete scope',
        tags: ['Scope']
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(response: 200, description: "Scope deleted successfully")]
    #[OA\Response(response: 404, description: "Scope not found")]
    #[Route('/api/scopes/{id}', name: 'app_scope_delete', methods: ['DELETE'])]
    public function deleteScope(int $id): Response
    {
        $scope = $this->scopeRepository->find($id);

        if (!$scope) {
            return $this->json(['message' => 'Scope not found'], 404);
        }

        $this->entityManager->remove($scope);
        $this->entityManager->flush();

        return $this->json(['message' => 'Scope deleted successfully']);
    }





}
