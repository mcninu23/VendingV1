<?php

namespace App\System\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HealthcheckController
{
    #[Route('/health', name: 'system_health', methods: ['GET'])]
    public function __invoke(): Response
    {
        // Capa Infrastructure exponiendo un caso de uso trivial (sin dominio)
        return new Response('OK', 200, ['Content-Type' => 'text/plain']);
    }
}