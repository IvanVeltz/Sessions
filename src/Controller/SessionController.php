<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Session;
use App\Entity\Trainee;
use App\Form\SessionType;
use App\Repository\ModuleRepository;
use App\Repository\ProgramRepository;
use App\Repository\SessionRepository;
use App\Repository\TraineeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/session/new', name: 'new_session')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = new Session();

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $session = $form->getData();

            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession'=>$form,
        ]);
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session,SessionRepository $sessionRepository, TraineeRepository $traineeRepository, ModuleRepository $moduleRepository, int $id): Response
    {

        $traineesNoRegister = $traineeRepository->findTraineesNotInSession($id);
        $modulesNoProgram = $moduleRepository->findModulesNotInSession($id);
        $nbrDaysOpen = $sessionRepository->getNbOpenDays($session->getDateStart(), $session->getDateEnd());

        return $this->render('session/show.html.twig', [
            'session' => $session,
            'traineesNoRegister' => $traineesNoRegister,
            'nbrDaysOpen' => $nbrDaysOpen,
            'modulesNoProgram' => $modulesNoProgram
        ]);
    }

    #[Route('/session/{sessionId}/removeProgram/{programId}', name: 'remove_session_program')]
    public function removeProgramToSession(int $sessionId,int  $programId, SessionRepository $sessionRepository, ProgramRepository $programRepository, EntityManagerInterface $entityManager)
    {
        $session = $sessionRepository->findOneBy(['id'=> $sessionId]);
        $program = $programRepository->findOneBy(['id' => $programId]);
        
        $session->removeProgram($program);

        $entityManager->persist($session);
        $entityManager->flush();
        
        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }

    #[Route('/session/{sessionId}/addProgram/{moduleId}', name: 'add_session_program')]
    public function addModuleToProgram(
        int $sessionId, 
        int $moduleId, 
        SessionRepository $sessionRepository, 
        ModuleRepository $moduleRepository,
        EntityManagerInterface $entityManager,
        Request $request) 
    {
        // Récupération des entités session et module
        $session = $sessionRepository->find($sessionId);
        $module = $moduleRepository->find($moduleId);
        $totalDaysSession = $sessionRepository->getNbOpenDays($session->getDateStart(), $session->getDateEnd());
        $daysSession = 0;
        foreach ($session->getPrograms() as $program){
            $daysSession += $program->getNbrDays();
        }
        if ($request->isMethod('POST') && $request->request->has('submit')) {
            // Récupération et validation du nombre de jours
            $days = $request->request->getInt('days', 0); // Default à 0 si pas de valeur

            if ($days <= 0) {
                return $this->redirectToRoute('show_session', ['id' => $sessionId]);
            }

            if ($days + $daysSession <= $totalDaysSession){

            // Création et paramétrage de l'entité Program
            $program = new Program();
            $program->setSession($session);
            $program->setModule($module);
            $program->setNbrDays($days);
            // Persistance des données
            $entityManager->persist($program);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Le module a été ajouté avec succès à la session.');
            } else {
                $this->addFlash('error', 'Le module n\'a pas pu être ajouté. Nombre de jours de la sassion insufissante');
            }
        }

        return $this->redirectToRoute('show_session', ['id' => $session->getId()]);
    }
 
    #[Route('/session/{sessionId}/addTrainee/{traineeId}', name: 'add_session_trainee')]
    public function addTraineeToSession(int $sessionId,int  $traineeId, SessionRepository $sessionRepository, TraineeRepository $traineeRepository, EntityManagerInterface $entityManager)
    {
        $session = $sessionRepository->findOneBy(['id'=> $sessionId]);
        $trainee = $traineeRepository->findOneBy(['id' => $traineeId]);

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