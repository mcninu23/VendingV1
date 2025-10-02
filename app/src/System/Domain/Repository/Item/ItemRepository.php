<?php
namespace App\System\Domain\Repository\Item;

use App\System\Domain\Model\Item\Item;

interface ItemRepository
{
    public function save(Item $item): void;
    public function byId(int $id): ?Item;
    /** @return Item[] */
    public function all(): array;
}
