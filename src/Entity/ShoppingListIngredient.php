<?php

namespace App\Entity;

use App\Repository\ShoppingListIngredientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ShoppingListIngredientRepository::class)]
#[ORM\Table(name: 'shopping_list_ingredient')]
class ShoppingListIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ShoppingList::class)]
    #[ORM\JoinColumn(name: "id_shopping_list", referencedColumnName: "id")]
    private ?ShoppingList $shoppingList = null;

    #[ORM\Column]
    #[Assert\GreaterThan(0)]
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

    public function __construct(?ShoppingList $shoppingList, ?int $quantity, ?Fraction $fraction, ?Unit $unit, ?Ingredient $ingredient, ?string $note)
    {
        $this->shoppingList = $shoppingList;
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

    public function getShoppingList(): ?ShoppingList
    {
        return $this->shoppingList;
    }

    public function setShoppingList(ShoppingList $shoppingList): static
    {
        $this->shoppingList = $shoppingList;
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
