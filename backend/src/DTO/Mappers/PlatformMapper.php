<?php

namespace App\DTO\Mappers;

use App\DTO\PlatformDTO;
use App\Entity\Platform;

class PlatformMapper
{
    public static function mapToDTO(Platform $platform): PlatformDTO
    {
        $models = ModelMapper::mapToDTOArray($platform->getModels()->toArray());
        return new PlatformDTO(
            $platform->getID(),
            $platform->getName(),
            $platform->getBaseUrl(),
            $platform->getImageUrl(),
            $models
        );
    }

    /**
     * @param array<Platform> $platforms
     * @return array<PlatformDTO>
     */
        public static function mapToDTOArray(array $platforms): array
        {
            return array_map(function ($platform) {
                return self::mapToDTO($platform);
            },$platforms);
        }

}