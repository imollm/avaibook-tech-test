<?php

namespace App\Tests\Api;

use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Process\Process;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 *
 */
class CalculateScoreEndpointTest extends WebTestCase
{
    /**
     * @var string
     */
    private string $endpoint = 'http://localhost:8080/api/v1/calculate-scores';

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    protected function setUp(): void
    {
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
    public function testScoresArentCalculated()
    {
        $expected = '{}';

        $response = HttpClient::create()->request('GET', $this->endpoint);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($expected, $response->getContent());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testScoresAreCalculated()
    {
        $expected = 'scores are calculated';

        $httpClient = HttpClient::create();
        $httpClient->request('GET', $this->endpoint);
        $response = $httpClient->request('GET', $this->endpoint);
        $result = json_decode($response->getContent())->message;

        $this->assertEquals(200, $response->getStatusCode());
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
