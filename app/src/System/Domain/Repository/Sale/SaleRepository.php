<?php
namespace App\System\Domain\Repository\Sale;

use App\System\Domain\Model\Sale\Sale;

interface SaleRepository
{
    public function save(Sale $sale): void;
    /** @return Sale[] */
    public function all(): array;
}
