<?php
namespace App\System\Infrastructure\Persistence;

use App\System\Domain\Model\Item\Item;
use App\System\Domain\Repository\Item\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineItemRepository implements ItemRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Item $item): void
    {
        $this->em->persist($item);
        $this->em->flush();
    }

    public function byId(int $id): ?Item
    {
        return $this->em->getRepository(Item::class)->find($id);
    }

    public function all(): array
    {
        return $this->em->getRepository(Item::class)->findBy([], ['id' => 'DESC']);
    }
}
