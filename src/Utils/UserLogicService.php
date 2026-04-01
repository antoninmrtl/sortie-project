<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Entity\User;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserLogicService
{


    public function __construct(private EntityManagerInterface $entityManager, private StatusRepository $statusRepository, private FileUploader $fileUploader, private StatusUpdater $statusUpdater, private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function create($form, User $user)
    {
        /**
         * @var UploadedFile $file
         */
        $file = $form->get('profilePicture')->getData();
        if ($file) {
            $user->setProfilePicture(
                $this->fileUploader->upload($file, 'assets/images/profilePicture', $user->getUsername())
            );
        } else {
            $user->setProfilePicture('profile-icon-9.png');
        }

        /** @var string $plainPassword */
        $plainPassword = $form->get('Password')->getData();
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));
        $user->setRoles(['ROLE_USER']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function edit($form, User $user)
    {
        $oldPassword = $user->getPassword();
        $plainPassword = $form->get('Password')->getData();

        if ($plainPassword) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));
        } else {
            $user->setPassword($oldPassword);
        }
        $file = $form->get('profilePicture')->getData();
        if ($file) {
            $user->setProfilePicture(
                $this->fileUploader->upload($file, 'assets/images/profilePicture', $user->getUsername())
            );
        }
        $this->entityManager->flush();
    }

}
