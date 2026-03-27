<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Entity\User;
use App\Repository\QuestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuestRegistrationService
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function inscriptionVerif(Quest $quest, User $user){

        if ($quest->getNbMaxInscription() < count($quest->getUsers()) || $quest->getUsers()->contains($user) || $quest->getInscriptionLimitDate() < new \DateTime()) {
            return false;
        } else {
            $quest = $quest->addUser($user);
            $this->entityManager->persist($quest);
            $this->entityManager->flush();
            return true;
        }
    }


    public function desisterVerif(Quest $quest, User $user){

        if ($quest->getUsers()->contains($user) && $quest->getStartDateTime() > new \DateTime()) {
            $quest = $quest->removeUser($user);
            $this->entityManager->persist($quest);
            $this->entityManager->flush();
            return true;
        } else {
            return false;
        }
    }


    public function aboveVerif(Quest $quest, User $user){


        if ($quest->getUsers()->contains($user) && $quest->getStartDateTime() < (new \DateTime())->modify('+1 days')) {

            $quest = $quest->removeUser($user);
            $this->entityManager->persist($quest);
            $this->entityManager->flush();
            return true;
        } else {
            return false;
        }
    }





}
