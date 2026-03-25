<?php

namespace App\Form\Model;

class QuestSearch
{
    private ?string $name = null;
    private bool $isPromoter = false;
    private bool $isRegistered = false;

    public function getName(): ?string
    {
        return $this->name;
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
