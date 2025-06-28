<?php

namespace App\DTO;

class RunDTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?float $temperature = null,
        public readonly string $actualResponse,
        public readonly ?float $rating = null,
        public readonly ?float $userRating = null,
    ) {
    }
}
