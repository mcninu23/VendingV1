<?php
namespace App\System\Application\DTO;

class CoinStatusDT
{
    /**
     * Transform Coin class to frontend format
     * 
     * @param array $coinsList array{\App\System\Domain\Model\Coin\Coin}
     * @return array{id: int, name: string, value: string, quantity: int}
     */
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