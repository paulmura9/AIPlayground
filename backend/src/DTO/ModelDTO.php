<?php

namespace App\DTO;

class ModelDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?float $userRating=null,
        public readonly ?float $rating=null,
    ){}
}