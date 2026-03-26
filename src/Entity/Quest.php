<?php

namespace App\Entity;

use App\Repository\QuestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuestRepository::class)]
class Quest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual('today', message: "La quête ne peut pas débuter dans le passé !")]
    private ?\DateTime $startDateTime = null;

    #[ORM\Column]
    private ?float $duration = null;

    #[ORM\Column]
    #[Assert\LessThanOrEqual(
        propertyPath: "startDateTime",
        message: "La date limite d'inscription doit être avant le début de la quête"
    )]
    private ?\DateTime $inscriptionLimitDate = null;

    #[ORM\Column]
    private ?int $nbMaxInscription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $infoQuest = null;

    #[ORM\ManyToOne(inversedBy: 'quests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'quests')]
    private ?Campus $campus = null;

    /**
     * @var Collection<int, User>
     */

    #[ORM\ManyToOne(inversedBy: 'quests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'quests')]
    private Collection $users;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $picture = null;

    #[ORM\ManyToOne(inversedBy: 'quest')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $promoter = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getStartDateTime(): ?\DateTime
    {
        return $this->startDateTime;
    }


    public function setStartDateTime(\DateTime $startDateTime): static
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getInscriptionLimitDate(): ?\DateTime
    {
        return $this->inscriptionLimitDate;
    }

    public function setInscriptionLimitDate(\DateTime $inscriptionLimitDate): static
    {
        $this->inscriptionLimitDate = $inscriptionLimitDate;

        return $this;
    }

    public function getNbMaxInscription(): ?int
    {
        return $this->nbMaxInscription;
    }

    public function setNbMaxInscription(int $nbMaxInscription): static
    {
        $this->nbMaxInscription = $nbMaxInscription;

        return $this;
    }

    public function getInfoQuest(): ?string
    {
        return $this->infoQuest;
    }

    public function setInfoQuest(string $infoQuest): static
    {
        $this->infoQuest = $infoQuest;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

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


    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addQuest($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeQuest($this);
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPromoter(): ?User
    {
        return $this->promoter;
    }

    public function setPromoter(?User $promoter): static
    {
        $this->promoter = $promoter;

        return $this;
    }
}
