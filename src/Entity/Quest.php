<?php

namespace App\Entity;

use App\Repository\QuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?\DateTime $startDateTime = null;

    #[ORM\Column]
    private ?float $duration = null;

    #[ORM\Column]
    private ?\DateTime $inscriptionLimitDate = null;

    #[ORM\Column]
    private ?int $nbMaxInscription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $infoQuest = null;

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
}
