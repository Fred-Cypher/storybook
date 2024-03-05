<?php

namespace App\Entity;

use App\Repository\CoversRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoversRepository::class)]
class Covers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'covers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Games $games = null;

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

    public function getGames(): ?Games
    {
        return $this->games;
    }

    public function setGames(?Games $games): static
    {
        $this->games = $games;

        return $this;
    }
}
