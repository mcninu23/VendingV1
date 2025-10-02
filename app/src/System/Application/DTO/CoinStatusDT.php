<?php
namespace App\System\Application\DTO;

use App\System\Domain\Model\Coin\Coin;

class CoinStatusDT
{
    public function transform(array $coinsList)
    {
        $availableCoins = [];
        foreach ($coinsList as $coin) {
            $availableCoins[]= array(
                'id' => $coin->id(),
                'name' => $coin->name(),
                'value' => $coin->value(),
                'quantity' => $coin->quantity()
            );
        }

        return $availableCoins;
    }
}