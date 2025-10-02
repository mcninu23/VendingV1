<?php
namespace App\System\Application\Service;

use App\System\Infrastructure\Persistence\DoctrineSaleRepository;

class GetTotalCollectedService
{
    private DoctrineSaleRepository $saleRepo;

    public function __construct(
        DoctrinesaleRepository $saleRepo
    ) 
    {
        $this->saleRepo = $saleRepo;
    }

    public function execute(): int
    {
        $sales = $this->saleRepo->all();

        $totalCents = 0;
        foreach ($sales as $sale) {
            $totalCents += $sale->amount();
        }

        return $totalCents;
    }
}