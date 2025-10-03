<?php
namespace App\System\Application\DTO;

use App\System\Domain\Model\Coin\Coin;

class ItemStatusDT
{
    /**
     * Transform Item class to frontend format
     * 
     * @param array $coinsList array{\App\System\Domain\Model\Item\Item}
     * @return array{id: int, name: string, value: string, quantity: int}
     */
    public function transform(array $itemsList)
    {
        $availableItems = [];
        foreach ($itemsList as $item) {
            $availableItems[]= array(
                'id' => $item->id(),
                'name' => $item->name(),
                'value' => $item->value(),
                'quantity' => $item->quantity()
            );
        }

        return $availableItems;
    }
}