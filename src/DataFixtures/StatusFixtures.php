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

class StatusFixtures extends Fixture implements DependentFixtureInterface
{

    public const STATUS_REFERENCE = 'status';


    public function load(ObjectManager $manager): void
    {

        foreach (['Créee', 'Ouverte', 'Cloturée', 'En cours', 'Passée', 'Annulée', 'Archive', 'En création'] as $value) {
            $status = new Status();
            $status->setLabel($value);
            $manager->persist($status);
        }
        $manager->flush();

        $this->addReference(self::STATUS_REFERENCE, $status);
    }
}
