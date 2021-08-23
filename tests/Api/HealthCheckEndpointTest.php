<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthCheckEndpointTest extends WebTestCase
{
    public function testHealthCheckEndpoint()
    {
        $client = static::createClient();
        $client->request('GET', '/healthcheck');
        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertEquals('ok', $response->getContent());
    }
}
