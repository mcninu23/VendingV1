<?php
namespace App\System\Application\Service;

use App\System\Domain\Model\Sale\Sale;
use App\System\Infrastructure\Persistence\DoctrineCoinRepository;
use App\System\Infrastructure\Persistence\DoctrineItemRepository;
use App\System\Infrastructure\Persistence\DoctrineSaleRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchaseItemService
{
    private DoctrineCoinRepository $coinRepo;
    private DoctrineItemRepository $itemRepo;
    private DoctrineSaleRepository $saleRepo;

    public function __construct(
        DoctrineCoinRepository $coinRepo, 
        DoctrineItemRepository $itemRepo,
        DoctrineSaleRepository $saleRepo
    ) 
    {
        $this->coinRepo = $coinRepo;
        $this->itemRepo = $itemRepo;
        $this->saleRepo = $saleRepo;
    }

    /**
     * Calculate change with Item and inserted coins
     * 
     * @param int $itemId
     * @param array $insertedCents
     * @throws \Symfony\Component\HttpKernel\Exception\ConflictHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * Return coins to return an Item name
     * @return array{coins: array{value: string, quantity: int}, itemName: string}
     */
    public function execute(int $itemId, array $insertedCents): array
    {
        if (!is_int($itemId) || $itemId <= 0) throw new ConflictHttpException("Invalid item id");

        //Check item availability
        $item = $this->itemRepo->byId($itemId);

        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }

        if ($item->quantity() < 1) {
            throw new NotFoundHttpException('Item not available');
        }

        //total amount
        $totalCents = array_sum($insertedCents);
        $itemPriceCents = (int) ((float) $item->value() * 100);
        $totalCentsToReturn = $totalCents - $itemPriceCents;

        //available coins for change
        $availableCoins = $this->coinRepo->all();
        $availableCoinsInCents = array_map(
            fn($coin) => [
                'id' => $coin->id(), 
                'cents' => (int) ((float) $coin->value() * 100),
                'quantity' => $coin->quantity()
            ], 
            $availableCoins
        );        
        rsort($availableCoinsInCents);

        //calculate change
        $coinsToReduceInDB = [];
        $remainingCents = $totalCentsToReturn;
        $restOfCents = $remainingCents;

        foreach ($availableCoinsInCents as $idAndCents) {
            if ($remainingCents <= 0) break;
            if ($remainingCents < $idAndCents['cents']) continue;

            $numberOfCoins = intdiv($remainingCents, $idAndCents['cents']);
            //Has necessary coins of this type?
            if ($numberOfCoins > $idAndCents['quantity']) {
                $numberOfCoins = $idAndCents['quantity'];
                $centsToSubstract = $idAndCents['cents'] * $idAndCents['quantity'];              
                $restOfCents -= $centsToSubstract;
            } else {
                $restOfCents = (int) $remainingCents % $idAndCents['cents'];
            }

            if ($numberOfCoins > 0) {
                $coinsToReduceInDB[$idAndCents['id']] = $numberOfCoins;
            }

            if ($restOfCents <= 0) break;
            $remainingCents = $restOfCents;
        }

        //not enough change coins
        if ($restOfCents > 0) throw new ConflictHttpException('Not enough change');

        //purchase OK
        //decrease item quantity
        $item->setQuantity($item->quantity() - 1);
        $this->itemRepo->save($item);

        //decrease coins quantity
        $coinsToReturnToClient = [];
        foreach ($coinsToReduceInDB as $coinId => $quantityToReduce) {
            $coinToReduce = array_find($availableCoins, function ($coin) use ($coinId) {
                return $coin->id() == $coinId;
            });
            $coinToReduce->setQuantity($coinToReduce->quantity() - $quantityToReduce);
            $coinsToReturnToClient[] = ['value' => $coinToReduce->value(), 'quantity' => $quantityToReduce];
            $this->coinRepo->save($coinToReduce);
        }

        //save sale
        $newSale = new Sale($totalCents);
        $this->saleRepo->save($newSale);

        return ['coins' => $coinsToReturnToClient, 'itemName' => $item->name()];
    }
}