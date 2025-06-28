<?php

namespace App\DTO;

class ScopeDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}
}