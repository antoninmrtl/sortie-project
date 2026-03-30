<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/place', name: 'place_')]
final class PlaceController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add( Request $request, EntityManagerInterface $entityManager): Response
    {

        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($place);
            $entityManager->flush();
            $this->addFlash('success', 'Nouvel endroit créee');
            return $this->redirectToRoute('quest_create');

        }
        return $this->render('place/add.html.twig', [
            'form' => $form
        ]);

    }

}

