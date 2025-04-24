<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use App\Repository\TrainingRepository;
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

    #[Route('/training/{id}', name: 'show_training')]
    public function show(SessionRepository $sessionRepository,int $id): Response
    {
        $sessions = $sessionRepository->findBy(['training' => $id], []);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }
}
