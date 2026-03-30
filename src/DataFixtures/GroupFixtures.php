<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Quest;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GroupFixtures extends Fixture implements DependentFixtureInterface
{



    public function load(ObjectManager $manager): void
    {
    }

    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
            CampusFixtures::class,
            CityFixtures::class,
            PlaceFixtures::class,
            UserFixtures::class,
            QuestFixtures::class,
        ];
    }
}
