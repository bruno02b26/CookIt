<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CuisineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CuisineRepository::class)]
#[ORM\Table(name: 'cuisine')]
#[ApiResource(
    normalizationContext: ['groups' => ['cuisine:read']],
    denormalizationContext: ['groups' => ['cuisine:write']]
)]
class Cuisine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['cuisine:read', 'cuisine:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 25, unique: true)]
    #[Groups(['cuisine:read', 'cuisine:write'])]
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