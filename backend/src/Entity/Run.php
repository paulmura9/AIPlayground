<?php

namespace App\Entity;

use App\Repository\RunRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RunRepository::class)]
class Run
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'modelPrompts')]
    private ?Model $model = null;

    #[ORM\ManyToOne(inversedBy: 'modelPrompts')]
    private ?Prompt $prompt = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column(length: 1000)]
    private ?string $actualResponse = null;

    #[ORM\Column]
    private ?float $rating = null;

    #[ORM\Column]
    private ?float $userRating = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getPrompt(): ?Prompt
    {
        return $this->prompt;
    }

    public function setPrompt(?Prompt $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getActualResponse(): ?string
    {
        return $this->actualResponse;
    }

    public function setActualResponse(string $actualResponse): static
    {
        $this->actualResponse = $actualResponse;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getUserRating(): ?float
    {
        return $this->userRating;
    }

    public function setUserRating(float $userRating): static
    {
        $this->userRating = $userRating;

        return $this;
    }
}