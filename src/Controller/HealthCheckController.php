<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HealthCheckController
{
    public function __invoke(): Response
    {
        return new Response('ok', 200);
    }
}
