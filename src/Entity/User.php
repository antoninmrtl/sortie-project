<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\Unique]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    private ?string $phone = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    private ?bool $active = null;

    #[ORM\Column(length: 50)]
    private ?string $profilePicture = null;

    #[ORM\ManyToOne(inversedBy: 'userss')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    private ?Campus $campus = null;

    /**
     * @var Collection<int, Quest>
     */
    #[ORM\ManyToMany(targetEntity: Quest::class, inversedBy: 'users')]
    private Collection $quests;

    #[ORM\Column(length: 50)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $username = null;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }


    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Quest>
     */
    public function getQuests(): Collection
    {
        return $this->quests;
    }

    public function addQuest(Quest $quest): static
    {
        if (!$this->quests->contains($quest)) {
            $this->quests->add($quest);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): static
    {
        $this->quests->removeElement($quest);

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
