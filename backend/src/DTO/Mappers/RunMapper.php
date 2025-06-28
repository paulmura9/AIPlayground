<?php

namespace App\DTO\Mappers;

use App\DTO\RunDTO;
use App\Entity\Run;

class RunMapper
{
    public static function mapToDTO(Run $run): RunDTO
    {
        return new RunDTO(
            id: $run->getId(),
            temperature: $run->getTemperature(),
            actualResponse: $run->getActualResponse(),
            rating: $run->getRating(),
            userRating: $run->getUserRating()
        );
    }

    /**
     * @param array<Run> $runs
     * @return array<RunDTO>
     */
    public static function mapToDTOArray(array $runs): array
    {
        return array_map([self::class, 'mapToDTO'], $runs);
    }
}