<?php

namespace App\Controller;

use App\Entity\Quest;
use App\Form\Model\QuestSearch;
use App\Form\QuestSearchType;
use App\Form\QuestType;
use App\Repository\QuestRepository;
use App\Repository\StatusRepository;
use App\Services\QuestService;
use App\Utils\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Service\Attribute\Required;

#[Route('/quest', name: 'quest_')]
final class  QuestController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(QuestRepository $questRepository, Request $request): Response
    {

        $questSearch = new QuestSearch();
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        $questForm = $this->createForm(QuestSearchType::class, $questSearch);
        $questForm->handleRequest($request);

        if ($questForm->isSubmitted() && $questForm->isValid()) {
            $searchData = $questForm->getData();
            $quests = $questRepository->findBySearch($searchData, $user);
        } else {
            $quests = $questRepository->findAll();
        }

        return $this->render('quest/index.html.twig', [
            'quests' => $quests,
            'questForm' => $questForm
        ]);
    }

//GET USERS AU PLURIEL PARCE QUE USER (ORGANISATEUR N'EXISTE PAS ENCORE
    #[Route('/create', name: 'create')]
    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'])]
    public function createOrEdit(
        Request                $request,
        EntityManagerInterface $entityManager,
        StatusRepository       $statusRepository,
        QuestRepository        $questRepository,
        FileUploader           $fileUploader,
        int                    $id = null): Response
    {
        $quest = new Quest();
        if ($id != null) {
            $quest = $questRepository->find($id);
            if ($quest->getPromoter() != $this->getUser()) {
                throw $this->createAccessDeniedException("Vas saboter la quête d'autrui, malautru!");
            }
        }
        $status = $statusRepository->findAll();
        $form = $this->createForm(QuestType::class, $quest);
        $form->handleRequest($request);
        /** @var \App\Entity\User $user */
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
            $quest->addUser($user);

            $entityManager->persist($quest);
            $entityManager->flush();

            return $this->redirectToRoute('quest_show', ['id' => $quest->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quest/new.html.twig', [
            'quest' => $quest,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'show', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function show(Quest $quest): Response
    {
        return $this->render('quest/show.html.twig', [
            'quest' => $quest,
        ]);
    }

    #[Route('/inscription/{id}', name: 'inscription', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function inscription(Quest $quest, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($quest->getNbMaxInscription() < count($quest->getUsers()) || $quest->getUsers()->contains($user) || $quest->getInscriptionLimitDate() < new \DateTime()) {
            $this->addFlash('warning', 'Vous Ne pouvez pas vous inscrire à cette quête aventurier');
        } else {
            $quest = $quest->addUser($user);
            $entityManager->persist($quest);
            $entityManager->flush();
            $this->addFlash('success', 'Bienvenue a l\'aventure');
            return $this->redirectToRoute('quest_show', ['id' => $quest->getId()]);
        }

        return $this->redirectToRoute('quest_index');
    }

    #[Route('/desister/{id}', name: 'desister', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function desister(Quest $quest, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($quest->getUsers()->contains($user)){
            $quest = $quest->removeUser($user);
            $entityManager->persist($quest);
            $entityManager->flush();
            $this->addFlash('success', 'Vous venez de vous desister d\'une quête lâche ! ');
            return $this->redirectToRoute('quest_index');
        } else {
            $this->addFlash('warning', 'Vous n\'avez pas pu vous désister ');
        }

        return $this->redirectToRoute('quest_index');
    }


    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(
        Request                $request,
        Quest                  $quest,
        EntityManagerInterface $entityManager,
        QuestRepository        $questRepository,
        int                    $id): Response
    {
        $quest = $questRepository->find($id);

        if ($this->isCsrfTokenValid('delete' . $quest->getId(), $request->getPayload()->getString('_token'))) {

            if ($quest->getPromoter() != $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
                throw $this->createAccessDeniedException("Ne destroies point la sortie qui n'est nulle la tienne!");
            }

            $entityManager->remove($quest);
            $entityManager->flush();
        }
        return $this->redirectToRoute('quest_index', ['id' => $quest->getId()], Response::HTTP_SEE_OTHER);
    }
}
