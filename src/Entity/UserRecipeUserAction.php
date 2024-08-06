<?php

namespace App\Entity;

use App\Repository\UserRecipeUserActionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRecipeUserActionRepository::class)]
#[ORM\Table(name: 'user_recipe_user_action')]
#[ORM\UniqueConstraint(
    name: 'id_user_id_recipe_id_user_action',
    columns: ['id_user', 'id_recipe', 'id_user_action']
)]
class UserRecipeUserAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id")]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Recipe::class)]
    #[ORM\JoinColumn(name: "id_recipe", referencedColumnName: "id")]
    private ?Recipe $recipe = null;

    #[ORM\ManyToOne(targetEntity: UserAction::class)]
    #[ORM\JoinColumn(name: "id_user_action", referencedColumnName: "id")]
    private ?UserAction $userAction = null;

    public function __construct(?User $user, ?Recipe $recipe, ?UserAction $userAction)
    {
        $this->user = $user;
        $this->recipe = $recipe;
        $this->userAction = $userAction;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): static
    {
        $this->recipe = $recipe;
        return $this;
    }

    public function getUserAction(): ?UserAction
    {
        return $this->userAction;
    }

    public function setUserAction(UserAction $userAction): static
    {
        $this->userAction = $userAction;
        return $this;
    }
}
