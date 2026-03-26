<?php

namespace App\Utils;

use App\Repository\QuestRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatusUpdater
{

    public function __construct(private StatusRepository $statusRepository, private QuestRepository $questRepository)
    {
    }

    public function updateStatus(QuestRepository $questRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager ){

        $quests = $questRepository->findAll();

        $closedStatus = $statusRepository->findOneBy(['label' => 'Clôturée']);
        $openStatus = $statusRepository->findOneBy(['label' => 'Ouverte']);
        $passedStatus = $statusRepository->findOneBy(['label' => 'Passée']);

        foreach ($quests as $quest){
            if ($quest->getInscriptionLimitDate() <  new \DateTime()){
                $quest->setStatus($passedStatus);
                $entityManager->persist($quest);
            } elseif (count($quest->getUsers()) >= $quest->getNbMaxInscription()){
                $quest->setStatus($closedStatus);
                $entityManager->persist($quest);
            }else{
                $quest->setStatus($openStatus);
                $entityManager->persist($quest);
            }
        }
        $entityManager->flush();
        return $quests;

    }

}
