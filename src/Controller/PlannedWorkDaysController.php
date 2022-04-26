<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\PlannedWorkDays;
use App\Entity\User;
use App\Form\PlannedWorkDaysType;
use App\Repository\DepartementRepository;
use App\Repository\EffectiveWorkDaysRepository;
use App\Repository\PlannedWorkDaysRepository;
use App\Repository\UserRepository;
use App\Service\HoursPerWeek;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/planning")
 */
class PlannedWorkDaysController extends AbstractController
{

    /**
     * @Route("/", name="planned_user", methods={"GET"})
     */
    public function userPlanning(HoursPerWeek $hpw, UserRepository $users): Response
    {
        // on recupère l'utilisateur connecté
        $userLogged = $this->getUser();

        // Call to 'PLANNING_VIEWTEAM' from PlanningVoter
        // A user must be logged in to be able to access this page
        // Only Managers and RH Roles can access this page
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // la méthode hoursperWeek sert à calculer les heures d'une semaine
        // on la retrouve dans le service User
        $thw = $hpw->hoursPerWeek($userLogged);
        
        $dptManager = $users->findByManagerDepartementSQL($userLogged->getDepartement()->getId(),3);

        if($dptManager) {
            $manager = $dptManager[0];
        } else {
            $manager = '';
        }

        return $this->render('planning/user.planning.html.twig', [
            'user' => $userLogged,
            'totalHoursWeek' => $thw,
            'dptManager' => $manager,
        ]);
    }

    /**
     * @Route("/departement", name="planned_departement", methods={"GET"})
     */
    public function departementPlanning(UserRepository $user, DepartementRepository $departement, HoursPerWeek $hpw): Response
    {
        // on recupère l'utilisateur connecté
        $userLogged = $this->getUser();

        // Call to 'PLANNING_VIEWTEAM' from PlanningVoter
        // A user must be logged in to be able to access this page
        // Only Managers and RH Roles can access this page
        $this->denyAccessUnlessGranted('PLANNING_VIEWTEAM', $userLogged);
        
        $departementId = $userLogged->getDepartement()->getId();
        $dpt = $departement->find($departementId);
        //requête DQL pour afficher les membres de l'equipe sans le manager connecté 
        $departementUser = $user->findByTeamDQL($departementId, $userLogged->getid());
        $nbUser = (count($departementUser)-1);

        return $this->render('planning/departement.planning.html.twig', [
            'dptUser' => $departementUser,
            'nbUser' => $nbUser,
            'hpw' => $hpw,
            'userLogged' => $userLogged,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="planned_work_days_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PlannedWorkDays $plannedWorkDay, EntityManagerInterface $entityManager): Response
    {
    
        // on recupère l'utilisateur connecté
        $userLogged = $this->getUser();

        // Call to 'PLANNING_VIEWTEAM' from PlanningVoter
        // A user must be logged in to be able to access this page
        // Only Managers and RH Roles can access this page
        $this->denyAccessUnlessGranted('PLANNING_VIEWTEAM', $userLogged);
        
 
        $planningForm = $this->createForm(PlannedWorkDaysType::class, $plannedWorkDay);
        $planningForm->handleRequest($request);

        if ($planningForm->isSubmitted() && $planningForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('planned_departement', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning/edit.html.twig', [
            'planned_work_day' => $plannedWorkDay,
            'planning' => $planningForm,
        ]);
    }

    /**
     * @Route("/new", name="planned_work_days_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        // on recupère l'utilisateur connecté
        $userLogged = $this->getUser();

        // Call to 'PLANNING_VIEWTEAM' from PlanningVoter
        // A user must be logged in to be able to access this page
        // Only Managers and RH Roles can access this page
        $this->denyAccessUnlessGranted('PLANNING_VIEWTEAM', $userLogged);
        
    
        $plannedWorkDay = new PlannedWorkDays();
        $planningForm = $this->createForm(PlannedWorkDaysType::class, $plannedWorkDay);
        $planningForm->handleRequest($request);

        if ($planningForm->isSubmitted() && $planningForm->isValid()) {
            $entityManager->persist($plannedWorkDay);
            $entityManager->flush();

            return $this->redirectToRoute('planned_departement', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning/new.html.twig', [
            'planned_work_day' => $plannedWorkDay,
            'planning' => $planningForm,
        ]);
    }

    /**
     * @Route("/comparatif", name="comparative_planned", methods={"GET"})
     */
    public function Comparative(PlannedWorkDaysRepository $plannedRepo, EffectiveWorkDaysRepository $effectiveRepo): Response
    {
        // on recupère l'utilisateur connecté
        $userLogged = $this->getUser();

        // Call to 'PLANNING_VIEWTEAM' from PlanningVoter
        // A user must be logged in to be able to access this page
        // Only Managers and RH Roles can access this page
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // ON STOCKE LE JOUR MOI ANNEE
        $dateTimeToday = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $dateTimeToday = $dateTimeToday->format('Y-m-d');

       
        // ON RECUPERE LE PLANNING DU USER AVEC LA DATE RECHERCHEE
        //si pas de planning trouvé retourne FALSE
        $userPlanned = $plannedRepo->findOneUserPlanning($userLogged->getId() , $dateTimeToday);
        $userEffective = $effectiveRepo->findEffectiveWorkUser($userLogged->getId() , $dateTimeToday);

        if ($userEffective !== false) {
            $hoursPlanned = new DateTime($userPlanned['hoursplanned']);
            $hoursWorked = new DateTime($userEffective['hoursworked']);

            $gap = $hoursPlanned->diff($hoursWorked)->format('%R%Hh%I');
            $pos = strpos($gap, '+');
        } else {
            $gap ='';
            $pos='';
        }

        return $this->render('planning/comparative.planning.html.twig', [
            'user' => $userLogged,
            'gap' => $gap,
            'pos' => $pos,
            'plannedWorkDay' => $userPlanned,
            'effectiveWorkDay' => $userEffective,
        ]);
    }

}
