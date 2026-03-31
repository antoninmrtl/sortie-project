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


//    public function load(ObjectManager $manager): void
//    {
//
//        $faker = Factory::create('fr-FR');
//
//        for ($i = 0; $i < 50; $i++) {
//            $quest = new Quest();
//            $quest->setName($faker->text(25))
//                ->setDuration($faker->randomFloat(2, 1, 24))
//                ->setInfoQuest($faker->text(200))
//                ->setStartDateTime($faker->dateTimeBetween('-5 month'));
//            $quest->setInscriptionLimitDate($faker->dateTimeBetween($quest->getStartDateTime(), '+1 years'))
//                ->setStatus($this->getReference(StatusFixtures::STATUS_REFERENCE, Status::class))
//                ->setNbMaxInscription($faker->numberBetween(5, 60))
//                ->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE, Campus::class))
//                ->setPlace($this->getReference(PlaceFixtures::PLACE_REFERENCE, Place::class))
//                ->setPromoter($this->getReference(UserFixtures::USER_REFERENCE, User::class));
//
//            $manager->persist($quest);
//
//        }
//
//
//        $manager->flush();
//
//        $this->addReference(self::QUEST_REFERENCE, $quest);
//    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr-FR');

        $allStatuses = $manager->getRepository(Status::class)->findAll();
        $statusMap = [];
        foreach ($allStatuses as $s) { $statusMap[$s->getLabel()] = $s; }

        for ($i = 0; $i < 60; $i++) {
            $quest = new Quest();

            if ($i < 10) { // CAS ARCHIVE (Il y a plus de 30 jours)
                $start = $faker->dateTimeBetween('-60 days', '-31 days');
            } elseif ($i < 20) { ///CAS PASSÉE (Finie mais moins de 30 jours)
                $start = $faker->dateTimeBetween('-30 days', '-5 days');
            } elseif ($i < 25) { // CAS EN COURS (Commencée il y a 1h)
                $start = new \DateTime('-1 hour');
            } elseif ($i < 40) { // CAS OUVERTE (Dans le futur)
                $start = $faker->dateTimeBetween('+5 days', '+30 days');
            } else { // CAS CLÔTURÉE (Demain, mais date limite d'inscription dépassée)
                $start = new \DateTime('+2 days');
            }

            $quest->setName($faker->realText(25))
                ->setDuration($faker->randomFloat(2, 1, 5)) // Durée courte pour faciliter les tests
                ->setInfoQuest($faker->realText(200))
                ->setStartDateTime($start);


            if ($i >= 40) {
                $quest->setInscriptionLimitDate(new \DateTime('-1 day'));
            } else {
                $startDate = clone $quest->getStartDateTime();
                $daysBefore = $faker->numberBetween(1, 10);
                $quest->setInscriptionLimitDate($startDate->modify("-" . $daysBefore . " days"));
            }


            if ($i === 0) {
                $quest->setStatus($statusMap['Annulée']);
            } elseif ($i === 1) {
                $quest->setStatus($statusMap['En création']);
            } else {
                $quest->setStatus($statusMap['Ouverte']);
            }

            $quest->setNbMaxInscription($faker->numberBetween(5, 60))
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
