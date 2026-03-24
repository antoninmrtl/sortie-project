<?php

namespace App\DataFixtures;

use App\Entity\Quest;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr-FR');

        foreach ([' Créee', 'Ouverte', 'Cloturée', 'En cours','Passée', ' Annulée'] as $value) {
            $status = new Status();
            $status->setLabel($value);
            $manager->persist($status);
        }
        $manager->flush();
        $status = $manager->getRepository(Status::class)->findAll();

        for ($i = 0; $i < 50; $i++) {
            $quest = new Quest();
            $quest->setName($faker->text(25))
                ->setDuration($faker->randomFloat([2], [0], [12]))
                ->setInfoQuest($faker->text(200))
                ->setStartDateTime($faker->dateTimeBetween('-5 years'));
            $quest->setInscriptionLimitDate($faker->dateTimeBetween($quest->getStartDateTime()))
                ->setStatus($faker->randomElement($status))
            ->setNbMaxInscription($faker->numberBetween([5], [60]));

        }

        $manager->flush();
    }
}
