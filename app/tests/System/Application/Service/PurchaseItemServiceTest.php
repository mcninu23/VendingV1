<?php
namespace App\tests\System\Application\Service;

use App\System\Application\Service\PurchaseItemService;
use App\System\Domain\Model\Coin\Coin;
use App\System\Domain\Model\Item\Item;
use App\System\Infrastructure\Persistence\DoctrineCoinRepository;
use App\System\Infrastructure\Persistence\DoctrineItemRepository;
use App\System\Infrastructure\Persistence\DoctrineSaleRepository;
use App\tests\System\Domain\Model\CoinTest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PurchaseItemServiceTest extends TestCase
{
    private DoctrineCoinRepository $coinRepo;
    private DoctrineItemRepository $itemRepo;
    private DoctrineSaleRepository $saleRepo;
    private PurchaseItemService $purchaseItemService;

    protected function setUp(): void
    {
        $this->coinRepo = $this->createMock(DoctrineCoinRepository::class);
        $this->itemRepo = $this->createMock(DoctrineItemRepository::class);
        $this->saleRepo = $this->createMock(DoctrineSaleRepository::class);
        $this->purchaseItemService = new PurchaseItemService(
            $this->coinRepo, 
            $this->itemRepo,
            $this->saleRepo
        );
    }

    public function testInvalidItemIdException()
    {
        $itemId = 0;
        $insertedCents = [];

        $this->expectException(ConflictHttpException::class);
        $this->expectExceptionMessage("Invalid item id");
        $this->purchaseItemService->execute($itemId, $insertedCents);
    }

    public function testItemNotFoundException()
    {
        $itemId = 6;
        $insertedCents = [];

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("Item not found");
        $this->purchaseItemService->execute($itemId, $insertedCents);
    }

    public function testItemNotAvailableException()
    {
        $itemId = 6;
        $insertedCents = [];

        $item = new Item("item", "1.50", 0);

        $this->itemRepo->method("byId")->willReturn($item);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("Item not available");
        $this->purchaseItemService->execute($itemId, $insertedCents);
    }

    public function testNotEnoughChangeException()
    {
        $itemId = 6;
        $insertedCents = [100, 100];

        $item = new Item("item", "1.50", 2);

        $this->itemRepo->method("byId")->willReturn($item);
        $coin = new Coin("25 Cents", "0.25", 1);
        $this->coinRepo->method("all")->willReturn([$coin]);

        $this->expectException(ConflictHttpException::class);
        $this->expectExceptionMessage("Not enough change");
        $this->purchaseItemService->execute($itemId, $insertedCents);
    }

    public function testCorrectResponse()
    {
        $itemId = 6;
        $insertedCents = [100, 100];

        $item = new Item("item", "1.50", 2);

        $this->itemRepo->method("byId")->willReturn($item);
        $coin25 = new CoinTest("25 Cents", "0.25", 1);
        $coin25->setId(25);
        $coin5 = new CoinTest("5 Cents", "0.05",5);
        $coin5->setId(5);
        $this->coinRepo->method("all")->willReturn([$coin25, $coin5]);

        $result = $this->purchaseItemService->execute($itemId, $insertedCents);
        $this->assertIsArray($result);
        $coinsResponse = $result["coins"];
        $this->assertIsArray($coinsResponse);
        $this->assertCount(2, $coinsResponse);
        $firstCoinResponse = $coinsResponse[0];
        $this->assertIsArray($firstCoinResponse);
        $this->assertEquals("0.25", $firstCoinResponse["value"]);
        $this->assertEquals(1, $firstCoinResponse["quantity"]);
        $secondCoinResponse = $coinsResponse[1];
        $this->assertIsArray($secondCoinResponse);
        $this->assertEquals("0.05", $secondCoinResponse["value"]);
        $this->assertEquals(5, $secondCoinResponse["quantity"]);
    }

    
    /* public function testItemNotFoundException()
    {
        $this->coinRepo->method("all")->willReturn([]);
        $this->itemRepo->method("all")->willReturn([]);

        $status = $this->purchaseItemService->execute();
        $coins = reset($status);
        $items = end($status);

        $this->assertIsArray($status);
        $this->assertIsArray($coins);
        $this->assertIsArray($items);
    } */
}