<?php

namespace App\Form\Model;

class QuestSearch
{
    private ?string $name = null;
    private bool $isPromoter = false;
    private bool $isRegistered = false;

    public ?\DateTime $startDate = null;
    public ?\DateTime $endDate = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }


    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function isPromoter(): bool
    {
        return $this->isPromoter;
    }

    public function setIsPromoter(bool $isPromoter): void
    {
        $this->isPromoter = $isPromoter;
    }

    public function isRegistered(): bool
    {
        return $this->isRegistered;
    }

    public function setIsRegistered(bool $isRegistered): void
    {
        $this->isRegistered = $isRegistered;
    }


}
