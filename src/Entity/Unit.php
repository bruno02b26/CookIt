<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UnitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
#[ORM\Table(name: 'unit')]
#[ApiResource]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 25, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 5, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $abbr = null;

    public function __construct(?string $name, ?string $abbr)
    {
        $this->name = $name;
        $this->abbr = $abbr;
    }

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

    public function getAbbr(): ?string
    {
        return $this->abbr;
    }

    public function setAbbr(string $abbr): static
    {
        $this->abbr = $abbr;
        return $this;
    }
}