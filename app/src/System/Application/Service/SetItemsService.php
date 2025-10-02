<?php
namespace App\System\Application\Service;

use App\System\Infrastructure\Persistence\DoctrineItemRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class SetItemsService
{
    private DoctrineItemRepository $itemRepo;

    public function __construct(
        DoctrineItemRepository $itemRepo
    ) 
    {
        $this->itemRepo = $itemRepo;
    }

    public function execute(array $itemValues): bool
    {
        //Check valid values
        foreach ($itemValues as $values) {
            if(!is_int($values['quantity'])) throw new ConflictHttpException('Invalid value');
        }

        foreach ($itemValues as $values) {
            $item = $this->itemRepo->byId($values['id']);
            if(!$item) continue;
            $item->setQuantity($values['quantity']);
            $this->itemRepo->save($item);
        }

        return true;
    }
}