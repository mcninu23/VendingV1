<?php
namespace App\System\Domain\Model\Coin;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'coin')]
class Coin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 120)]
    private string $name;

    // decimal(4,2) -> se maneja como string en PHP
    #[ORM\Column(type: 'decimal', precision: 4, scale: 2)]
    private string $value;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $quantity = 0;

    public function __construct(string $name, string $value, int $quantity)
    {
        $this->name = $name;
        $this->value = $value;
        $this->quantity = $quantity;
    }

    public function id(): ?int { return $this->id; }
    public function name(): string { return $this->name; }
    public function value(): string { return $this->value; }
    public function quantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): Coin 
    {
        $this->quantity = $quantity;
        return $this;
    }
}