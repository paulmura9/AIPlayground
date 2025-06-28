<?php

namespace App\Controller;

use App\DTO\Mappers\RunMapper;
use App\DTO\PromptDTO;
use App\DTO\RunDTO;
use App\Entity\Run;
use App\Repository\ModelRepository;
use App\Repository\RunRepository;
use App\services\MetricsService;
use App\services\ModelService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PlatformRepository;
use App\Repository\PromptRepository;
use App\Repository\ScopeRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use App\Entity\Scope;
use Symfony\Component\HttpFoundation\Request;
final class RunController extends AbstractController
{
    public function __construct(private readonly RunRepository $runRepository,private readonly PromptRepository $promptRepository,private readonly ModelRepository $modelRepository,private readonly EntityManagerInterface $entityManager,private readonly ModelService $modelService)
    {
    }

    #[OA\Get(
        path: '/api/prompts/{id}/runs',
        description: 'Get all runs for a given prompt',
        summary: 'Fetch runs by prompt ID',
        tags: ['Run']
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
        description: "List of runs for the given prompt",
        content: new OA\JsonContent(
            ref: new Model(type: RunDTO::class, groups: ['full'])
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Prompt not found"
    )]
    #[Route('/api/prompts/{id}/runs', name: 'app_prompt_runs', methods: ['GET'])]
    public function getRunsByPrompt(int $id): Response
    {
        $prompt = $this->promptRepository->find($id);

        if (!$prompt) {
            return $this->json(['message' => 'Prompt not found'], 404);
        }

        $runs = $prompt->getRuns()->toArray();

        return $this->json(RunMapper::mapToDTOArray($runs));
    }


    #[OA\Get(
        path: '/api/models/{id}/runs',
        description: 'Get all runs for a given model',
        summary: 'Fetch runs by model ID',
        tags: ['Run']
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Model ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Response(
        response: 200,
        description: "List of runs for the given model",
        content: new OA\JsonContent(
            ref: new Model(type: RunDTO::class, groups: ['full'])
        )
    )]
    #[OA\Response(response: 404, description: "Model not found")]
    #[Route('/api/models/{id}/runs', name: 'app_model_runs', methods: ['GET'])]
    public function getRunsByModel(int $id): Response
    {
        $model = $this->modelRepository->find($id);

        if (!$model) {
            return $this->json(['message' => 'Model not found'], 404);
        }

        return $this->json(RunMapper::mapToDTOArray($model->getRuns()->toArray()));
    }


    #[OA\Patch(
        path: '/api/runs/{id}',
        description: 'Update only the rating of a run',
        summary: 'Update run rating only',
        tags: ['Run']
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Run ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "rating", type: "integer")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Run rating updated successfully",
        content: new OA\JsonContent(
            ref: new Model(type: RunDTO::class, groups: ['full'])
        )
    )]
    #[OA\Response(response: 404, description: "Run not found")]
    #[Route('/api/runs/{id}', name: 'app_run_update_rating', methods: ['PATCH'])]
    public function updateRunRating(int $id, Request $request): Response
    {
        $run = $this->runRepository->find($id);

        if (!$run) {
            return $this->json(['message' => 'Run not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['rating']) || !is_numeric($data['rating'])) {
            return $this->json(['message' => 'Missing or invalid rating'], 400);
        }

        $run->setRating((int) $data['rating']);
        $this->entityManager->flush();

        return $this->json(RunMapper::mapToDTO($run));
    }

    #[OA\Put(
        path: '/api/runs/{id}',
        description: 'Fully update a run',
        summary: 'Update all editable fields of a run',
        tags: ['Run']
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'Run ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "temperature", type: "number"),
                new OA\Property(property: "actualResponse", type: "string"),
                new OA\Property(property: "rating", type: "integer"),
                new OA\Property(property: "userRating", type: "integer")
            ],
            type: "object"
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Run updated successfully",
        content: new OA\JsonContent(
            ref: new Model(type: RunDTO::class, groups: ['full'])
        )
    )]
    #[OA\Response(response: 404, description: "Run not found")]
    #[OA\Response(response: 400, description: "Invalid input")]
    #[Route('/api/runs/{id}', name: 'app_run_update', methods: ['PUT'])]
    public function updateRun(int $id, Request $request): Response
    {
        $run = $this->runRepository->find($id);

        if (!$run) {
            return $this->json(['message' => 'Run not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (
            !isset($data['temperature'], $data['actualResponse'], $data['rating'], $data['userRating']) ||
            !is_numeric($data['temperature']) ||
            !is_string($data['actualResponse']) ||
            !is_numeric($data['rating']) ||
            !is_numeric($data['userRating'])
        ) {
            return $this->json(['message' => 'Invalid input'], 400);
        }

        $run->setTemperature((float)$data['temperature']);
        $run->setActualResponse($data['actualResponse']);
        $run->setRating((int)$data['rating']);
        $run->setUserRating((int)$data['userRating']);

        $this->entityManager->flush();

        return $this->json(RunMapper::mapToDTO($run));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[OA\Post(
        path: "/api/runs",
        description: "Creates one or more runs for a given prompt ID. Each run is associated with a specific model and temperature.",
        summary: "Create a new run/s for a prompt.",
        tags: ["Run"]
    )]
    #[OA\RequestBody(
        description: "Run data",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "promptId", type: "integer", example: 1),
                new OA\Property(
                    property: "models",
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "modelId", type: "integer", example: 3),
                            new OA\Property(property: "temperature", type: "number", format: "float", example: 0.7)
                        ]
                    )
                )
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Runs created successfully",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: RunDTO::class, groups: ["all"]))
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Invalid request data"
    )]
    #[OA\Response(
        response: 404,
        description: "Prompt or Model not found"
    )]
    #[Route('/api/runs', name: 'create_runs', methods: ['POST'])]
    public function createRuns(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['promptId']) || !isset($data['models'])) {
            return new JsonResponse(['error' => 'Missing required fields'], 400);
        }

        $prompt = $this->promptRepository->find($data['promptId']);
        if (!$prompt) {
            return new JsonResponse(['error' => 'Prompt not found'], 404);
        }
        foreach ($data['models'] as $modelData) {
            $model = $this->modelRepository->find($modelData['modelId']);
            if (!$model) {
                return new JsonResponse(['error' => "Model with ID {$modelData['modelId']} not found"], 404);
            }

            $temperature = $modelData['temperature'] ?? 1.0;
            $response = $this->modelService->callModelAPI($model, $prompt, $temperature);

            if (!$response) {
                continue;
            }

            $rating=  MetricsService::calculateRating(
                $response["executionTime"],
                $response["totalTokens"],
                $response["actualResponse"],
                $prompt->getExpectedResult()
            );

            $run= new Run();
            $run->setModel($model);
            $run->setPrompt($prompt);
            $run->setTemperature($temperature);
            $run->setActualResponse($response["actualResponse"]);
            $run->setUserRating(0);
            $run->setRating($rating);

            $this->runRepository->saveRun(($run));

            $runs[] = $run;
        }
        return $this->json(RunMapper::mapToDTOArray($runs),Response::HTTP_CREATED);
    }

}
