<?php
namespace App\System\Application\Service;

use App\System\Infrastructure\Persistence\DoctrineCoinRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class SetCoinsService
{
    private DoctrineCoinRepository $coinRepo;

    public function __construct(
        DoctrineCoinRepository $coinRepo
    ) 
    {
        $this->coinRepo = $coinRepo;
    }

    public function execute(array $coinValues): bool
    {
        //Check valid values
        foreach ($coinValues as $values) {
            if(!is_int($values['quantity'])) throw new ConflictHttpException('Invalid value');
        }

        foreach ($coinValues as $values) {
            $coin = $this->coinRepo->byId($values['id']);
            if(!$coin) continue;
            $coin->setQuantity($values['quantity']);
            $this->coinRepo->save($coin);
        }

        return true;
    }
}