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

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    public const USER_REFERENCE = 'user';
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr-FR');

        $profilePictureTest = 'profile-icon-9.png';

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'azerty'
            );
            $user->setFirstname($faker->firstName('male'|'female'))
                ->setUsername($faker->userName())
                ->setLastname($faker->lastName())
                ->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE, Campus::class))
                ->setRoles($faker->randomElements(['ROLE_USER', 'ROLE_ADMIN']))
                ->setActive($faker->boolean(75))
                ->setEmail($faker->email())
                ->setPassword($hashedPassword)
                ->setProfilePicture($profilePictureTest)
                ->setPhone($faker->e164PhoneNumber());

            $manager->persist($user);

        }

        $admins = [
            ['vanina', 'van@gmail.com'],
            ['antonin', 'anto@gmail.com'],
            ['sylvain', 'syl@gmail.com'],
            ['silvia', 'sia@gmail.com'],
        ];

        foreach ($admins as $adminData) {
            $admin = new User();
            $admin->setUsername($adminData[0])
                ->setFirstname($adminData[0])
                ->setLastname($adminData[0])
                ->setEmail($adminData[1])
                ->setPhone('0601020304')
                ->setRoles(['ROLE_ADMIN'])
                ->setActive(true)
                ->setCampus($this->getReference(CampusFixtures::CAMPUS_REFERENCE, Campus::class))
                ->setProfilePicture($profilePictureTest);
            $admin->setPassword(
                $this->passwordHasher->hashPassword($admin, 'azerty')
            );

            $manager->persist($admin);
        }

        $manager->flush();
        $this->addReference(self::USER_REFERENCE, $user);
    }

    public function getDependencies(): array
    {
        return [
            StatusFixtures::class,
            CampusFixtures::class,
            CityFixtures::class,
            PlaceFixtures::class,
        ];
    }
}
