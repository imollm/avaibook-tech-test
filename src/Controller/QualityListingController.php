<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QualityListingController extends AbstractController
{
    #[Route('/api/v1/quality-ads', name: 'quality_listing', methods: ['GET'])]
    public function index(AdRepository $adRepository): JsonResponse | Response
    {
        if (!$adRepository->hasScoresCalculated())
            return new Response('The ad score must be calculated', 404);

        $qualityAds = $adRepository->getQualityAds();

        if (count($qualityAds) <= 0)
            return new Response('No quality ads in system', 404);

        return new JsonResponse(['ads' => $qualityAds], 200);
    }
}
