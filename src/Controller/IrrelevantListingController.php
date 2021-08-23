<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IrrelevantListingController extends AbstractController
{
    #[Route('/api/v1/irrelevant-ads', name: 'public_listing', methods: ['GET'])]
    public function index(AdRepository $adRepository): JsonResponse | Response
    {
        if (!$adRepository->hasScoresCalculated())
            return new Response('The ad score must be calculated', 404);

        if (empty($irrelevantAds = $adRepository->getIrrelevantAds()))
            return new Response('No irrelevant ads in system', 404);

        return new JsonResponse([
            'ads' => $irrelevantAds,
            'quality-ads-avg' => $adRepository->getQualityAdsScoreAvg(),
            'irrelevant-ads-avg' => $adRepository->getIrrelevantAdsScoreAvg(),
            'ads-avg' => $adRepository->getAdsAvg()
        ], 200);
    }
}
