<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipeTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RecipeTypeRepository::class)]
#[ORM\Table(name: 'recipe_type')]
#[ApiResource(
    normalizationContext: ['groups' => ['recipe_type:read']],
    denormalizationContext: ['groups' => ['recipe_type:write']]
)]
class RecipeType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipe_type:read', 'recipe_type:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 25, unique: true)]
    #[Groups(['recipe_type:read', 'recipe_type:write'])]
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
