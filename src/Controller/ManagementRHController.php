<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 *@IsGranted("ROLE_RH")
 */
class ManagementRHController extends AbstractController
{
    /**
     * Affiche les départements et les managers par département
     * Vue : /management_rh/index.html.twig
     * @Route("/managementrh", name="managementrh")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('/management_rh/index.html.twig', [
            'users' => $userRepository->findByJob()
        ]);
    }

    /**
     * @Route("/managementrh/search", name="managementrh_search")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function SearchSalarie(Request $request, UserRepository $userRepository): Response
    {

        $salarie = $request->request->all()["salarie"];
        $username = explode(' ', $salarie);
        $firstname = $username[0];
        $lastname = $username[1];
        $user = $userRepository->findOneBy(array("firstname" => $firstname, "lastname" => $lastname));
        if(is_null($user))
        {
            $user = $userRepository->findOneBy(array("firstname" => $lastname, "lastname" => $firstname));
        }
        return $this->redirectToRoute('profil_id', [
            'id' => $user->getId()
        ]);

    }
}