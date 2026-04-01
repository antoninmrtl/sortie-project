<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\QuestRepository;
use App\Repository\UserRepository;
use App\Utils\FileUploader;
use App\Utils\UserLogicService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'user_')]
final class                   UserController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function displayAll(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        UserLogicService $logicService,
        ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'is_edit' => false
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logicService->create($form, $user);

            $this->addFlash('success','Utilisateur crée avec succès');
            return $this->redirectToRoute('user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,
                         UserLogicService $userLogicService,
                         UserRepository $userRepository,
                         int $id
    ): Response
    {
        $user = $userRepository->find($id);

        $this->denyAccessUnlessGranted('USER_EDIT', $user, 'Vous ne pouvez pas modifer cet utilisateur');

        $form = $this->createForm(UserType::class, $user, [
            'is_edit' => true
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $userLogicService->edit($form,$user);
            $this->addFlash('success','Utilisateur modifié avec succès');
            return $this->redirectToRoute('user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);

        $this->denyAccessUnlessGranted('USER_DELETE', $user, 'Vous ne pouvez pas supprimer cet utilisateur');

        $currentUser = $this->getUser();
        $isSelfDelete = ($currentUser === $user);
        $entityManager->remove($user);
        $entityManager->flush();

        if ($isSelfDelete) {
            $request->getSession()->invalidate();
            $this->container->get('security.token_storage')->setToken(null);

            $this->addFlash('success', 'Votre compte a été définitivement supprimé.');
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('success', "L'utilisateur {$user->getUsername()} a été supprimé.");
        return $this->redirectToRoute('user_index');
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function profile(QuestRepository $questRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $questCreate = $questRepository->findAllCreateByPromoter($user);

        return $this->render('user/profile.html.twig', [
            'questCreate' => $questCreate,
        ]);
    }
}
