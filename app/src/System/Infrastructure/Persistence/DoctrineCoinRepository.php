<?php
namespace App\System\Infrastructure\Persistence;

use App\System\Domain\Coin\Coin;
use App\System\Domain\Coin\CoinRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineCoinRepository implements CoinRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Coin $coin): void
    {
        $this->em->persist($coin);
        $this->em->flush();
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
