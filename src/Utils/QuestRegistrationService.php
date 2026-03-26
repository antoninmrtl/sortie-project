<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Repository\QuestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuestRegistrationService
{

    public function __construct(private UserRepository $userRepository, private QuestRepository $questRepository)
    {
    }

    public function inscriptionVerif(Quest $quest, EntityManagerInterface $entityManager){

        if ($quest->getNbMaxInscription() < count($quest->getUsers()) || $quest->getUsers()->contains($user) || $quest->getInscriptionLimitDate() < new \DateTime()) {
            $this->addFlash('warning', 'Vous Ne pouvez pas vous inscrire à cette quête aventurier');
        } else {
            $quest = $quest->addUser($user);
            $entityManager->persist($quest);
            $entityManager->flush();
            $this->addFlash('success', 'Bienvenue a l\'aventure');
            return $this->redirectToRoute('quest_show', ['id' => $quest->getId()]);
        }
    }



}
