<?php
namespace App\System\Domain\Item;

interface ItemRepository
{
    public function save(Item $item): void;
    public function byId(int $id): ?Item;
    /** @return Item[] */
    public function all(): array;
}
