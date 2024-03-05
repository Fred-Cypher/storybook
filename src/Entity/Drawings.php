<?php

namespace App\Entity;

use App\Repository\DrawingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrawingsRepository::class)]
class Drawings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'drawings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tales $tales = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTales(): ?Tales
    {
        return $this->tales;
    }

    public function setTales(?Tales $tales): static
    {
        $this->tales = $tales;

        return $this;
    }
}
