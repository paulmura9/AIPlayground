<?php

namespace App\DTO;

class PlatformDTO
{
    /**
     * @param int $id
     * @param string $name
     * @param string $baseUrl
     * @param string $imageUrl
     * @param array<ModelDTO> $models
     */
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $baseUrl,
        public readonly string $imageUrl,
        public readonly array $models
    )
    {
    }

}
