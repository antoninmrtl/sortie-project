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
use App\Utils\QuestRegistrationService;
use App\Utils\StatusUpdater;
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
    public function index(QuestRepository $questRepository, StatusUpdater $statusUpdater, Request $request): Response
    {
        $statusUpdater->updateStatus();

        $questSearch = new QuestSearch();
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
        $questForm = $this->createForm(QuestSearchType::class, $questSearch);
        $questForm->handleRequest($request);


        $quests = $questRepository->findBySearch($questSearch, $user);


        return $this->render('quest/index.html.twig', [
            'quests' => $quests,
            'questForm' => $questForm
        ]);
    }

    #[Route('/create', name: 'create')]
    #[IsGranted("ROLE_USER")]
    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'])]
    public function createOrEdit(
        Request                $request,
        EntityManagerInterface $entityManager,
        StatusRepository       $statusRepository,
        FileUploader           $fileUploader,
        StatusUpdater          $statusUpdater,
        ?Quest                 $quest = null,
    ): Response
    {

        $quest = $quest ?? new Quest();

        $this->denyAccessUnlessGranted('QUEST_EDIT', $quest, "Vas saboter la quête d'autrui, malautru!");


        $form = $this->createForm(QuestType::class, $quest);
        $form->handleRequest($request);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $action = $request->request->get('save_action');


            $file = $form->get('picture')->getData();
            if ($file) {
                $quest->setPicture(
                    $fileUploader->upload($file, 'assets/images/pictureQuest', $quest->getName())
                );
            }
            $quest->setPromoter($user);
            $quest->addUser($user);

            $enCreationStatus = $statusRepository->findOneBy(['label' => 'En création']);

            if($action === 'save') {
                $quest->setStatus($enCreationStatus);
                $quest = $statusUpdater->createStatus($quest);

            } else {
                $quest = $statusUpdater->createStatus($quest);
            }

            $entityManager->persist($quest);
            $entityManager->flush();

            return $this->redirectToRoute('quest_show', ['id' => $quest->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quest/new.html.twig', [
            'quest' => $quest,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    #[IsGranted("ROLE_USER")]
    public function show(int $id, QuestRepository $questRepository): Response
    {
        $quest = $questRepository->find($id);
        if (!$quest) {
            throw $this->createNotFoundException('Quête introuvable !');
        }

        return $this->render('quest/show.html.twig', [
            'quest' => $quest,
        ]);
    }


    #[Route('/inscription/{id}', name: 'inscription', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function inscription(Quest $quest,  QuestRegistrationService $questRegistrationService): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $verify = $questRegistrationService->inscriptionVerif($quest, $user);

        if ($verify) {
            $this->addFlash('success', 'Bienvenue a l\'aventure');
            return $this->redirectToRoute('quest_show', ['id' => $quest->getId()]);
        } else {
            $this->addFlash('warning', 'Vous Ne pouvez pas vous inscrire à cette quête aventurier');
        }

        return $this->redirectToRoute('quest_index');
    }

    #[Route('/desister/{id}', name: 'desister', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function desister(Quest $quest, QuestRegistrationService $questRegistrationService): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $verify = $questRegistrationService->desisterVerif($quest, $user);

        if ($verify) {
            $this->addFlash('success', 'Vous venez de vous desister d\'une quête lâche ! ');
            return $this->redirectToRoute('quest_index');
        } else {
            $this->addFlash('warning', 'Vous n\'avez pas pu vous désister ');
        }

        return $this->redirectToRoute('quest_index');
    }

    #[Route('/annuler/{id}', name: 'annuler', methods: ['GET'])]
    public function annuler(Quest $quest, QuestRegistrationService $questRegistrationService): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $verify = $questRegistrationService->aboveVerif($quest, $user);

        if ($verify) {
            $this->addFlash('success', 'Vous êtes bien propriétaire de cette quest !');
            return $this->render('quest/above.html.twig', ['id' => $quest->getId(),
                'quest' => $quest]);
        } else {
            $this->addFlash('warning', 'Vous n\'avez pas pu annuler la quête ');
        }

        return $this->redirectToRoute('quest_show', ['id' => $quest->getId()]);

    }

    #[Route('/confirmAnnuler/{id}', name: 'confirmAnnuler',requirements: ['id' => '\d+'])]
    public function confirmAnnuler(Request $request, Quest $quest, StatusRepository $statusRepository, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('QUEST_CANCEL', $quest);

        $motif = $request->request->get('motif');

        $annuledStatus = $statusRepository->findOneBy(['label' => 'Annulée']);

        $quest->setStatus($annuledStatus);
        $quest->setInfoQuest($quest->getInfoQuest() . ' Raison de l\'annulation : ' . $motif);
        $entityManager->persist($quest);
        $entityManager->flush();
        $this->addFlash('success', 'Vous venez d\'annuler votre quête');

        return $this->redirectToRoute('quest_index');

    }


    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(
        EntityManagerInterface $entityManager,
        QuestRepository        $questRepository,
        int                    $id): Response
    {
        $quest = $questRepository->find($id);

        $this->denyAccessUnlessGranted('QUEST_DELETE', $quest, 'Ne destroies point la sortie qui n\'est nulle la tienne!');

        $entityManager->remove($quest);
        $entityManager->flush();
//        coucou
        $this->addFlash('success', 'Quête supprimée avec success');

        return $this->redirectToRoute('quest_index', ['id' => $quest->getId()], Response::HTTP_SEE_OTHER);

    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/archive', name: 'archive', methods: ['GET'])]
    public function archive(QuestRepository $questRepository): Response
    {
        $quests = $questRepository->findAllArchive();

        return $this->render('quest/archive.html.twig', [
            'quests' => $quests,
        ]);
    }


}
