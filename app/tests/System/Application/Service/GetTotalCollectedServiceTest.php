<?php
namespace App\tests\System\Application\Service;

use App\System\Application\Service\GetTotalCollectedService;
use App\System\Domain\Model\Sale\Sale;
use App\System\Infrastructure\Persistence\DoctrineSaleRepository;;
use PHPUnit\Framework\TestCase;

final class GetTotalCollectedServiceTest extends TestCase
{
    private DoctrineSaleRepository $saleRepo;
    private GetTotalCollectedService $getTotalCollectedService;

    protected function setUp(): void
    {
        $this->saleRepo = $this->createMock(DoctrineSaleRepository::class);
        $this->getTotalCollectedService = new GetTotalCollectedService($this->saleRepo);
    }

    
    public function testCorrectResponse()
    {
        $sale100 = new Sale(100);
        $sale50 = new Sale(50);
        $sales = [$sale100, $sale50];

        $this->saleRepo->method("all")->willReturn($sales);

        $totalInCents = $this->getTotalCollectedService->execute();

        $this->assertIsInt($totalInCents);
        $this->assertEquals(150, $totalInCents);
    }
}