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

class QuestFixtures extends Fixture implements DependentFixtureInterface
{

    public const QUEST_REFERENCE = 'quest';


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr-FR');

        for ($i = 0; $i < 50; $i++) {
            $quest = new Quest();
            $quest->setName($faker->text(25))
                ->setDuration($faker->randomFloat([2], [1], [24])) //bug sur la duration en bdd
                ->setInfoQuest($faker->text(200))
                ->setStartDateTime($faker->dateTimeBetween('-5 month'));
            $quest->setInscriptionLimitDate($faker->dateTimeBetween($quest->getStartDateTime(), '+1 years'))
                ->setStatus($this->getReference(StatusFixtures::STATUS_REFERENCE, Status::class))
                ->setNbMaxInscription($faker->numberBetween(5, 60))
                ->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE, Campus::class))
                ->setPlace($this->getReference(PlaceFixtures::PLACE_REFERENCE, Place::class))
                ->setPromoter($this->getReference(UserFixtures::USER_REFERENCE, User::class));

            $manager->persist($quest);

        }


        $manager->flush();

        $this->addReference(self::QUEST_REFERENCE, $quest);
    }

    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
            CampusFixtures::class,
            CityFixtures::class,
            PlaceFixtures::class,
            UserFixtures::class
        ];
    }
}
