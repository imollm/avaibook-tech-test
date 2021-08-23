<?php

namespace App\Tests\Repository;

use App\DataFixtures\FakeData;
use App\Repository\AdRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Process\Process;

/**
 *
 */
class AdRepositoryTest extends KernelTestCase
{
    /**
     * @var AdRepository
     */
    private AdRepository $repository;

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        
        $this->repository = $container->get(AdRepository::class);

        try {
            # RESET DATA
            $loadFixtures = new Process(
                ['bin/console', 'doctrine:fixtures:load', '--no-interaction']
            );
            $loadFixtures->run();

        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    /**
     *
     */
    public function testGetAll()
    {
        $expected = FakeData::getAds();

        $result = $this->repository->getAll();

        $this->assertJson(json_encode($expected), json_encode($result));
        $this->assertLessThanOrEqual(count($expected), count($result));
    }

    /**
     *
     */
    public function testGetById()
    {
        $ads = FakeData::getAds();
        $expected = $ads[rand(0, count($ads) - 1)];
        $adId = $expected['id'] + 1;

        $result = $this->repository->getById($adId);

        $this->assertJson(json_encode($expected), json_encode($result));
    }

    /**
     *
     */
    public function testScoresNoCalculated()
    {
        $result = $this->repository->hasScoresCalculated();

        $this->assertFalse($result);
    }

    /**
     *
     */
    public function testScoresCalculated()
    {
        $this->repository
            ->createQueryBuilder('ad')
            ->update('App:Ad', 'ad')
            ->set('ad.score', 0)
            ->getQuery()
            ->getResult();

        $result = $this->repository->hasScoresCalculated();

        $this->assertTrue($result);
    }

    /**
     *
     */
    public function testUpdateScore()
    {
        $ads = FakeData::getAds();
        $randomAd = $ads[rand(0, count($ads) - 1)];
        $adId = $randomAd['id'] + 1;

        $expected = 100;

        $this->repository->updateScore($adId, $expected);

        $ad = $this->repository->getById($adId);
        $result = $ad[0]['score'];

        $this->assertEquals($result, $expected);
    }

    /**
     *
     */
    public function testUpdateIrrelevantSince()
    {
        $adId = 1;

        $expected = date(('Y-m-d H:i:s'), time());

        $this->repository->updateIrrelevantSince($adId);
        $adUpdated = $this->repository->getById($adId);
        $result = ($adUpdated[0]['irrelevantSince'])->format('Y-m-d H:i:s');

        $this->assertEquals($expected, $result);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
