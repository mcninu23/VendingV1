<?php
namespace App\tests\System\Domain\Model;

use App\System\Domain\Model\Coin\Coin;

class CoinTest extends Coin
{
    protected ?int $id;

    public function setId(?int $id)
    {
        $this->id = $id;
    }
}