<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $surname = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: UserType::class)]
    #[ORM\JoinColumn(name: "id_user_type", referencedColumnName: "id")]
    #[Groups(['user:read', 'user:write'])]
    private ?UserType $userType = null;

    private array $roles = [];

    public function __construct(
        string $name,
        string $surname,
        string $email,
        string $password,
        UserType $userType
    ) {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->userType = $userType;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getUserType(): ?UserType
    {
        return $this->userType;
    }

    public function setUserType(UserType $userType): static
    {
        $this->userType = $userType;
        return $this;
    }

    public function getUserTypeName(): ?string
    {
        return $this->userType?->getName();
    }

    public function getRoles(): array
    {

        $roles = ['ROLE_USER'];

        if ($this->userType && $this->userType->getId() === 1) {
            $roles[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }


    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}