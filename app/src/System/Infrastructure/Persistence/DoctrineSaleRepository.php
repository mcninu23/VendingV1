<?php
namespace App\System\Infrastructure\Persistence;

use App\System\Domain\Model\Sale\Sale;
use App\System\Domain\Repository\Sale\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSaleRepository implements SaleRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(Sale $sale): void
    {
        $this->em->persist($sale);
        $this->em->flush();
    }

    public function all(): array
    {
        return $this->em->getRepository(Sale::class)->findBy([], ['id' => 'DESC']);
    }
}
