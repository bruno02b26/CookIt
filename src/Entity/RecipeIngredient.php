<?php

namespace App\Entity;

use App\Repository\RecipeIngredientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeIngredientRepository::class)]
#[ORM\Table(name: 'recipe_ingredient')]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Recipe::class)]
    #[ORM\JoinColumn(name: "id_recipe", referencedColumnName: "id")]
    private ?Recipe $recipe = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantity = null;

    #[ORM\ManyToOne(targetEntity: Fraction::class)]
    #[ORM\JoinColumn(name: "id_fraction", referencedColumnName: "id")]
    private ?Fraction $fraction = null;

    #[ORM\ManyToOne(targetEntity: Unit::class)]
    #[ORM\JoinColumn(name: "id_unit", referencedColumnName: "id")]
    private ?Unit $unit = null;

    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(name: "id_ingredient", referencedColumnName: "id")]
    private ?Ingredient $ingredient = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $note = null;

    public function __construct(?Recipe $recipe, ?int $quantity, ?Fraction $fraction, ?Unit $unit, ?Ingredient $ingredient, ?string $note)
    {
        $this->recipe = $recipe;
        $this->quantity = $quantity;
        $this->fraction = $fraction;
        $this->unit = $unit;
        $this->ingredient = $ingredient;
        $this->note = $note;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getFraction(): ?Fraction
    {
        return $this->fraction;
    }

    public function setFraction(Fraction $fraction): static
    {
        $this->fraction = $fraction;
        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(Unit $unit): static
    {
        $this->unit = $unit;
        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;
        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;
        return $this;
    }
}
