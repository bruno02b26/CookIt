<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FractionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FractionRepository::class)]
#[ORM\Table(name: 'fraction')]
#[ApiResource(
    normalizationContext: ['groups' => ['fraction:read']],
    denormalizationContext: ['groups' => ['fraction:write']]
)]
class Fraction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['fraction:read', 'fraction:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 4, unique: true)]
    #[Groups(['fraction:read', 'fraction:write'])]
    private ?string $name = null;

    public function __construct(?string $name)
    {
        $this->name = $name;
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
}