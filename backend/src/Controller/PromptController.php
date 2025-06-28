<?php

namespace App\Controller;

use App\DTO\Mappers\PromptMapper;
use App\DTO\ModelDTO;
use App\DTO\PlatformDTO;
use App\DTO\PromptDTO;
use App\Repository\PlatformRepository;
use App\Repository\PromptRepository;
use App\Repository\ScopeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use App\Entity\Scope;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Attribute\Model;
final class PromptController extends AbstractController
{
    public function __construct(private readonly PromptRepository $promptRepository,private readonly ScopeRepository $scopeRepository,private readonly EntityManagerInterface $entityManager)
    {
    }

    #[OA\Get(
        path: '/api/prompts',
        description: 'Get all the prompts with their scope and runs',
        summary: 'Fetch all the prompts with related entities',
        tags: ['Prompt'],
    )]

    #[OA\Response(
        response: 200,
        description: "List of prompts",
        content: new OA\JsonContent(
            ref: new Model(type: PromptDTO::class, groups: ['full'])
        )
    )]

    #[Route('/api/prompts', name: 'app_prompt', methods: ['GET'])]
    public function getPrompts(): Response
    {
        $prompts = $this->promptRepository->findAll();
        return $this->json(PromptMapper::mapToDTOArray($prompts));
    }

    #[OA\Get(
        path: '/api/prompts/{id}',
        description: 'Get a specific prompt by ID',
        summary: 'Fetch one prompt',
        tags: ['Prompt']
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Prompt ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: "The requested prompt",
        content: new OA\JsonContent(
            ref: new Model(type: PromptDTO::class, groups: ['full'])
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Prompt not found"
    )]
    #[Route('/api/prompts/{id}', name: 'app_prompt_show', methods: ['GET'])]
    public function getPrompt(int $id): Response
    {
        $prompt = $this->promptRepository->find($id);

        if (!$prompt) {
            return $this->json(['message' => 'Prompt not found'], 404);
        }

        return $this->json(PromptMapper::mapToDTO($prompt));
    }


    #[OA\Get(
        path: '/api/scopes/{id}/prompts',
        description: 'Get prompts by scope ID',
        summary: 'Fetch prompts for a scope',
        tags: ['Prompt']
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Scope ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: "List of prompts for the scope",
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(type: PromptDTO::class, groups: ['full'])
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Scope not found"
    )]
    #[Route('/api/scopes/{id}/prompts', name: 'app_scope_prompts', methods: ['GET'])]
    public function getPromptsByScope(int $id): Response
    {
        $scope = $this->scopeRepository->find($id);

        if (!$scope) {
            return $this->json(['message' => 'Scope not found'], 404);
        }

        return $this->json(PromptMapper::mapToDTOArray($scope->getPrompts()->toArray()));
    }

    #[OA\Post(
        path: '/api/prompts',
        description: 'Create a new prompt',
        summary: 'Create prompt',
        tags: ['Prompt']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string"),
                new OA\Property(property: "systemMessage", type: "string"),
                new OA\Property(property: "userMessage", type: "string"),
                new OA\Property(property: "expectedResult", type: "string"),
                new OA\Property(property: "scope", description: "Scope ID", type: "integer")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Prompt created",
        content: new OA\JsonContent(
            ref: new Model(type: PromptDTO::class, groups: ['full'])
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Invalid input or scope not found"
    )]
    #[Route('/api/prompts', name: 'app_prompt_create', methods: ['POST'])]
    public function createPrompt(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (
            empty($data['name']) ||
            empty($data['systemMessage']) ||
            empty($data['userMessage']) ||
            empty($data['expectedResult']) ||
            empty($data['scope'])
        ) {
            return $this->json(['message' => 'Missing required fields'], 400);
        }

        $scope = $this->scopeRepository->find($data['scope']);
        if (!$scope) {
            return $this->json(['message' => 'Scope not found'], 400);
        }

        $prompt = new \App\Entity\Prompt();
        $prompt->setName($data['name']);
        $prompt->setSystemMessage($data['systemMessage']);
        $prompt->setUserMessage($data['userMessage']);
        $prompt->setExpectedResult($data['expectedResult']);
        $prompt->setScope($scope);

        $this->entityManager->persist($prompt);
        $this->entityManager->flush();

        return $this->json(PromptMapper::mapToDTO($prompt), 201);
    }


    #[OA\Delete(
        path: '/api/prompts/{id}',
        description: 'Delete a prompt by ID, along with its runs',
        summary: 'Delete prompt',
        tags: ['Prompt']
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Prompt ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: "Prompt deleted successfully"
    )]
    #[OA\Response(
        response: 404,
        description: "Prompt not found"
    )]
    #[Route('/api/prompts/{id}', name: 'app_prompt_delete', methods: ['DELETE'])]
    public function deletePrompt(int $id): Response
    {
        $prompt = $this->promptRepository->find($id);

        if (!$prompt) {
            return $this->json(['message' => 'Prompt not found'], 404);
        }

        foreach ($prompt->getRuns() as $run) {
            $this->entityManager->remove($run);
        }

        $this->entityManager->remove($prompt);
        $this->entityManager->flush();

        return $this->json(['message' => 'Prompt deleted successfully']);
    }



}
