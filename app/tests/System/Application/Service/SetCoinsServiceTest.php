<?php
namespace App\tests\System\Application\Service;

use App\System\Application\Service\SetCoinsService;
use App\System\Domain\Model\Coin\Coin;
use App\System\Infrastructure\Persistence\DoctrineCoinRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

final class SetCoinsServiceTest extends TestCase
{
    private DoctrineCoinRepository $coinRepo;
    private SetCoinsService $setCoinsService;

    protected function setUp(): void
    {
        $this->coinRepo = $this->createMock(DoctrineCoinRepository::class);
        $this->setCoinsService = new SetCoinsService($this->coinRepo);
    }

    public function testInvalidValueException()
    {
        $valuesArray = [['id'=>1, 'quantity' => "E"]];

        $this->expectException(ConflictHttpException::class);
        $this->expectExceptionMessage("Invalid value");
        $this->setCoinsService->execute($valuesArray);
    }

    
    public function testCorrectResponse()
    {
        $this->coinRepo->method("byId")->willReturn(new Coin("", "0.01", 1));
        $valuesArray = [['id'=>1, 'quantity' => 12]];

        $response = $this->setCoinsService->execute($valuesArray);
        $this->assertTrue($response);
    }
}