<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Repository\QuestRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatusUpdater
{

    public function __construct(private ExperienceService $experienceService ,private StatusRepository $statusRepository, private QuestRepository $questRepository, private EntityManagerInterface $entityManager)    {
    }

    public function updateStatus(){

        $quests = $this->questRepository->findAllClean();

        $AllStatus = $this->statusRepository->findAll();
        $statusMap = [];

        foreach ($AllStatus as $status) {
            $statusMap[$status->getLabel()] = $status;
        }

        foreach ($quests as $quest){

            $endDateTime = clone $quest->getStartDateTime();
            $minutesToAdd = (int)($quest->getDuration() * 60);
            $endDateTime->modify("+$minutesToAdd minutes");

            if ($quest->getStartDateTime()  < new \DateTime('-30 days')){
                $quest->setStatus($statusMap['Archive']);
            }elseif ($quest->getStatus() === $statusMap['Annulée']){
                $quest->setStatus($statusMap['Annulée']);
                continue;
            }elseif ($quest->getStatus() === $statusMap['En création']) {
                $quest->setStatus($statusMap['En création']);
                continue;
            } elseif ($endDateTime <  new \DateTime()){
                $quest->setStatus($statusMap['Passée']);
                $this->experienceService->awardExperienceForQuest($quest);
            }elseif ($quest->getStartDateTime() < new \DateTime() && $endDateTime > new \DateTime()) {
                $quest->setStatus($statusMap['En cours']);
            } elseif (count($quest->getUsers()) >= $quest->getNbMaxInscription() || $quest->getInscriptionLimitDate() < new \DateTime()){
                $quest->setStatus($statusMap['Cloturée']);
            } else{
                $quest->setStatus($statusMap['Ouverte']);
            }
            $this->entityManager->persist($quest);
        }
        $this->entityManager->flush();
    }

    public function createStatus(Quest $quest ){

        $closedStatus = $this->statusRepository->findOneBy(['label' => 'Clôturée']);
        $openStatus = $this->statusRepository->findOneBy(['label' => 'Ouverte']);
        $passedStatus = $this->statusRepository->findOneBy(['label' => 'Passée']);
        $enCreationStatus = $this->statusRepository->findOneBy(['label' => 'En création']);

        $now = new \DateTime();

        if ($quest->getStatus() === $enCreationStatus) {
            return $quest;
        }

        if ($quest->getInscriptionLimitDate() < $now) {
            $quest->setStatus($closedStatus);
        } elseif (count($quest->getUsers()) >= $quest->getNbMaxInscription()) {
            $quest->setStatus($closedStatus);
        } elseif ($quest->getStartDateTime() < $now) {
            $quest->setStatus($passedStatus);
        } else {
            $quest->setStatus($openStatus);
        }

        return $quest;

    }

}
