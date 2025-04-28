<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use App\Repository\TraineeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findAll();
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, TraineeRepository $traineeRepository, int $id): Response
    {

        $traineesNoRegister = $traineeRepository->findTraineesNotInSession($id);

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'traineesNoRegister' => $traineesNoRegister
        ]);
    }
}
