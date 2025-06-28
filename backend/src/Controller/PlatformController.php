<?php

namespace App\Controller;

use App\DTO\Mappers\PlatformMapper;
use App\DTO\PlatformDTO;
use App\Entity\Platform;
use App\Repository\PlatformRepository;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class PlatformController extends AbstractController
{
    public function __construct(private readonly PlatformRepository $platformRepository) //injectare
    {
    }

    #[OA\GET(   //for swagger (openAI)
        path: '/api/platforms',
        description: 'Get all the platforms',
        summary: 'Fetch all the platforms',
        tags: ['Platform'],
    )]

    #[OA\Response(
        response: 200,
        description: "List of all platforms",
        content: new OA\JsonContent(
            ref: new Model(type: PlatformDTO::class, groups: ["full"])
        ),
    )]



    #[Route('/api/platforms', name: 'app_platform',methods: ['GET'])]
    public function getPlatforms(): Response
    {
       $platforms = $this->platformRepository->findAll();
       //dd($platforms);

      return $this->json(PlatformMapper::mapToDTOArray($platforms), Response::HTTP_OK);
    }

    #[OA\Get(
        path: '/api/platforms/{id}',
        description: 'Get a platform by its ID',
        summary: 'Fetch a specific platform',
        tags: ['Platform'],
    )]

    #[OA\Parameter(
        name: 'id',
        description: 'Platform ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'integer')
    )]

    #[OA\Response(
        response: 200,
        description: "The requested platform",
        content: new OA\JsonContent(
            ref: new Model(type: PlatformDTO::class, groups: ['full'])
        )
    )]

    #[OA\Response(
        response: 404,
        description: "Platform not found"
    )]

    #[Route('/api/platforms/{id}', name: 'app_platform_show', methods: ['GET'])]
    public function getPlatform(int $id): Response
    {
        $platform = $this->platformRepository->find($id);

        if (!$platform) {
            return $this->json(['message' => 'Platform not found'], 404);
        }

        return $this->json(PlatformMapper::mapToDTO($platform));
    }

}
