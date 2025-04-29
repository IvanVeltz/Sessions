<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TrainingController extends AbstractController
{
    #[Route('/training', name: 'app_training')]
    public function index(TrainingRepository $trainingRepository): Response
    {
        $trainings = $trainingRepository->findAll();
        return $this->render('training/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/training/new', name: 'new_training')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $training = new Training();
        
        $form = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $training = $form->getData();

            $entityManager->persist($training);
            $entityManager->flush();

            return $this->redirectToRoute('app_training');
        }

        return $this->render('training/new.html.twig', [
            'formAddTraining'=>$form,
        ]);
    }

    #[Route('/training/{id}', name: 'show_training')]
    public function show(Training $training): Response
    {
        return $this->render('training/show.html.twig', [
            'training' => $training
        ]);
    }
}
