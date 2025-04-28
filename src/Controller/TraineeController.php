<?php

namespace App\Controller;

use App\Entity\Trainee;
use App\Repository\TraineeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TraineeController extends AbstractController
{
    #[Route('/trainee', name: 'app_trainee')]
    public function index(TraineeRepository $traineeRepository): Response
    {
        $trainees = $traineeRepository->findAll();
        return $this->render('trainee/index.html.twig', [
            'trainees' => $trainees,
        ]);
    }

    #[Route('/trainee/{id}', name: 'show_trainee')]
    public function show(Trainee $trainee): Response
    {
        return $this->render('trainee/show.html.twig', [
            'trainee' => $trainee,
        ]);
    }
}
