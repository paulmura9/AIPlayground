<?php

namespace App\DTO\Mappers;

use App\DTO\PromptDTO;
use App\Entity\Prompt;

class PromptMapper
{
    public static function mapToDTO(Prompt $prompt): PromptDTO
    {
        return new PromptDTO(
            $prompt->getId(),
            $prompt->getName(),
            $prompt->getSystemMessage(),
            $prompt->getUserMessage(),
            $prompt->getExpectedResult()
        );
    }

    /**
     * @param Prompt[] $prompts
     * @return PromptDTO[]
     */
    public static function mapToDTOArray(array $prompts): array
    {
        return array_map([self::class, 'mapToDTO'], $prompts);
    }
}