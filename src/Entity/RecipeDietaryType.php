<?php

namespace App\Entity;

use App\Repository\RecipeDietaryTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeDietaryTypeRepository::class)]
#[ORM\Table(name: 'recipe_dietary_type')]
#[ORM\UniqueConstraint(
    name: 'id_recipe_id_dietary_type',
    columns: ['id_recipe', 'id_dietary_type']
)]
class RecipeDietaryType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Recipe::class)]
    #[ORM\JoinColumn(name: "id_recipe", referencedColumnName: "id")]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(targetEntity: DietaryType::class)]
    #[ORM\JoinColumn(name: "id_dietary_type", referencedColumnName: "id")]
    private ?DietaryType $dietaryType = null;

    public function __construct(?Recipe $recipe, ?DietaryType $dietaryType)
    {
        $this->recipe = $recipe;
        $this->dietaryType = $dietaryType;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): static
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getDietaryType(): ?DietaryType
    {
        return $this->dietaryType;
    }

    public function setDietaryType(DietaryType $dietaryType): static
    {
        $this->dietaryType = $dietaryType;
        return $this;
    }
}
