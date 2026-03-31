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

class PlaceFixtures extends Fixture implements DependentFixtureInterface
{

    public const PLACE_REFERENCE = 'place';


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr-FR');

        $cities = [
            $this->getReference('city_0', City::class),
            $this->getReference('city_1', City::class),
            $this->getReference('city_2', City::class),
        ];



        for ($i = 0; $i < 5; $i++) {
            $place = new Place();
            $place->setName($faker->domainWord())
                ->setCity(($faker->randomElement($cities)))
                ->setLatitude($faker->latitude($min = -90, $max = 90))
                ->setLongitude($faker->longitude($min = -180, $max = 180))
                ->setStreet($faker->streetAddress());
            $manager->persist($place);
        }
        $this->addReference('place_' . $i, $place);
        $manager->flush();

        $this->addReference(self::PLACE_REFERENCE, $place);
    }

    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
            CampusFixtures::class,
            CityFixtures::class
        ];
    }
}
