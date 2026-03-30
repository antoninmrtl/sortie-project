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
//    private UserPasswordHasherInterface $passwordHasher;
//
//    public function __construct(UserPasswordHasherInterface $passwordHasher)
//    {
//        $this->passwordHasher = $passwordHasher;
//    }


    public function load(ObjectManager $manager): void
    {
//
//        $faker = Factory::create('fr-FR');
//        $profilePictureTest = 'profilePTest.jpg';
//
//        foreach (['Créee', 'Ouverte', 'Cloturée', 'En cours', 'Passée', 'Annulée', 'Archive', 'En création'] as $value) {
//            $status = new Status();
//            $status->setLabel($value);
//            $manager->persist($status);
//        }
//        $manager->flush();
//
//        foreach ([' Rennes', 'Niort', 'Quimper', 'Nantes'] as $value) {
//            $campus = new Campus();
//            $campus->setName($value);
//            $manager->persist($campus);
//        }
//        $manager->flush();
//
//        $status = $manager->getRepository(Status::class)->findAll();
//        $campus = $manager->getRepository(Campus::class)->findAll();
//
//
//        for ($i = 0; $i < 25; $i++) {
//            $city = new City();
//            $city->setName($faker->text(25))
//                ->setPostalCode($faker->randomNumber([0],[99999]));
//            $manager->persist($city);
//        }
//
//        $manager->flush();
//
//        $city = $manager->getRepository(City::class)->findAll();
//
//        for ($i = 0; $i < 5; $i++) {
//            $place = new Place();
//            $place->setName($faker->domainWord())
//                ->setCity($faker->randomElement($city))
//                ->setLatitude($faker->latitude($min = -90, $max = 90))
//                ->setLongitude($faker->longitude($min = -180, $max = 180))
//                ->setStreet($faker->streetAddress());
//            $manager->persist($place);
//        }
//
//
//        $manager->flush();
//
//        $place = $manager->getRepository(Place::class)->findAll();
//
//
//        for ($i = 0; $i < 50; $i++) {
//            $user = new User();
//            $hashedPassword = $this->passwordHasher->hashPassword(
//                $user,
//                'azerty'
//            );
//            $user->setFirstname($faker->firstName('male'|'female'))
//                ->setUsername($faker->userName())
//                ->setLastname($faker->lastName())
//                ->setCampus($faker->randomElement($campus))
//                ->setRoles($faker->randomElements(['ROLE_USER', 'ROLE_ADMIN']))
//                ->setActive($faker->boolean(75))
//                ->setEmail($faker->email())
//                ->setPassword($hashedPassword)
//                ->setProfilePicture($profilePictureTest)
//                ->setPhone($faker->e164PhoneNumber());
//
//            $manager->persist($user);
//
//        }
//
//        $admins = [
//            ['vanina', 'van@gmail.com'],
//            ['antonin', 'anto@gmail.com'],
//            ['sylvain', 'syl@gmail.com'],
//            ['silvia', 'sia@gmail.com'],
//        ];
//
//        foreach ($admins as $adminData) {
//            $admin = new User();
//            $admin->setUsername($adminData[0])
//                ->setFirstname($adminData[0])
//                ->setLastname($adminData[0])
//                ->setEmail($adminData[1])
//                ->setPhone('0601020304')
//                ->setRoles(['ROLE_ADMIN'])
//                ->setActive(true)
//                ->setCampus($campus[0])
//                ->setProfilePicture($profilePictureTest);
//            $admin->setPassword(
//                $this->passwordHasher->hashPassword($admin, 'azerty')
//            );
//
//            $manager->persist($admin);
//        }
//
//        $manager->flush();
//
//        $user = $manager->getRepository(User::class)->findAll();
//
//        for ($i = 0; $i < 50; $i++) {
//            $quest = new Quest();
//            $quest->setName($faker->text(25))
//                ->setDuration($faker->randomFloat([2], [1], [24])) //bug sur la duration en bdd
//                ->setInfoQuest($faker->text(200))
//                ->setStartDateTime($faker->dateTimeBetween('-5 month'));
//            $quest->setInscriptionLimitDate($faker->dateTimeBetween($quest->getStartDateTime(), '+1 years'))
//                ->setStatus($faker->randomElement($status))
//                ->setNbMaxInscription($faker->numberBetween(5, 60))
//                ->setCampus($faker->randomElement($campus))
//                ->setPlace($faker->randomElement($place))
//                ->setPromoter($faker->randomElement($user));
//
//            $manager->persist($quest);

        }


//        $manager->flush();

    }
}
