<?php

namespace App\Tests\FakeData;

use App\Repository\AdRepository;
use App\Services\CalculateScoresService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FakeDataTest extends KernelTestCase
{
    private CalculateScoresService $service;
    private AdRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->service = $container->get(CalculateScoresService::class);
        $this->repository = $container->get(AdRepository::class);
    }

    public function testScoreAds()
    {
        $fakeAds = $this->repository->getAll();

        $expectedScores = [0, 90, 20, 80, 75, 50, 0, 25];

        foreach ($fakeAds as $index => $fakeAd) {
            $result = $this->service->calculateScore($fakeAd);

            $this->assertEquals($expectedScores[$index], $result);
        }
    }
}
