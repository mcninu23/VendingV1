<?php
namespace App\System\Application\DTO;

use App\System\Domain\Model\Coin\Coin;

class ItemStatusDT
{
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