<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'recipe')]
#[ApiResource]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id")]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addTime = null;

    #[ORM\Column(length: 50)]
    private ?string $image = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\GreaterThan(0)]
    private ?int $prepTime = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\GreaterThan(0)]
    private ?int $noServing = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\GreaterThan(0)]
    private ?int $noIngredient = null;

    #[ORM\ManyToOne(targetEntity: RecipeType::class)]
    #[ORM\JoinColumn(name: "id_recipe_type", referencedColumnName: "id")]
    private ?RecipeType $recipeType = null;

    #[ORM\ManyToOne(targetEntity: Cuisine::class)]
    #[ORM\JoinColumn(name: "id_cuisine", referencedColumnName: "id")]
    private ?Cuisine $cuisine = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 2000)]
    private ?string $preparation = null;

    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe')]
    private Collection $ingredients;

    public function __construct(
        string $title,
        User $user,
        \DateTimeInterface $addTime,
        string $image,
        int $prepTime,
        int $noServing,
        int $noIngredient,
        RecipeType $recipeType,
        Cuisine $cuisine,
        string $description,
        string $preparation
    ) {
        $this->title = $title;
        $this->user = $user;
        $this->addTime = $addTime;
        $this->image = $image;
        $this->prepTime = $prepTime;
        $this->noServing = $noServing;
        $this->noIngredient = $noIngredient;
        $this->recipeType = $recipeType;
        $this->cuisine = $cuisine;
        $this->description = $description;
        $this->preparation = $preparation;
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getAddTime(): ?\DateTimeInterface
    {
        return $this->addTime;
    }

    public function setAddTime(\DateTimeInterface $addTime): static
    {
        $this->addTime = $addTime;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getPrepTime(): ?int
    {
        return $this->prepTime;
    }

    public function setPrepTime(int $prepTime): static
    {
        $this->prepTime = $prepTime;
        return $this;
    }

    public function getNoServing(): ?int
    {
        return $this->noServing;
    }

    public function setNoServing(int $noServing): static
    {
        $this->noServing = $noServing;
        return $this;
    }

    public function getNoIngredient(): ?int
    {
        return $this->noIngredient;
    }

    public function setNoIngredient(int $noIngredient): static
    {
        $this->noIngredient = $noIngredient;
        return $this;
    }

    public function getRecipeType(): ?RecipeType
    {
        return $this->recipeType;
    }

    public function setRecipeType(RecipeType $recipeType): static
    {
        $this->recipeType = $recipeType;
        return $this;
    }

    public function getCuisine(): ?Cuisine
    {
        return $this->cuisine;
    }

    public function setCuisine(Cuisine $cuisine): static
    {
        $this->cuisine = $cuisine;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPreparation(): ?string
    {
        return $this->preparation;
    }

    public function setPreparation(string $preparation): static
    {
        $this->preparation = $preparation;
        return $this;
    }

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(RecipeIngredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(RecipeIngredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }
}
