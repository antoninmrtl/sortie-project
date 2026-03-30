<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Entity\User;
use App\Repository\QuestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\cs_CZ\DateTime;

class QuestRegistrationService
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function inscriptionVerif(Quest $quest, User $user){

        if ($quest->getNbMaxInscription() <= $quest->getUsers()->count() || $quest->getUsers()->contains($user) || $quest->getInscriptionLimitDate() < new \DateTime() || $quest->getStatus()->getLabel() === 'Annulée') {
            return false;
        } else {
            $quest = $quest->addUser($user);
            $this->entityManager->persist($quest);
            $this->entityManager->flush();
            return true;
        }
    }


    public function desisterVerif(Quest $quest, User $user){

        if ($quest->getUsers()->contains($user) && $quest->getStartDateTime() > new \DateTime() && $quest->getStatus()->getLabel() != 'Annulée') {
            $quest = $quest->removeUser($user);
            $this->entityManager->persist($quest);
            $this->entityManager->flush();
            return true;
        } else {
            return false;
        }
    }


    public function aboveVerif(Quest $quest, User $user){

        if ($quest->getPromoter() === $user && $quest->getStartDateTime() > new \DateTime() || in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        } else {
            return false;
        }
    }

//    public function aboveConfirmVerif(Quest $quest, User $user){
//// inverser < (uniquement pou test)
//        if () {
//            return true;
//        } else {
//            return false;
//        }
//    }





}
