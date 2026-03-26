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
        $archiveStatus = $this->statusRepository->findOneBy(['label' => 'Archive']);


        foreach ($quests as $quest){
            if ($quest->getStartDateTime()  < new \DateTime('-30 days')){
                $quest->setStatus($archiveStatus);
            } elseif ($quest->getInscriptionLimitDate() <  new \DateTime()){
                $quest->setStatus($passedStatus);
            } elseif (count($quest->getUsers()) >= $quest->getNbMaxInscription() || $quest->getInscriptionLimitDate() < new \DateTime()){
                $quest->setStatus($closedStatus);
            }else{
                $quest->setStatus($openStatus);
            }
            $this->entityManager->persist($quest);
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
