<?php
namespace App\System\Application\Service;

use App\System\Infrastructure\Persistence\DoctrineCoinRepository;
use App\System\Infrastructure\Persistence\DoctrineItemRepository;

class GetVendingStatusService
{
    private DoctrineCoinRepository $coinRepo;
    private DoctrineItemRepository $itemRepo;

    public function __construct(DoctrineCoinRepository $coinRepo, DoctrineItemRepository $itemRepo) 
    {
        $this->coinRepo = $coinRepo;
        $this->itemRepo = $itemRepo;
    }

    public function execute(): array
    {
        $availableCoins = $this->coinRepo->all();
        $availableItems = $this->itemRepo->all();

        return [$availableCoins, $availableItems];
    }
}