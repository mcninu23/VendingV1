<?php
namespace App\System\Domain\Item;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'item')]
class Item
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

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function id(): ?int { return $this->id; }
    public function name(): string { return $this->name; }
    public function value(): string { return $this->value; }
}