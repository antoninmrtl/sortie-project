<?php

namespace App\Utils;

use App\Entity\Quest;
use App\Entity\User;
use App\Form\QuestType;
use App\Repository\QuestRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Provider\cs_CZ\DateTime;
use Symfony\Component\Form\Form;

class QuestLogicService
{

    public function __construct(private EntityManagerInterface $entityManager, private StatusRepository $statusRepository, private FileUploader $fileUploader, private StatusUpdater $statusUpdater)
    {
    }

    public function createAndEdit($form, Quest $quest, User $user, $request){
        $action = $request->request->get('save_action');


        $file = $form->get('picture')->getData();
        if ($file) {
            $quest->setPicture(
                $this->fileUploader->upload($file, 'assets/images/pictureQuest', $quest->getName())
            );
        }
        $quest->setPromoter($user);
        $quest->addUser($user);

        $enCreationStatus = $this->statusRepository->findOneBy(['label' => 'En création']);

        if($action === 'save') {
            $quest->setStatus($enCreationStatus);
            $quest = $this->statusUpdater->createStatus($quest);

        } else {
            $quest = $this->statusUpdater->createStatus($quest);
        }

        $this->entityManager->persist($quest);
        $this->entityManager->flush();

    }

}
