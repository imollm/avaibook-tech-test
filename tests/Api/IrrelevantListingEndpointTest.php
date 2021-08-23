<?php

namespace App\Tests\Api;

use App\Repository\AdRepository;
use App\Services\CalculateScoresService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Process\Process;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class IrrelevantListingEndpointTest extends WebTestCase
{
    private AdRepository $adRepository;
    private CalculateScoresService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->adRepository = $container->get(AdRepository::class);
        $this->service = $container->get(CalculateScoresService::class);

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
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testEndpointNoScoresCalculated()
    {
        $expectedStatusCode = 404;
        $expectedBodyMessage = 'The ad score must be calculated';

        try {
            HttpClient::create()->request('GET', 'http://localhost:8080/api/v1/irrelevant-ads');
        } catch (ClientExceptionInterface $exception) {
            $this->assertEquals($expectedStatusCode, $exception->getCode());
            $this->assertEquals($expectedBodyMessage, $exception->getResponse()->getContent(false));
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testEndpointNoIrrelevantAdsAre()
    {
        $expectedStatusCode = 404;
        $expectedBodyMessage = 'No irrelevant ads in system';

        $this->adRepository->createQueryBuilder('ad')
            ->update('App:Ad', 'ad')
            ->set('ad.score', 100)
            ->getQuery()
            ->getResult();

            $response = HttpClient::create()->request('GET', 'http://localhost:8080/api/v1/irrelevant-ads');

            $this->assertEquals($expectedStatusCode, $response->getStatusCode());
            $this->assertEquals($expectedBodyMessage, $response->getContent(false));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testEndpointGetIrrelevantAds()
    {
        $expectedStatusCode = 200;
        $expectedBodyKey = 'ads';

        $this->service->exec();

        $response = HttpClient::create()->request('GET', 'http://localhost:8080/api/v1/quality-ads');

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());
        $this->assertObjectHasAttribute($expectedBodyKey, json_decode($response->getContent(false)));
    }
}
