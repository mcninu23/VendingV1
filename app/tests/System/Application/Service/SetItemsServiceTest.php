<?php
namespace App\tests\System\Application\Service;

use App\System\Application\Service\SetCoinsService;
use App\System\Application\Service\SetItemsService;
use App\System\Domain\Model\Item\Item;
use App\System\Infrastructure\Persistence\DoctrineItemRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

final class SetItemsServiceTest extends TestCase
{
    private DoctrineItemRepository $itemRepo;
    private SetItemsService $setItemsService;

    protected function setUp(): void
    {
        $this->itemRepo = $this->createMock(DoctrineItemRepository::class);
        $this->setItemsService = new SetItemsService($this->itemRepo);
    }

    public function testInvalidValueException()
    {
        $valuesArray = [['id'=>1, 'quantity' => "E"]];

        $this->expectException(ConflictHttpException::class);
        $this->expectExceptionMessage("Invalid value");
        $this->setItemsService->execute($valuesArray);
    }

    
    public function testCorrectResponse()
    {
        $this->itemRepo->method("byId")->willReturn(new Item("", "0.01", 1));
        $valuesArray = [['id'=>1, 'quantity' => 12]];

        $response = $this->setItemsService->execute($valuesArray);
        $this->assertTrue($response);
    }
}