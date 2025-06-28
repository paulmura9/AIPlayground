<?php

namespace App\services;

class MetricsService
{
    public static function calculateRating(float $executionTime, int $totalTokens, string $actualResponse,string $expectedResponse): float
    {
        $executionTimeScore = 1.0;
        if($executionTime > 1.0){
            $executionTimeScore = max(0.0,1.0- ($executionTime-1.0) * 0.5);
        }

        $totalTokensScore = 1.0;
        if($totalTokens > 500){
            $totalTokensScore = max(0.0,1.0-($totalTokens-500) / 1000);
        }

        similar_text($expectedResponse, $actualResponse, $percent);
        $similarityScore=$percent/100;

        return ($executionTimeScore*0.2+$totalTokensScore*0.4+$similarityScore*0.4)*100;

    }
}