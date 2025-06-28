<?php

namespace App\DTO\Mappers;

use App\DTO\ModelDTO;
use App\Entity\Model;

class ModelMapper
{
    public static function mapToDTO(Model $model):ModelDTO
    {
        $runs= $model->getRuns();
        $totalUserRating=0;
        $totalRating=0;
        $countUserRating=0;
        $countRating=0;

        foreach ($runs as $run) {
            if($run->getUserRating() !== null) {
                $totalUserRating+=$run->getUserRating();
                $countUserRating++;
            }
            if($run->getUserRating() !== null) {
                $totalRating+=$run->getUserRating();
                $countRating++;
            }
        }

        $userRating=$countUserRating>0?$totalUserRating/$countUserRating:0.0;
        $rating=$countRating>0?$totalRating/$countRating:0.0;


        return new ModelDTO($model->getId(),$model->getName(),$userRating,$rating);
    }

    /**
     * @param array<Model> $models
     * @return array|ModelDTO[]
     */
    public static function mapToDTOArray(array $model): array
    {


        return array_map(
            function (Model $model) {
                return self::mapToDTO($model);
            },
            $model
        );
//        foreach ($models as $model) {
//            self::mapToDTO($model);
//        }
    }
}