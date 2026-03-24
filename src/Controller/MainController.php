<?php

namespace App\Controller;

use App\Repository\QuestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('', name: 'main_home')]
    public function home(QuestRepository $questRepository): Response
    {

        $qests = $questRepository->findAll();
        return $this->render('main/home.html.twig', [
            'quests' => $qests,
        ]);
    }

    #[Route('/test', name: 'main_test')]
    public function test(QuestRepository $questRepository): Response
    {

        $qests = $questRepository->findAll();
        return $this->render('main/test.html.twig', [
            'quests' => $qests,
        ]);
    }
}
