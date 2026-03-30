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

class CampusFixtures extends Fixture implements DependentFixtureInterface
{

    public const CAMPUS_REFERENCE = 'campus';


    public function load(ObjectManager $manager): void
    {

        foreach ([' Rennes', 'Niort', 'Quimper', 'Nantes'] as $value) {
            $campus = new Campus();
            $campus->setName($value);
            $manager->persist($campus);
        }
        $manager->flush();

        $this->addReference(self::CAMPUS_REFERENCE, $campus);
    }

    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
        ];
    }
}
