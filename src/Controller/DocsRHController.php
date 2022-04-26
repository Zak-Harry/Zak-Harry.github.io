<?php

namespace App\Controller;

use App\Entity\Payslip;
use App\Repository\PayslipRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocsRHController extends AbstractController
{
    /**
     * @Route("/documentation", name="documentation")
     */
    public function Documents( PayslipRepository $payslipRepo ) : Response
    {
        
        $userLogged = $this->getUser();
        
        // Call to 'PLANNING_VIEW' from PlanningVoter
      // A user must be logged in to be able to access this page
      // ALL users can access this page
        $this->denyAccessUnlessGranted('PLANNING_VIEW', $userLogged);

        
        return $this->render('documentation/index.html.twig', [
            'user' => $userLogged,

        ]);
    }
}
