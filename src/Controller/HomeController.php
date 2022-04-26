<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\EffectiveWorkDays;
use App\Repository\EffectiveWorkDaysRepository;
use App\Repository\PlannedWorkDaysRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(UserRepository $user, EffectiveWorkDaysRepository $effectiverepo, PlannedWorkDaysRepository $plannedRepo): Response
    {
        // TODO : Créer un service ou un listener pour déclencher cette action a chaque tentative d'accès à une page
        // Je m'assure qu'un utilisateur est correctement connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Je récupère l'utilisateur connecté
        $user = $this->getUser();
        
        // Envoie de la date du jour en français
        setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
        $today = strftime("%A %d %B %Y");                   
        
        //! ON STOCKE LE JOUR MOI ANNEE
        $dateTimeToday = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $dateTimeToday = $dateTimeToday->format('Y-m-d');

        //! ON RECUPERE LE PLANNING DU USER AVEC LA DATE RECHERCHEE
        //si pas de planning trouvé retourne FALSE
        $userPlannedThisDay = $plannedRepo->findOneUserPlanning($user->getId() , $dateTimeToday);
        $userEffectiveWork = $effectiverepo->findEffectiveWorkUser($user->getId() , $dateTimeToday);
        //dd($userPlannedThisDay);

        //! Si utilisateur planifié ce jour alors j'affiche sur l'index les pointages
        if(is_array($userPlannedThisDay)){

            // parcours les heures effectives du user pour que la date du jour coincide avec le pointage du jour
            // si coincide, retourne à la vue les pointages du jour
            //for( $i = 0 ; $i < count($effectiveWorkUser) ; $i++){
            //    if( $dateTimeToday === $effectiveWorkUser[$i]->getStartlog()->format('Y-m-d') ) {
                
                    // Je retourne à la vue l'utilisateur connecté
                    return $this->render('home/index.html.twig', [
                        'user' => $user,
                        'today' => $today,
                        'dateTimeToday' => $dateTimeToday,
                        'userPlannedThisDay' => $userPlannedThisDay,
                        'userEffectiveWork' => $userEffectiveWork
                        ]);
                }
            
        else {
            return $this->render('home/index.html.twig', [
                'user' => $user,
                'today' => $today,
                'dateTimeToday' => $dateTimeToday,
                'userPlannedThisDay' => $userPlannedThisDay,
                'userEffectiveWork' => $userEffectiveWork
                ]);
        }
    
    }

    /**
     * Méthode pour ajout en BDD de l'évènement cliqué début de journée
     * 
     * @Route("/log/startlog" , name="startlog", methods={"POST"})
     * @return void
     */
    public function startLog(Request $request, EntityManagerInterface $doctrine, EffectiveWorkDaysRepository $effectiverepo): Response
    {
        $user = $this->getUser();
    
        // Stocke la date et l'heure 
        $shift = new DateTime('now', new DateTimeZone('Europe/Paris'));
           
        // Formate le datetime en année / mois / jour 
        $dateTimeToday = $shift->format('Y-m-d');

        
        // Récupère le pointage (si existant) du user connecté
        $userEffectiveWork = $effectiverepo->findEffectiveWorkUser($user->getId() , $dateTimeToday);
        
         //! Si user n'a pas cliqué sur début de journée alors je l'enregistre en BDD
            if( $userEffectiveWork === false ) {
                $effectiveWorkModel = new EffectiveWorkDays();
                $effectiveWorkModel->setStartlog($shift);
                $effectiveWorkModel->setCreatedAt($shift);

                $doctrine->persist($effectiveWorkModel->setUser($user));
                $doctrine->flush();

                return $this->json(
                    json_encode($effectiveWorkModel),
                    200
                );
            }
             //! Sinon si user a déjà un startLog je ne rentre rien en BDD
            else {
                return $this->json(
                    json_encode('debut de journee deja fait'),
                    200
                );
            }
    }

    /**
     * Méthode pour ajout en BDD de l'évènement cliqué début de pause repas
     * 
     * @Route("/log/startlunch" , name="startlunch", methods={"POST"})
     * @return void
     */
    public function startLunch(Request $request, EntityManagerInterface $doctrine, EffectiveWorkDaysRepository $effectiverepo): Response
    {
        $user = $this->getUser();
    
        // Stocke la date et l'heure 
        $shift = new DateTime('now', new DateTimeZone('Europe/Paris'));
           
        // Formate le datetime en année / mois / jour 
        $dateTimeToday = $shift->format('Y-m-d');

        // Récupère le pointage (si existant) du user connecté
        $userEffectiveWork = $effectiverepo->findEffectiveWorkUser($user->getId() , $dateTimeToday);
        
        //! Si user n'a pas cliqué sur début de pause repas alors je l'enregistre en BDD
        if ( $userEffectiveWork && $userEffectiveWork['startlunch'] === null)
        {
                $effectiveWorkModel = $effectiverepo->find($userEffectiveWork['id']);
                $effectiveWorkModel->setStartlunch($shift);
                $effectiveWorkModel->setUpdatedAt($shift);

                $doctrine->persist($effectiveWorkModel->setUser($user));
                $doctrine->flush();

                return $this->json(
                    json_encode($effectiveWorkModel),
                    200
                );
        }

        //! Sinon si user a déjà un startLunch je ne rentre rien en BDD
        else {
                return $this->json(
                    json_encode('debut de pause repas deja fait'),
                    200
                );
        }
        
    }

    /**
     * Méthode pour ajout en BDD de l'évènement cliqué fin de pause repas
     * 
     * @Route("/log/endlunch" , name="endlunch", methods={"POST"})
     * @return void
     */
    public function endLunch(Request $request, EntityManagerInterface $doctrine, EffectiveWorkDaysRepository $effectiverepo): Response
    {
        $user = $this->getUser();
    
        // Stocke la date et l'heure 
        $shift = new DateTime('now', new DateTimeZone('Europe/Paris'));
           
        // Formate le datetime en année / mois / jour 
        $dateTimeToday = $shift->format('Y-m-d');

        // Récupère le pointage (si existant) du user connecté
        $userEffectiveWork = $effectiverepo->findEffectiveWorkUser($user->getId() , $dateTimeToday);
        
        //! Si user n'a pas cliqué sur fin de pause repas alors je l'enregistre en BDD
        if ( $userEffectiveWork && $userEffectiveWork['endlunch'] === null)
        {
                $effectiveWorkModel = $effectiverepo->find($userEffectiveWork['id']);
                $effectiveWorkModel->setEndlunch($shift);
                $effectiveWorkModel->setUpdatedAt($shift);

                $doctrine->persist($effectiveWorkModel->setUser($user));
                $doctrine->flush();

                return $this->json(
                    json_encode($effectiveWorkModel),
                    200
                );
        }

        //! Sinon si user a déjà un endLunch je ne rentre rien en BDD
        else {
                return $this->json(
                    json_encode('debut de pause repas deja fait'),
                    200
                );
        }
        
    }

    /**
     * Méthode pour ajout en BDD de l'évènement cliqué fin de journée
     * 
     * @Route("/log/endlog" , name="endlog", methods={"POST"})
     * @return void
     */
    public function endLog(Request $request, EntityManagerInterface $doctrine, EffectiveWorkDaysRepository $effectiverepo): Response
    {
        $user = $this->getUser();
    
        // Stocke la date et l'heure 
        $shift = new DateTime('now', new DateTimeZone('Europe/Paris'));
           
        // Formate le datetime en année / mois / jour 
        $dateTimeToday = $shift->format('Y-m-d');

        // Récupère le pointage (si existant) du user connecté
        $userEffectiveWork = $effectiverepo->findEffectiveWorkUser($user->getId() , $dateTimeToday);
        
        //! Si user n'a pas cliqué sur fin de journée alors je l'enregistre en BDD
        if ( $userEffectiveWork && $userEffectiveWork['endlog'] === null)
        {
                $effectiveWorkModel = $effectiverepo->find($userEffectiveWork['id']);
                $effectiveWorkModel->setEndlog($shift);
                $effectiveWorkModel->setUpdatedAt($shift);

                //Calcul du temps de pause repas
                $lunchTime = new DateTime($effectiveWorkModel->getStartlunch()->diff($effectiveWorkModel->getEndlunch())->format('%h:%i'));
                //Calcul de la journée total de travail
                $workTime = new DateTime($effectiveWorkModel->getStartlog()->diff($effectiveWorkModel->getEndlog())->format('%h:%i'));
                //Soustraction du temps de pause repas au temps total d'heures travaillées 
                $effectiveWorkModel->setHoursworked(new DateTime(($lunchTime)->diff($workTime)->format('%h:%i')));

                $doctrine->persist($effectiveWorkModel->setUser($user));
                $doctrine->flush();

                return $this->json(
                    json_encode($effectiveWorkModel),
                    200
                );
        }

        //! Sinon si user a déjà un endLog je ne rentre rien en BDD
        else {
                return $this->json(
                    json_encode('debut de pause repas deja fait'),
                    200
                );
        }
        
    }
    
}
