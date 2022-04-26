<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{

    /**
     * Affiche la page profil de l'utilisateur actuellement connecté
     * @Route("/profil", name="profil")
     * @Route("/profil/{id}", name="profil_id", requirements={"id"="\d+"})
     * @param User|null $user
     * @return Response
     */
    public function showProfil(User $user = NULL): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // Le rendu de cette page se fait sur le template Twig : profil/index.html.twig
        if(is_null($user))
        {
            $user = $this->getUser();
        }

        return $this->render('profil/index.html.twig', [
            'user' => $user
        ]);
    }


    /**
     * Cette méthode permet de créer ou d'éditer une page de profil
     * Le rendu de cette page se fait sur le template Twig : profil/profilform.html.twig
     * @Route("/profil/new", name="profil_new")
     * @Route("/profil/edit/{id}", name="profil_edit")
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @param User|null $user
     * @return Response
     */
    public function formProfil(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher, User $user = NULL): Response
    {
        // Si pas de USER alors crée un USER, ceci pour l'ajout d'un salarié
        if(!$user)
        {
            // Si utilisateur est RH alors il peut CREATE
            $this->denyAccessUnlessGranted('CREATE');
            $user = new User();
        }

        $profilForm = $this->createForm(ProfilType::class, $user);
        // Si utilisateur est RH ou utilisateur connecte = page de profil demandé alors il peut EDIT
        $this->denyAccessUnlessGranted('EDIT', $profilForm);
        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted() && $profilForm->isValid())
        {

            if (is_null($user->getId()))
            {
                $user->setPassword($hasher->hashPassword($user, strtolower($user->getFirstname())));
                $user->setEmailpro($user->getFirstname() . $user->getLastname() . '@oclock.io');
                $user->setCreatedAt(new \DateTime());
            }

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('profil_id', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/profilform.html.twig',
            [
                'profil' => $profilForm,
                'user' => $user ?? new User()
            ]);
    }

}