<?php
namespace App\System\Infrastructure\Persistence;

use App\System\Domain\Model\Coin\Coin;
use App\System\Domain\Repository\Coin\CoinRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCoinRepository implements CoinRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Coin $coin, bool $flush = true): void
    {
        $this->em->persist($coin);

        if($flush) $this->em->flush();
    }

    public function byId(int $id): ?Coin
    {
        return $this->em->getRepository(Coin::class)->find($id);
    }

    public function all(): array
    {
        return $this->em->getRepository(Coin::class)->findBy([], ['id' => 'DESC']);
    }
}
