<?php
namespace App\tests\System\Application\Service;

use App\System\Application\Service\GetVendingStatusService;;
use App\System\Infrastructure\Persistence\DoctrineCoinRepository;
use App\System\Infrastructure\Persistence\DoctrineItemRepository;
use PHPUnit\Framework\TestCase;

final class GetVendingStatusServiceTest extends TestCase
{
    private DoctrineCoinRepository $coinRepo;
    private DoctrineItemRepository $itemRepo;
    private GetVendingStatusService $getVendingStatusService;

    protected function setUp(): void
    {
        $this->coinRepo = $this->createMock(DoctrineCoinRepository::class);
        $this->itemRepo = $this->createMock(DoctrineItemRepository::class);
        $this->getVendingStatusService = new GetVendingStatusService($this->coinRepo, $this->itemRepo);
    }

    
    public function testCorrectResponse()
    {
        $this->coinRepo->method("all")->willReturn([]);
        $this->itemRepo->method("all")->willReturn([]);

        $status = $this->getVendingStatusService->execute();
        $coins = reset($status);
        $items = end($status);

        $this->assertIsArray($status);
        $this->assertIsArray($coins);
        $this->assertIsArray($items);
    }
}