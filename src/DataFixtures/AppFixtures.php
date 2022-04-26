<?php

namespace App\DataFixtures;


use App\Entity\Contract;
use App\Entity\Departement;
use App\Entity\DepartementJob;
use App\Entity\Documentation;
use App\Entity\EffectiveWorkDays;
use App\Entity\Job;
use App\Entity\Payslip;
use App\Entity\Role;
use App\Entity\PlannedWorkDays;
use App\Entity\User;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Doctrine\DBAL\Connection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Connection $connexion;
    private UserPasswordHasherInterface $hasher;

    private array $departements = [
        'Marketing' => [
                'Web Designer',
                'Chef de produit',
                'Data manager'
        ],
        'Informatique' => [
                'Dev OPS',
                'Administrateur Système'
        ],
        'Développement' => [
                'Développeur Front',
                'Développeur Back'
        ],
        'Comptabilité' => [
                'Comptable',
                'Expert comptable',
                'Analyste'
        ]
    ];

    private array $departementRH = [
            'Chargé de formation',
            'Chargé de recrutement',
            'Chargé de paie'
    ];

    private array $grade = ['Cadre', 'Agent de maitrise', 'autre'];


    public function __construct(Connection $connexion, UserPasswordHasherInterface $hasher)
    {
        $this->connexion = $connexion;
        $this->hasher = $hasher;
    }
    
    // On sépare un peu notre code

    /**
     * @throws Exception
     */
    private function truncate()
    {
        //  on désactive la vérification des FK
        // Sinon les truncate ne fonctionne pas.
        $this->connexion->executeQuery('SET foreign_key_checks = 0');

        // la requete TRUNCATE remet l'auto increment à 1
        $this->connexion->executeQuery('TRUNCATE TABLE contract');
        $this->connexion->executeQuery('TRUNCATE TABLE departement');
        $this->connexion->executeQuery('TRUNCATE TABLE departement_job');
        $this->connexion->executeQuery('TRUNCATE TABLE documentation');
        $this->connexion->executeQuery('TRUNCATE TABLE effective_work_days');
        $this->connexion->executeQuery('TRUNCATE TABLE job');
        $this->connexion->executeQuery('TRUNCATE TABLE payslip');
        $this->connexion->executeQuery('TRUNCATE TABLE role');
        $this->connexion->executeQuery('TRUNCATE TABLE planned_work_days');
        $this->connexion->executeQuery('TRUNCATE TABLE user');
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        
         // on vide les tables avant de commencer
        $this->truncate();
        
        // mis en place de faker
        $faker = Faker::create('fr_FR');

        // fonction pour traduire les jours en mois et jours
        function dateToFrench($date, $format) 
        {
            $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
            $french_days = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
            $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
            $french_months = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
            return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );
        }

        // fonction pour générer une heure aléatoire :
        function randHours($minHour, $maxHour)
        {
            $firstHour = strtotime($minHour);
            $secondHour = strtotime($maxHour);
            return date('H:i', rand($firstHour, $secondHour));
        }
 
        // fonction pour supprimer les accents, enlever les espaces et mettre tout en minuscule
        function formatString($string) {
            $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
            $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
            return strtolower(str_replace(' ','',str_replace($search, $replace, $string)));
        }

        /************* Payslip *************/
        $allPayslip = [];
        for ($i = 1; $i<= 200; $i++) {
            $newPayslip = new Payslip();
            $newPayslip->setLink('http://extranet-rh.lexisnexis.fr/wp-content/uploads/Documents/LIRE-UN-BULLETIN-DE-PAIE.pdf');
            $newPayslip->setCreatedAt(new DateTime('now'));
            $allPayslip[] = $newPayslip;
            $manager->persist($newPayslip);
        }

        /************* Documentation *************/
        $allDocumentation = [];
        for ($i = 1; $i<= 80; $i++) {
            $newDocumentation = new Documentation();
            $newDocumentation->setLink('https://www.conseil-constitutionnel.fr/node/3850/pdf');
            $newDocumentation->setCreatedAt(new DateTime('now'));
            $allDocumentation[] = $newDocumentation;
            $manager->persist($newDocumentation);
        }

        /************* Contract *************/
        $allContract = [];
        for ($i = 1; $i<= 80; $i++) {
            $newContract = new Contract();
            $newContract->setLink('https://www.pajemploi.urssaf.fr/pajewebinfo/files/live/sites/pajewebinfo/files/contributed/pdf/employeur_ama/2377-PAJE-CDI-AMA.pdf');
            $newContract->setCreatedAt(new DateTime('now'));
            $allContract[] = $newContract;
            $manager->persist($newContract);
        }

         
        /************* PlannedWorkDays *************/
        $hoursStart = ['08:00', '9:30'];
        $hoursSlunch = ['12:00', '12:45'];
        $hoursElunch = ['13:15', '13:45'];
        $hoursEnd = ['16:30', '18:00'];
        $allPlanned = [];
        for ($i = 3; $i<= 7; $i++) {
            $newPlanned = new PlannedWorkDays;
            $newPlanned->setStartshift((new DateTime($hoursStart[rand(0,1)]))->modify('+'. $i . ' day'));
            $newPlanned->setStartlunch((new DateTime($hoursSlunch[rand(0,1)]))->modify('+'. $i . ' day'));
            $newPlanned->setEndlunch((new DateTime($hoursElunch[rand(0,1)]))->modify('+'. $i . ' day'));
            $newPlanned->setEndshift((new DateTime($hoursEnd[rand(0,1)]))->modify('+'. $i . ' day'));
            // calcul de la pause déjeuner
            $lunchBreak = new DateTime($newPlanned->getStartlunch()->diff($newPlanned->getEndlunch())->format('%h:%i'));
            //calcul de la journée de travail
            $workDay = new DateTime($newPlanned->getStartshift()->diff($newPlanned->getEndshift())->format('%h:%i'));
            //on peux donc obentir maintenant le nombre d'ehures travaillée dans la journée sans la pause déjeuner
            $newPlanned->setHoursplanned(new DateTime(($workDay)->diff($lunchBreak)->format('%h:%i')));
            $newPlanned->setCreatedAt(new DateTime('now'));
            $allPlanned[] = $newPlanned;

            $manager->persist($newPlanned);
        }

        /************* EffetiveWorkDays *************/
        $allEffective = [];
        for ($i = 0; $i<= 200; $i++) {
            $newEffective = new EffectiveWorkDays;
            $newEffective->setStartlog(new DateTime(randHours('08:00', '10:00')));
            $newEffective->setStartlunch(new DateTime(randHours('12:00', '12:30')));
            $newEffective->setEndlunch(new DateTime(randHours('12:30', '13:30')));
            $newEffective->setEndlog(new DateTime(randHours('16:30', '18:30')));
            // calcul de la pause déjeuner
            $lunchBreak = new DateTime($newEffective->getStartlunch()->diff($newEffective->getEndlunch())->format('%h:%i'));
            //calcul de la journée de travail
            $workDay = new DateTime($newEffective->getStartlog()->diff($newEffective->getEndlog())->format('%h:%i'));
            //on peut donc obtenir maintenant le nombre d'heures travaillé dans la journée sans la pause déjeuner
            $newEffective->setHoursworked(new DateTime(($workDay)->diff($lunchBreak)->format('%h:%i')));
            $newEffective->setCreatedAt(new DateTime('now'));
            $allEffective[] = $newEffective;

            $manager->persist($newEffective);
        }
       
        /************* Role *************/
        $role = ['ROLE_USER', 'ROLE_RH', 'ROLE_MANAGER'];
        $allRole =[];
        
        foreach ($role as $roleName) {
            $newRole = new Role();
            $newRole->setName($roleName);
            $newRole->setCreatedAt(new DateTime('now'));
            $allRole[] = $newRole;
            $manager->persist($newRole);
        }

        $allDepartementJobRH = [];
        $allDepartementJob = [];

        // Je crée le département
            // Je crée un user avec en job, la première valeur du tableau département et le role 3 du tableau des rôle (Manager)
                // Je partage le dernier niveau du tableau département et je crée un User par valeur avec le Role USER

        /*****Ajout de l'emploi MANAGER [HORS DEPARTEMENT RH]*****/
        $newJobManager = new Job();
        $newJobManager->setName('Manager');
        $newJobManager->setGrade($this->grade[0]);
        $newJobManager->setCreatedAt(new DateTime('now'));
        $manager->persist($newJobManager);

        foreach ($this->departements as $departementName => $job)
        {
            // Je crée un département
            $newDepartement =  new Departement();
            $newDepartement->setName($departementName)
                ->setCreatedAt(new DateTime('now'));
            $manager->persist($newDepartement);

            $newDepartementJob = new DepartementJob;
            $newDepartementJob->setJob($newJobManager);
            $newDepartementJob->setDepartement($newDepartement);
            $allDepartementJob[] = $newDepartementJob;
            $manager->persist($newDepartementJob);

            // je crée un manager pour ce département
            $newUserManager = new User();
            $newUserManager->setFirstname($faker->firstName());
            $newUserManager->setLastname($faker->lastName());
            $newUserManager->setPicture('https://boredhumans.b-cdn.net/faces2/'. rand(100, 400).'.jpg');
            $newUserManager->setEmail($faker->freeEmail());
            $newUserManager->setEmailpro(formatString(($newUserManager->getFirstname() .'.'. $newUserManager->getLastname() . '@oclock.io')));
            $newUserManager->setPhonenumber($faker->mobileNumber());
            $newUserManager->setPhonenumberpro($faker->phoneNumber());
            $newUserManager->setAddress($faker->streetAddress());
            $newUserManager->setZipcode($faker->postcode());
            $newUserManager->setCity($faker->city());
            $newUserManager->setRib($faker->iban('FR'));
            $newUserManager->setStatus(true);
            $newUserManager->setDateOfBirth($faker->dateTimeBetween('-60years', '-20years'));
            $newUserManager->setCreatedAt(new DateTime('now'));

            $newUserManager->setJob($newJobManager);

            /*****Ajout du département*****/
            $newUserManager->setDepartement($newDepartement);

            //hashage du password
            // le mdp est le prénom en minuscule sans accent
            $hashedPassword = $this->hasher->hashPassword($newUserManager,formatString($newUserManager->getFirstname()));
            $newUserManager->setPassword($hashedPassword);

            /*****Ajout du role *****/
            $newUserManager->setRole($allRole[2]);

            /*****Ajout du contrat *****/
            // On ajoute de 1 à 3 contrats au hasard pour chaque user
            for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                $randomContract = $allContract[rand(0, count($allContract) -1)];
                $newUserManager->addContract($randomContract);
            }

            /*****Ajout des fiches de paie*****/
            // On ajoute de 1 à 24 fiche de paie au hasard pour chaque user
            for ($g = 1; $g <= mt_rand(1, 24); $g++) {
                $randomPayslip = $allPayslip[rand(0, count($allPayslip) -1)];
                $newUserManager->addPayslip($randomPayslip);
            }

            /*****Ajout des documentations*****/
            // On ajoute de 1 à 24 documents au hasard pour chaque user
            for ($g = 1; $g <= mt_rand(1, 24); $g++) {
                $randomDocumentation = $allDocumentation[rand(0, count($allDocumentation) -1)];
                $newUserManager->addDocumentation($randomDocumentation);
            }

            /*****Ajout du planning prévu *****/
            // On ajoute de 5 plannings au hasard pour chaque user

            foreach ($allPlanned as $planned)
            {
                $newUserManager->addPlannedWorkDay($planned);
            }

            /*****Ajout du planning effectué *****/
            // On ajoute de 5 plannings au hasard pour chaque user
            for ($g = 0; $g <= 5; $g++) {
                $newUserManager->addEffectiveWorkDay($allEffective[$g]);
            }
            $manager->persist($newUserManager);

            // FIN CREATION MANAGER DEPARTEMENT

            // CREATION USER RH

            foreach ($this->departements[$departementName] as $jobName)
            {
                $newJob = new Job();
                $newJob->setName($jobName);
                $newJob->setGrade($this->grade[0]);
                $newJob->setCreatedAt(new DateTime('now'));
                $manager->persist($newJob);

                $newDepartementJob = new DepartementJob;
                $newDepartementJob->setJob($newJob);
                $newDepartementJob->setDepartement($newDepartement);
                $allDepartementJob[] = $newDepartementJob;
                $manager->persist($newDepartementJob);

                $newUser = new User();
                $newUser->setJob($newJob);
                $newUser->setFirstname($faker->firstName());
                $newUser->setLastname($faker->lastName());
                $newUser->setPicture('https://boredhumans.b-cdn.net/faces2/'. rand(100, 400).'.jpg');
                $newUser->setEmail($faker->freeEmail());
                $newUser->setEmailpro(formatString(($newUser->getFirstname() .'.'. $newUser->getLastname() . '@oclock.io')));
                $newUser->setPhonenumber($faker->mobileNumber());
                $newUser->setPhonenumberpro($faker->phoneNumber());
                $newUser->setAddress($faker->streetAddress());
                $newUser->setZipcode($faker->postcode());
                $newUser->setCity($faker->city());
                $newUser->setRib($faker->iban('FR'));
                $newUser->setStatus(true);
                $newUser->setDateOfBirth($faker->dateTimeBetween('-60years', '-20years'));
                $newUser->setCreatedAt(new DateTime('now'));

                //hashage du password
                // le mdp est le prénom en minuscule sans accent
                $hashedPassword = $this->hasher->hashPassword($newUser,formatString($newUser->getFirstname()));
                $newUser->setPassword($hashedPassword);

                /*****Ajout du role *****/
                $newUser->setRole($allRole[0]);

                /*****Ajout du contrat *****/
                // On ajoute de 1 à 3 contrats au hasard pour chaque user
                for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                    $randomContract = $allContract[rand(0, count($allContract) -1)];
                    $newUser->addContract($randomContract);
                }

                /*****Ajout des fiches de paie*****/
                // On ajoute de 1 à 24 fiche de paie au hasard pour chaque user
                for ($g = 1; $g <= mt_rand(1, 24); $g++) {
                    $randomPayslip = $allPayslip[rand(0, count($allPayslip) -1)];
                    $newUser->addPayslip($randomPayslip);
                }

                /*****Ajout des documentations*****/
                // On ajoute de 1 à 24 documents au hasard pour chaque user
                for ($g = 1; $g <= mt_rand(1, 24); $g++) {
                    $randomDocumentation = $allDocumentation[rand(0, count($allDocumentation) -1)];
                    $newUser->addDocumentation($randomDocumentation);
                }
                // CREATION JOB

                /*****Ajout de l'emploi*****/


                /*****Ajout du département*****/
                $newUser->setDepartement($newDepartement);

                /*****Ajout du planning prévu *****/
                // On ajoute de 5 plannings au hasard pour chaque user

                foreach ($allPlanned as $planned)
                {
                    $newUser->addPlannedWorkDay($planned);
                }

                /*****Ajout du planning effectué *****/
                // On ajoute de 5 plannings au hasard pour chaque user
                for ($g = 0; $g <= 5; $g++) {
                    $newUser->addEffectiveWorkDay($allEffective[$g]);
                }
                $manager->persist($newUser);
            }
        }

        /*****Ajout de l'emploi MANAGER [DEPARTEMENT RH]*****/
        // Je crée un département
        $newDepartementRH =  new Departement();
        $newDepartementRH->setName('Ressources Humaines')
            ->setCreatedAt(new DateTime('now'));
        $manager->persist($newDepartementRH);


        $newJobManagerRH = new Job();
        $newJobManagerRH->setName('Manager');
        $newJobManagerRH->setGrade($this->grade[0]);
        $newJobManagerRH->setCreatedAt(new DateTime('now'));
        $manager->persist($newJobManagerRH);

        $newDepartementJobRH = new DepartementJob;
        $newDepartementJobRH->setJob($newJobManagerRH);
        $newDepartementJobRH->setDepartement($newDepartementRH);
        $allDepartementJobRH[] = $newDepartementJobRH;
        $manager->persist($newDepartementJobRH);

        $newUserManagerRH = new User();
        $newUserManagerRH->setFirstname($faker->firstName());
        $newUserManagerRH->setLastname($faker->lastName());
        $newUserManagerRH->setPicture('https://boredhumans.b-cdn.net/faces2/'. rand(100, 400).'.jpg');
        $newUserManagerRH->setEmail($faker->freeEmail());
        $newUserManagerRH->setEmailpro(formatString(($newUserManagerRH->getFirstname() .'.'. $newUserManagerRH->getLastname() . '@oclock.io')));
        $newUserManagerRH->setPhonenumber($faker->mobileNumber());
        $newUserManagerRH->setPhonenumberpro($faker->phoneNumber());
        $newUserManagerRH->setAddress($faker->streetAddress());
        $newUserManagerRH->setZipcode($faker->postcode());
        $newUserManagerRH->setCity($faker->city());
        $newUserManagerRH->setRib($faker->iban('FR'));
        $newUserManagerRH->setStatus(true);
        $newUserManagerRH->setDateOfBirth($faker->dateTimeBetween('-60years', '-20years'));
        $newUserManagerRH->setCreatedAt(new DateTime('now'));

        $newUserManagerRH->setJob($newJobManager);

        /*****Ajout du département*****/
        $newUserManagerRH->setDepartement($newDepartementRH);

        //hashage du password
        // le mdp est le prénom en minuscule sans accent
        $hashedPassword = $this->hasher->hashPassword($newUserManagerRH,formatString($newUserManagerRH->getFirstname()));
        $newUserManagerRH->setPassword($hashedPassword);

        /*****Ajout du role *****/
        $newUserManagerRH->setRole($allRole[1]);

        /*****Ajout du contrat *****/
        // On ajoute de 1 à 3 contrats au hasard pour chaque user
        for ($g = 1; $g <= mt_rand(1, 3); $g++) {
            $randomContract = $allContract[rand(0, count($allContract) -1)];
            $newUserManagerRH->addContract($randomContract);
        }

        /*****Ajout des fiches de paie*****/
        // On ajoute de 1 à 24 fiche de paie au hasard pour chaque user
        for ($g = 1; $g <= mt_rand(1, 24); $g++) {
            $randomPayslip = $allPayslip[rand(0, count($allPayslip) -1)];
            $newUserManagerRH->addPayslip($randomPayslip);
        }

        /*****Ajout des documentations*****/
        // On ajoute de 1 à 24 documents au hasard pour chaque user
        for ($g = 1; $g <= mt_rand(1, 24); $g++) {
            $randomDocumentation = $allDocumentation[rand(0, count($allDocumentation) -1)];
            $newUserManagerRH->addDocumentation($randomDocumentation);
        }

        /*****Ajout du planning prévu *****/
        // On ajoute de 5 plannings au hasard pour chaque user

        foreach ($allPlanned as $planned)
        {
            $newUserManagerRH->addPlannedWorkDay($planned);
        }

        /*****Ajout du planning effectué *****/
        // On ajoute de 5 plannings au hasard pour chaque user
        for ($g = 0; $g <= 5; $g++) {
            $newUserManagerRH->addEffectiveWorkDay($allEffective[$g]);
        }
        $manager->persist($newUserManagerRH);

        /// CREATION EMPLOYE SERVICE RH ///

        foreach ($this->departementRH as $jobRH)
        {
            $newJobRH = new Job();
            $newJobRH->setName($jobRH);
            $newJobRH->setGrade($this->grade[0]);
            $newJobRH->setCreatedAt(new DateTime('now'));
            $newDepartementJobRH = new DepartementJob;
            $newDepartementJobRH->setJob($newJobRH);
            $newDepartementJobRH->setDepartement($newDepartementRH);
            $allDepartementJobRH[] = $newDepartementJobRH;
            $manager->persist($newDepartementJobRH);
            $manager->persist($newJobRH);
        }

        foreach ($this->departementRH as $job)
        {

            // je crée un employé pour ce département
            $newUserManager = new User();
            $newUserManager->setFirstname($faker->firstName());
            $newUserManager->setLastname($faker->lastName());
            $newUserManager->setPicture('https://boredhumans.b-cdn.net/faces2/'. rand(100, 400).'.jpg');
            $newUserManager->setEmail($faker->freeEmail());
            $newUserManager->setEmailpro(formatString(($newUserManager->getFirstname() .'.'. $newUserManager->getLastname() . '@oclock.io')));
            $newUserManager->setPhonenumber($faker->mobileNumber());
            $newUserManager->setPhonenumberpro($faker->phoneNumber());
            $newUserManager->setAddress($faker->streetAddress());
            $newUserManager->setZipcode($faker->postcode());
            $newUserManager->setCity($faker->city());
            $newUserManager->setRib($faker->iban('FR'));
            $newUserManager->setStatus(true);
            $newUserManager->setDateOfBirth($faker->dateTimeBetween('-60years', '-20years'));
            $newUserManager->setCreatedAt(new DateTime('now'));

            $newUserManager->setJob($newJobRH);

            /*****Ajout du département*****/
            $newUserManager->setDepartement($newDepartementRH);

            //hashage du password
            // le mdp est le prénom en minuscule sans accent
            $hashedPassword = $this->hasher->hashPassword($newUserManager,formatString($newUserManager->getFirstname()));
            $newUserManager->setPassword($hashedPassword);

            /*****Ajout du role *****/
            $newUserManager->setRole($allRole[1]);

            /*****Ajout du contrat *****/
            // On ajoute de 1 à 3 contrats au hasard pour chaque user
            for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                $randomContract = $allContract[rand(0, count($allContract) -1)];
                $newUserManager->addContract($randomContract);
            }

            /*****Ajout des fiches de paie*****/
            // On ajoute de 1 à 24 fiche de paie au hasard pour chaque user
            for ($g = 1; $g <= mt_rand(1, 24); $g++) {
                $randomPayslip = $allPayslip[rand(0, count($allPayslip) -1)];
                $newUserManager->addPayslip($randomPayslip);
            }

            /*****Ajout des documentations*****/
            // On ajoute de 1 à 24 documents au hasard pour chaque user
            for ($g = 1; $g <= mt_rand(1, 24); $g++) {
                $randomDocumentation = $allDocumentation[rand(0, count($allDocumentation) -1)];
                $newUserManager->addDocumentation($randomDocumentation);
            }

            /*****Ajout du planning prévu *****/
            // On ajoute de 5 plannings au hasard pour chaque user

            foreach ($allPlanned as $planned)
            {
                $newUserManager->addPlannedWorkDay($planned);
            }

            /*****Ajout du planning effectué *****/
            // On ajoute de 5 plannings au hasard pour chaque user
            for ($g = 0; $g <= 5; $g++) {
                $newUserManager->addEffectiveWorkDay($allEffective[$g]);
            }
            $manager->persist($newUserManager);

            // FIN CREATION MANAGER DEPARTEMENT

            // CREATION USER RH

            foreach ($this->departementRH as $jobName)
            {
                $newUser = new User();
                $newUser->setJob($newJob);
                $newUser->setFirstname($faker->firstName());
                $newUser->setLastname($faker->lastName());
                $newUser->setPicture('https://boredhumans.b-cdn.net/faces2/'. rand(100, 400).'.jpg');
                $newUser->setEmail($faker->freeEmail());
                $newUser->setEmailpro(formatString(($newUser->getFirstname() .'.'. $newUser->getLastname() . '@oclock.io')));
                $newUser->setPhonenumber($faker->mobileNumber());
                $newUser->setPhonenumberpro($faker->phoneNumber());
                $newUser->setAddress($faker->streetAddress());
                $newUser->setZipcode($faker->postcode());
                $newUser->setCity($faker->city());
                $newUser->setRib($faker->iban('FR'));
                $newUser->setStatus(true);
                $newUser->setDateOfBirth($faker->dateTimeBetween('-60years', '-20years'));
                $newUser->setCreatedAt(new DateTime('now'));

                //hashage du password
                // le mdp est le prénom en minuscule sans accent
                $hashedPassword = $this->hasher->hashPassword($newUser,formatString($newUser->getFirstname()));
                $newUser->setPassword($hashedPassword);

                /*****Ajout du role *****/
                $newUser->setRole($allRole[0]);

                /*****Ajout du contrat *****/
                // On ajoute de 1 à 3 contrats au hasard pour chaque user
                for ($g = 1; $g <= mt_rand(1, 3); $g++) {
                    $randomContract = $allContract[rand(0, count($allContract) -1)];
                    $newUser->addContract($randomContract);
                }

                /*****Ajout des fiches de paie*****/
                // On ajoute de 1 à 24 fiche de paie au hasard pour chaque user
                for ($g = 1; $g <= mt_rand(1, 24); $g++) {
                    $randomPayslip = $allPayslip[rand(0, count($allPayslip) -1)];
                    $newUser->addPayslip($randomPayslip);
                }

                /*****Ajout des documentations*****/
                // On ajoute de 1 à 24 documents au hasard pour chaque user
                for ($g = 1; $g <= mt_rand(1, 24); $g++) {
                    $randomDocumentation = $allDocumentation[rand(0, count($allDocumentation) -1)];
                    $newUser->addDocumentation($randomDocumentation);
                }
                // CREATION JOB

                /*****Ajout de l'emploi*****/


                /*****Ajout du département*****/
                $newUser->setDepartement($newDepartement);

                /*****Ajout du planning prévu *****/
                // On ajoute de 5 plannings au hasard pour chaque user

                foreach ($allPlanned as $planned)
                {
                    $newUser->addPlannedWorkDay($planned);
                }

                /*****Ajout du planning effectué *****/
                // On ajoute de 5 plannings au hasard pour chaque user
                for ($g = 0; $g <= 5; $g++) {
                    $newUser->addEffectiveWorkDay($allEffective[$g]);
                }
                $manager->persist($newUser);
            }
        }

        $manager-> flush();
    }
    
}