<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Quest;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr-FR');

        foreach ([' Créee', 'Ouverte', 'Cloturée', 'En cours', 'Passée', ' Annulée'] as $value) {
            $status = new Status();
            $status->setLabel($value);
            $manager->persist($status);
        }
        $manager->flush();

        foreach ([' Rennes', 'Niort', 'Quimper', 'Nantes'] as $value) {
            $campus = new Campus();
            $campus->setName($value);
            $manager->persist($campus);
        }
        $manager->flush();

        $status = $manager->getRepository(Status::class)->findAll();
        $campus = $manager->getRepository(Campus::class)->findAll();


        for ($i = 0; $i < 25; $i++) {
            $city = new City();
            $city->setName($faker->text(25))
                ->setPostalCode($faker->numberBetween(1000, 9999));
            $manager->persist($city);
        }

        $city = $manager->getRepository(City::class)->findAll();

        for ($i = 0; $i < 15; $i++) {
            $place = new Place();
            $place->setName($faker->text(25))
                ->setCity($faker->randomElement($city))
                ->setLatitude($faker->randomFloat([2], [0], [10000]))
                ->setLongitude($faker->randomFloat([2], [0], [10000]))
                ->setStreet($faker->text(100));
            $manager->persist($place);
        }

        $place = $manager->getRepository(Place::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $quest = new Quest();
            $quest->setName($faker->text(25))
                ->setDuration($faker->randomFloat([2], [0], [12]))
                ->setInfoQuest($faker->text(200))
                ->setStartDateTime($faker->dateTimeBetween('-5 years'));
            $quest->setInscriptionLimitDate($faker->dateTimeBetween($quest->getStartDateTime()))
                ->setStatus($faker->randomElement($status))
                ->setNbMaxInscription($faker->numberBetween(5, 60))
                ->setCampus($faker->randomElement($campus))
                ->setPlace($faker->randomElement($place));

            $manager->persist($quest);

        }

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setFirstname($faker->text(25))
                ->setLastname($faker->text(25))
                ->setCampus($faker->randomElement($campus))
                ->setRoles($faker->randomElements(['ROLE_USER', 'ROLE_ADMIN']))
                ->setActive($faker->boolean(75))
                ->setEmail($faker->text(35))
                ->setPassword($faker->randomElement($campus))
                ->setProfilePicture($faker->text(35));

            $manager->persist($user);

        }

        $manager->flush();
    }
}
