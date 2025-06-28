<?php

namespace App\Controller;

use App\DTO\Mappers\ModelMapper;
use App\DTO\Mappers\PlatformMapper;
use App\DTO\ModelDTO;
use App\Repository\ModelRepository;
use App\Repository\PlatformRepository;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class ModelController extends AbstractController
{
    public function __construct(private readonly ModelRepository $modelRepository,private readonly PlatformRepository $platformRepository)
    {
    }

    #[OA\Get(
        path: '/api/models',
        description: 'Get all models with their platforms',
        summary: 'Fetch all models and the platforms that include them',
        tags: ['Model']
    )]
    #[OA\Response(
        response: 200,
        description: "List of models",
        content: new OA\JsonContent(
            ref: new Model(type: ModelDTO::class, groups: ['full']),
        )
    )]
    #[Route('/api/models', name: 'app_model_list', methods: ['GET'])]
    public function getModelsWithPlatforms(): Response
    {
        $models = $this->modelRepository->findAll();

        $result = ModelMapper::mapToDTOArray($models);

        return $this->json($result);
    }



    #[OA\Get(
        path: '/api/models/{id}',
        description: 'Get a model by its ID',
        summary: 'Fetch a specific model (id and name only)',
        tags: ['Model']
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
        description: "The requested model",
        content: new OA\JsonContent(
            ref: new Model(type: ModelDTO::class, groups: ['full'])
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Model not found"
    )]
    #[Route('/api/models/{id}', name: 'app_model_show', methods: ['GET'])]
    public function getModelById(int $id): Response
    {
        $model = $this->modelRepository->find($id);

        if (!$model) {
            return $this->json(['message' => 'Model not found'], 404);
        }

        return $this->json(ModelMapper::mapToDTO($model));
    }

}
