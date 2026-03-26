<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Repository\QuestRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatusUpdater
{

    public function __construct(private StatusRepository $statusRepository, private QuestRepository $questRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function updateStatus(){

        $quests = $this->questRepository->findAllClean();

        $closedStatus = $this->statusRepository->findOneBy(['label' => 'Clôturée']);
        $openStatus = $this->statusRepository->findOneBy(['label' => 'Ouverte']);
        $passedStatus = $this->statusRepository->findOneBy(['label' => 'Passée']);

        foreach ($quests as $quest){
            if ($quest->getInscriptionLimitDate() <  new \DateTime()){
                $quest->setStatus($passedStatus);
                $this->entityManager->persist($quest);
            } elseif (count($quest->getUsers()) >= $quest->getNbMaxInscription()){
                $quest->setStatus($closedStatus);
                $this->entityManager->persist($quest);
            }else{
                $quest->setStatus($openStatus);
                $this->entityManager->persist($quest);
            }
        }
        $this->entityManager->flush();
    }

    public function createStatus(Quest $quest ){

        $closedStatus = $this->statusRepository->findOneBy(['label' => 'Clôturée']);
        $openStatus = $this->statusRepository->findOneBy(['label' => 'Ouverte']);
        $passedStatus = $this->statusRepository->findOneBy(['label' => 'Passée']);

        if (count($quest->getUsers()) < $quest->getNbMaxInscription() && $quest->getInscriptionLimitDate() > new \DateTime()){
            $quest->setStatus($openStatus);
        } elseif ($quest->getInscriptionLimitDate() < new \DateTime() || count($quest->getUsers()) >= $quest->getNbMaxInscription()){
            $quest->setStatus($closedStatus);
        } else {
            $quest->setStatus($passedStatus);
        }

        return $quest;

    }

}
