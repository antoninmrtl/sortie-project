<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ExperienceService
{
    private const XP_MULTIPLIER = 10;
    private const LEVEL_FACTOR = 500;

    public function __construct(private EntityManagerInterface $entityManager) {}

    public function awardExperienceForQuest(Quest $quest): void
    {
        $duration = $quest->getDuration();
        $xpToAward = $duration * self::XP_MULTIPLIER;

        foreach ($quest->getUsers() as $user) {
            $currentXp = $user->getExperience();
            $user->setExperience($currentXp + $xpToAward);
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }

    public function calculateLevel(int $xp): int
    {
        if ($xp <= 0) return 1;
        return (int) floor(sqrt($xp / self::LEVEL_FACTOR)) + 1;
    }


    public function calculatePercentage(int $xp): int
    {
        $currentLevel = $this->calculateLevel($xp);

        $xpStartCurrent = (($currentLevel - 1) ** 2) * self::LEVEL_FACTOR;
        $xpNextLevel = ($currentLevel ** 2) * self::LEVEL_FACTOR;

        $totalNeeded = $xpNextLevel - $xpStartCurrent;
        $currentProgress = $xp - $xpStartCurrent;

        return (int) (($currentProgress / $totalNeeded) * 100);
    }
}
