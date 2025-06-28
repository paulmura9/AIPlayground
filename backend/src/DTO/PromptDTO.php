<?php

namespace App\DTO;

/**
 * @param RunDTO[] $runs
 * @param array{id: int, name: string}|null $scope
 */
class PromptDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $systemMessage,
        public readonly string $userMessage,
        public readonly string $expectedResult
    ) {}
}