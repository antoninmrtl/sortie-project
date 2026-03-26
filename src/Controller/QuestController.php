<?php

namespace App\Controller;

use App\Entity\Quest;
use App\Form\QuestType;
use App\Repository\QuestRepository;
use App\Repository\StatusRepository;
use App\Utils\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/quest', name: 'quest_')]
final class  QuestController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(QuestRepository $questRepository): Response
    {
        return $this->render('quest/index.html.twig', [
            'quests' => $questRepository->findAll()
        ]);
    }

//GET USERS AU PLURIEL PARCE QUE USER (ORGANISATEUR N'EXISTE PAS ENCORE
    #[Route('/create', name:'create')]
    #[Route('/edit/{id}', name:'edit', requirements: ['id'=>'\d+'])]
    public function createOrEdit(
        Request $request,
        EntityManagerInterface $entityManager,
        StatusRepository $statusRepository,
        QuestRepository $questRepository,
        FileUploader $fileUploader,
        int $id = null): Response
    {
        $quest = new Quest();
        if($id !=null){
            $quest = $questRepository->find($id);
            if($quest->getPromoter() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')){
                throw $this->createAccessDeniedException("Vas saboter la quête d'autrui, malautru!");
            }
        }
        $status = $statusRepository->findAll();
        $form = $this->createForm(QuestType::class, $quest);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('picture')->getData();
            if ($file) {
                $quest->setPicture(
                    $fileUploader->upload($file, 'assets/images/pictureQuest', $quest->getName())
                );
            }
            //$quest->setUsers($this->getUser()); Ya un probleme avec ça
            $quest->setStatus($status[1]);
            $quest->setPromoter($user);

            $entityManager->persist($quest);
            $entityManager->flush();

            return $this->redirectToRoute('quest_show', ['id'=>$quest->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quest/new.html.twig', [
            'quest' => $quest,
            'form' => $form,
        ]);
    }
//    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager, StatusRepository $repository): Response
//    {
//        $quest = new Quest();
//        $status = $repository->findAll();
//        $form = $this->createForm(QuestType::class, $quest);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $quest->setStatus($status[1]);
//
//            $entityManager->persist($quest);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('quest_show', ['id'=>$quest->getId()], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('quest/new.html.twig', [
//            'quest' => $quest,
//            'form' => $form,
//        ]);
//    }
//
//
//    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Quest $quest, EntityManagerInterface $entityManager): Response
//    {
//        $form = $this->createForm(QuestType::class, $quest);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            return $this->redirectToRoute('quest_show', ['id'=>$quest->getId()], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('quest/edit.html.twig', [
//            'quest' => $quest,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Quest $quest): Response
    {
        return $this->render('quest/show.html.twig', [
            'quest' => $quest,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements:['id'=>'\d+'])]
    public function delete(
        Request $request,
        Quest $quest,
        EntityManagerInterface $entityManager,
        QuestRepository $questRepository,
        int $id): Response
    {
        $quest = $questRepository->find($id);

        // if ($this->isCsrfTokenValid('delete'.$quest->getId(), $request->getPayload()->getString('_token'))) {

//            dd($quest->getPromoter() !== $this->getUser());
            if($quest->getPromoter() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')){
//             dd('edfghjklm');
                throw $this->createAccessDeniedException("Ne destroies point la sortie qui n'est nulle la tienne!");
            }

            $entityManager->remove($quest);
            $entityManager->flush();
//        }
        return $this->redirectToRoute('quest_index', ['id'=>$quest->getId()], Response::HTTP_SEE_OTHER);
    }
}
