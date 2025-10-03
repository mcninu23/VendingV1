<?php
namespace App\tests\System\Application\DTO;

use App\System\Application\DTO\CoinStatusDT;
use App\tests\System\Domain\Model\CoinTest;
use PHPUnit\Framework\TestCase;

class CoinStatusDTTest extends TestCase
{
    const RESPONSE_LENGTH = 4;

    private CoinStatusDT $coinStatusDT;

    protected function setUp(): void
    {
        $this->coinStatusDT = new CoinStatusDT();
    }

    
    public function testCorrectTransform()
    {
        $coinId = 5;
        $coinName = '1Cent';
        $coinValue = '0.01';
        $coinQuantity = 90;
        $coin = new CoinTest(
            $coinName,
            $coinValue,
            $coinQuantity
        );
        $coin->setId($coinId);

        $response = $this->coinStatusDT->transform([$coin]);
        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $transformedCoin = reset($response);
        $this->assertIsArray($transformedCoin);
        $this->assertCount(self::RESPONSE_LENGTH, $transformedCoin);
        $this->assertEquals($coinId, $transformedCoin['id']);
        $this->assertEquals($coinName, $transformedCoin['name']);
        $this->assertEquals($coinValue, $transformedCoin['value']);
        $this->assertEquals($coinQuantity, $transformedCoin['quantity']);
    }
}