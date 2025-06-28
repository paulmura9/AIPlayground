<?php

namespace App\services;

use App\Entity\Model;
use App\Entity\Prompt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ModelService
{
    public function __construct(private readonly HttpClientInterface $httpClient) {}

    public function callModelAPI(Model $model,Prompt $prompt,float $temperature): array | null{
        $platform=$model->getPlatform();
        $platformName=strtolower($platform->getName());
        //dd($platformName);

        return match($platformName){
            'openai' => $this->openAiGenerateResponse($platform->getBaseUrl(),$model->getName(),$prompt,$temperature),
            'deepseek' => $this->deepSeekGenerateResponse($platform->getBaseUrl(), $model->getName(), $prompt, $temperature),
            'gemini'   => $this->geminiGenerateResponse($platform->getBaseUrl(), $model->getName(), $prompt, $temperature),
            default => null
        };
    }

    public function openAiGenerateResponse(string $platformBaseUrl,string $modelName,Prompt $prompt,float $temperature): array
    {
        $starTime=microtime(true);
        $response = $this->httpClient->request('POST',$platformBaseUrl . '/v1/chat/completions',[
            'headers' => [
                'Authorization' => 'Bearer ' . $_ENV['OPENAI_API_KEY'],
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $modelName,
                'messages' => [
                    ['role' => 'system', 'content' => $prompt->getSystemMessage()],
                    ['role' => 'user', 'content' => $prompt->getUserMessage()]
                ],
                'temperature' => $temperature
            ]
        ]);
        $actualResponse= $response->toArray()['choices'][0]['message']['content'] ?? 'No response';
        $totalTokens=$response->toArray()['usage']['total_tokens'] ?? 0;

        $endTime=microtime(true);

        $executionTime=$endTime-$starTime;

        return [
            "actualResponse" => $actualResponse,
            "totalTokens" => $totalTokens,
            "executionTime" => $executionTime,
        ];
    }

    public function deepSeekGenerateResponse(string $platformBaseUrl, string $modelName, Prompt $prompt, float $temperature): array
    {
        $starTime=microtime(true);
        $response = $this->httpClient->request('POST', $platformBaseUrl . '/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $_ENV['DEEPSEEK_API_KEY'],
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $modelName,
                'messages' => [
                    ['role' => 'system', 'content' => $prompt->getSystemMessage()],
                    ['role' => 'user', 'content' => $prompt->getUserMessage()],
                ],
                'temperature' => $temperature,
            ],
        ]);
        $actualResponse= $response->toArray()['choices'][0]['message']['content'] ?? 'No response';
        $totalTokens=$response->toArray()['usage']['total_tokens'] ?? 0;

        $endTime=microtime(true);

        $executionTime=$endTime-$starTime;

        return [
            "actualResponse" => $actualResponse,
            "totalTokens" => $totalTokens,
            "executionTime" => $executionTime,
        ];
    }

    public function geminiGenerateResponse(string $platformBaseUrl, string $modelName, Prompt $prompt, float $temperature): array
    {
        $starTime=microtime(true);

        $url = $platformBaseUrl . '/v1beta/models/' . $modelName . ':generateContent?key=' . $_ENV['GEMINI_API_KEY'];

        $response = $this->httpClient->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'contents' => [
                    ['parts' => [['text' => $prompt->getUserMessage()]]]
                ],
                'generationConfig' => [
                    'temperature' => $temperature,
                ],
            ],
        ]);
        $actualResponse= $response->toArray()['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';
        $totalTokens=$response->toArray()['usageMetedata']['totalTokenCount'] ?? 0;

        $endTime=microtime(true);

        $executionTime=$endTime-$starTime;

        return [
            "actualResponse" => $actualResponse,
            "totalTokens" => $totalTokens,
            "executionTime" => $executionTime,
        ];


    }

}