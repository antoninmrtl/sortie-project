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

class CityFixtures extends Fixture implements DependentFixtureInterface
{

    public const CITY_REFERENCE = 'city';


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr-FR');

        for ($i = 0; $i < 25; $i++) {
            $city = new City();
            $city->setName($faker->text(25))
                ->setPostalCode($faker->randomNumber([0],[99999]));
            $manager->persist($city);
        }

        $manager->flush();

        $this->addReference(self::CITY_REFERENCE, $city);
    }

    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
            CampusFixtures::class
        ];
    }
}
