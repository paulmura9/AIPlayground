<?php

namespace App\DTO\Mappers;

use App\DTO\ScopeDTO;
use App\Entity\Scope;

class ScopeMapper
{
    public static function mapToDTO(Scope $scope): ScopeDTO
    {
        return new ScopeDTO(
            $scope->getId(),
            $scope->getName()
        );
    }

    /**
     * @param array<Scope> $scopes
     * @return array<ScopeDTO>
     */
    public static function mapToDTOArray(array $scopes): array
    {
        return array_map([self::class, 'mapToDTO'], $scopes);
    }
}