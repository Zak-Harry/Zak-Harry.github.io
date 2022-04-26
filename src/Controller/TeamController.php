<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DepartementRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
     /**
     * @Route("/team", name="view_team", methods={"GET"})
     */
    public function departementPlanning(UserRepository $user, DepartementRepository $departement): Response
    {

        $userLogged = $this->getUser();
        
         // Call to 'PLANNING_VIEWTEAM' from PlanningVoter
       // A user must be logged in to be able to access this page
       // Only Managers and RH Roles can access this page
       $this->denyAccessUnlessGranted('PLANNING_VIEWTEAM', $userLogged);
        
        $departementId = $userLogged->getDepartement()->getId();

        $dpt = $departement->find($departementId);
        //requête DQL pour afficher les membres de l'equipe sans le manager connecté 
        $departementUser = $user->findByTeamDQL($departementId, $userLogged->getid());

        return $this->render('team/index.html.twig', [
            'dpt' => $dpt,
            'dptUser' => $departementUser,
        ]);
    }
}
