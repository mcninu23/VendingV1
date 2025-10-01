<?php
namespace App\System\Infrastructure\Http;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class DbHealthController
{
    public function __construct(private Connection $connection) {}

    #[Route('/db/health', name: 'db_health', methods: ['GET'])]
    public function __invoke(): Response
    {
        $this->connection->executeQuery('SELECT 1');
        return new Response('DB OK', 200, ['Content-Type' => 'text/plain']);
    }
}
