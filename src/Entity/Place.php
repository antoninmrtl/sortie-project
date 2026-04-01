<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $name = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $street = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    private ?float $latitude = null;


    #[ORM\Column]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    private ?float $longitude = null;

    #[ORM\ManyToOne(inversedBy: 'places')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Ne peut pas être nul')]
    #[Assert\NotBlank(message: 'Ne peut pas être vide')]
    private ?City $city = null;

    /**
     * @var Collection<int, Quest>
     */
    #[ORM\OneToMany(targetEntity: Quest::class, mappedBy: 'place', orphanRemoval: true)]
    private Collection $quests;

    public function __construct()
    {
        $this->quests = new ArrayCollection();
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

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

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
            $quest->setPlace($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): static
    {
        if ($this->quests->removeElement($quest)) {
            // set the owning side to null (unless already changed)
            if ($quest->getPlace() === $this) {
                $quest->setPlace(null);
            }
        }

        return $this;
    }
}
