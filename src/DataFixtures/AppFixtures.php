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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
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
                ->setPostalCode($faker->postcode());
            $manager->persist($city);
        }

        $manager->flush();

        $city = $manager->getRepository(City::class)->findAll();

        for ($i = 0; $i < 15; $i++) {
            $place = new Place();
            $place->setName($faker->domainWord())
                ->setCity($faker->randomElement($city))
                ->setLatitude($faker->latitude($min = -90, $max = 90))
                ->setLongitude($faker->longitude($min = -180, $max = 180))
                ->setStreet($faker->streetAddress());
            $manager->persist($place);
        }


        $manager->flush();

        $place = $manager->getRepository(Place::class)->findAll();


        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'azerty'
            );
            $user->setFirstname($faker->firstName($gender = null|'male'|'female'))
                ->setUsername($faker->userName())
                ->setLastname($faker->lastName())
                ->setCampus($faker->randomElement($campus))
                ->setRoles($faker->randomElements(['ROLE_USER', 'ROLE_ADMIN']))
                ->setActive($faker->boolean(75))
                ->setEmail($faker->email())
                ->setPassword($hashedPassword)
                ->setProfilePicture($faker->text(35))
                ->setPhone($faker->e164PhoneNumber());

            $manager->persist($user);

        }

        $manager->flush();

        $user = $manager->getRepository(User::class)->findAll();

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
                ->setPlace($faker->randomElement($place))
                ->setPromoter($faker->randomElement($user));

            $manager->persist($quest);

        }


        $manager->flush();
    }
}
