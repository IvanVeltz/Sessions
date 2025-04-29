<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Trainee;
use App\Repository\SessionRepository;
use App\Repository\TraineeRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function show(Session $session,SessionRepository $sessionRepository, TraineeRepository $traineeRepository, int $id): Response
    {

        $traineesNoRegister = $traineeRepository->findTraineesNotInSession($id);
        $nbrDaysOpen = $sessionRepository->getNbOpenDays($session->getDateStart(), $session->getDateEnd());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'traineesNoRegister' => $traineesNoRegister,
            'nbrDaysOpen' => $nbrDaysOpen
        ]);
    }

 
    #[Route('/session/{sessionId}/addTrainee/{traineeId}', name: 'add_session_trainee')]
    public function addTraineeToSession(int $sessionId,int  $traineeId, SessionRepository $sessionRepository, TraineeRepository $traineeRepository, EntityManagerInterface $entityManager)
    {
        $session = $sessionRepository->findOneBy(['id'=> $sessionId]);
        $trainee = $traineeRepository->findOneBy(['id' => $traineeId]);
        $nbrPlaces = $session->getNbrPlaces() - count($session->getTrainees());

        $session->addTrainee($trainee);

        $entityManager->persist($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/{sessionId}/removeTrainee/{traineeId}', name: 'remove_session_trainee')]
    public function removeTraineeToSession(int $sessionId,int  $traineeId, SessionRepository $sessionRepository, TraineeRepository $traineeRepository, EntityManagerInterface $entityManager)
    {
        $session = $sessionRepository->findOneBy(['id'=> $sessionId]);
        $trainee = $traineeRepository->findOneBy(['id' => $traineeId]);
        
        $session->removeTrainee($trainee);

        $entityManager->persist($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
}