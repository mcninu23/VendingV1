<?php
namespace App\System\Domain\Coin;

interface CoinRepository
{
    public function save(Coin $coin): void;
    public function byId(int $id): ?Coin;
    /** @return Coin[] */
    public function all(): array;
}
