<?php
namespace App\System\Domain\Model\Sale;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'sale')]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeImmutable $createdAt;

    //amount in cents
    #[ORM\Column(type: 'integer')]
    private int $amount;

    public function __construct(int $amount)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->amount = $amount;
    }

    public function id(): ?int { return $this->id; }
    public function createdAt(): \DateTimeImmutable { return $this->createdAt; }
    public function amount(): int { return $this->amount; }
}