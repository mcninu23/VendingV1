<?php
namespace App\tests\System\Domain\Model;

use App\System\Domain\Model\Item\Item;

class ItemTest extends Item
{
    protected ?int $id;

    public function setId(?int $id)
    {
        $this->id = $id;
    }
}