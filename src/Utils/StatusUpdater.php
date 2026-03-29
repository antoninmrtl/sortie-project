<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Repository\QuestRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class StatusUpdater
{

    public function __construct(private ExperienceService $experienceService ,private StatusRepository $statusRepository, private QuestRepository $questRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function updateStatus(){

        $quests = $this->questRepository->findAllClean();

        $closedStatus = $this->statusRepository->findOneBy(['label' => 'Clôturée']);
        $openStatus = $this->statusRepository->findOneBy(['label' => 'Ouverte']);
        $passedStatus = $this->statusRepository->findOneBy(['label' => 'Passée']);
        $annuledStatus = $this->statusRepository->findOneBy(['label' => 'Annulée']);
        $archiveStatus = $this->statusRepository->findOneBy(['label' => 'Archive']);
        $enCoursStatus = $this->statusRepository->findOneBy(['label' => 'En cours']);
        $enCreationStatus = $this->statusRepository->findOneBy(['label' => 'En création']);



        foreach ($quests as $quest){

            $endDateTime = clone $quest->getStartDateTime();
            $minutesToAdd = (int)($quest->getDuration() * 60);
            $endDateTime->modify("+$minutesToAdd minutes");


            if ($quest->getStartDateTime()  < new \DateTime('-30 days')){
                $quest->setStatus($archiveStatus);
            }elseif ($quest->getStatus() === $annuledStatus){
                $quest->setStatus($annuledStatus);
                continue;
            }elseif ($quest->getStatus() === $enCreationStatus) {
                $quest->setStatus($enCreationStatus);
                continue;
            } elseif ($endDateTime <  new \DateTime()){
                $quest->setStatus($passedStatus);
                $this->experienceService->awardExperienceForQuest($quest);
            }elseif ($quest->getStartDateTime() < new \DateTime() && $endDateTime > new \DateTime()) {
                $quest->setStatus($enCoursStatus);
            } elseif (count($quest->getUsers()) >= $quest->getNbMaxInscription() || $quest->getInscriptionLimitDate() < new \DateTime()){
                $quest->setStatus($closedStatus);
            } else{
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
