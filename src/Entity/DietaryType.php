<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DietaryTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DietaryTypeRepository::class)]
#[ORM\Table(name: 'dietary_type')]
#[ApiResource(
    normalizationContext: ['groups' => ['dietary_type:read']],
    denormalizationContext: ['groups' => ['dietary_type:write']]
)]
class DietaryType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['dietary_type:read', 'dietary_type:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 25, unique: true)]
    #[Groups(['dietary_type:read', 'dietary_type:write'])]
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