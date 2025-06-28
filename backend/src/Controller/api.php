<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class api extends AbstractController{
  #[Route('/api/success', name: 'api_success',methods: ['GET'])]
  public function success():JsonResponse {
      return $this->json(
              ['success' => 'success', 'message' => 'working']);
  }
}
