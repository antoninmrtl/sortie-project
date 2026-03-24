<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Quest>
     */
    #[ORM\OneToMany(targetEntity: Quest::class, mappedBy: 'campus')]
    private Collection $quests;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'campus')]
    private Collection $userss;



    public function __construct()
    {
        $this->quests = new ArrayCollection();
        $this->userss = new ArrayCollection();

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
            $quest->setCampus($this);
        }

        return $this;
    }

    public function removeQuest(Quest $quest): static
    {
        if ($this->quests->removeElement($quest)) {
            // set the owning side to null (unless already changed)
            if ($quest->getCampus() === $this) {
                $quest->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserss(): Collection
    {
        return $this->userss;
    }

    public function addUserss(User $userss): static
    {
        if (!$this->userss->contains($userss)) {
            $this->userss->add($userss);
            $userss->setCampus($this);
        }

        return $this;
    }

    public function removeUserss(User $userss): static
    {
        if ($this->userss->removeElement($userss)) {
            // set the owning side to null (unless already changed)
            if ($userss->getCampus() === $this) {
                $userss->setCampus(null);
            }
        }

        return $this;
    }



}
