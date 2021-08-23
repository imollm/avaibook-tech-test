<?php

namespace App\Controller;

use App\Services\CalculateScoresService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CalculateScoresController extends AbstractController
{
    #[Route('/api/v1/calculate-scores', name: 'calculate_scores', methods: ['GET'])]
    public function index(CalculateScoresService $service): JsonResponse
    {
        return new JsonResponse($service->exec(), 200);
    }
}
