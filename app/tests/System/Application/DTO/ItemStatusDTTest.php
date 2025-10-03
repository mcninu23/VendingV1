<?php
namespace App\tests\System\Application\DTO;

use App\System\Application\DTO\ItemStatusDT;
use App\tests\System\Domain\Model\ItemTest;
use PHPUnit\Framework\TestCase;

class ItemStatusDTTest extends TestCase
{
    const RESPONSE_LENGTH = 4;

    private ItemStatusDT $itemStatusDT;

    protected function setUp(): void
    {
        $this->itemStatusDT = new itemStatusDT();
    }

    
    public function testCorrectTransform()
    {
        $itemId = 5;
        $itemName = 'Coke';
        $itemValue = '1.65';
        $itemQuantity = 50;
        $item = new ItemTest(
            $itemName,
            $itemValue,
            $itemQuantity
        );
        $item->setId($itemId);

        $response = $this->itemStatusDT->transform([$item]);
        $this->assertIsArray($response);
        $this->assertCount(1, $response);
        $transformedItem = reset($response);
        $this->assertIsArray($transformedItem);
        $this->assertCount(self::RESPONSE_LENGTH, $transformedItem);
        $this->assertEquals($itemId, $transformedItem['id']);
        $this->assertEquals($itemName, $transformedItem['name']);
        $this->assertEquals($itemValue, $transformedItem['value']);
        $this->assertEquals($itemQuantity, $transformedItem['quantity']);
    }
}