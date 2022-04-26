<?php

namespace App\Entity;

use App\Repository\PlannedWorkDaysRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlannedWorkDaysRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class PlannedWorkDays
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startshift;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endshift;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startlunch;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endlunch;

    /**
     * @ORM\Column(type="time", nullable=false)
     */
    private $hoursplanned;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="plannedWorkDays")
     */
    private $users;

    

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartshift(): ?\DateTimeInterface
    {
        return $this->startshift;
    }

    public function getStartshiftFR(): string
    {
        $date = $this->startshift->format('Y-m-d');
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
        $this->startshiftFR = strftime("%A %d %B %Y", strtotime($date));
        return $this->startshiftFR;
    } 

    public function setStartshift(\DateTimeInterface $startshift): self
    {
        $this->startshift = $startshift;

        return $this;
    }

    public function getEndshift(): ?\DateTimeInterface
    {
        return $this->endshift;
    }

    public function getEndshiftFR(): string
    {
    $date = $this->getEndshift()->format('Y-m-d');
    setlocale (LC_TIME, 'fr_FR.utf8','fra');
    $this->getEndshiftFR = strftime("%A %d %B %Y", strtotime($date));
    return $this->getEndshiftFR;
    }

    public function setEndshift(\DateTimeInterface $endshift): self
    {
        $this->endshift = $endshift;

        return $this;
    }

    public function getStartlunch(): ?\DateTimeInterface
    {
        return $this->startlunch;
    }

    public function getStartlunchFR(): string
    {
    $date = $this->getStartlunch()->format('Y-m-d');
    setlocale (LC_TIME, 'fr_FR.utf8','fra');
    $this->getStartlunchFR = strftime("%A %d %B %Y", strtotime($date));
    return $this->getStartlunchFR;
    }

    public function setStartlunch(\DateTimeInterface $startlunch): self
    {
        $this->startlunch = $startlunch;

        return $this;
    }

    public function getEndlunch(): ?\DateTimeInterface
    {
        return $this->endlunch;
    }

    public function getEndlunchFR(): string
    {
    $date = $this->getEndlunch()->format('Y-m-d');
    setlocale (LC_TIME, 'fr_FR.utf8','fra');
    $this->getEndlunchFR = strftime("%A %d %B %Y", strtotime($date));
    return $this->getEndlunchFR;
    }

    public function setEndlunch(\DateTimeInterface $endlunch): self
    {
        $this->endlunch = $endlunch;

        return $this;
    }

    public function getHoursplanned(): ?\DateTimeInterface
    {
        return $this->hoursplanned;
    }

    public function setHoursplanned(?\DateTimeInterface $hoursplanned): self
    {
        $this->hoursplanned = $hoursplanned;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addPlannedWorkDay($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removePlannedWorkDay($this);
        }

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setValuesOnPreUpdate(): void
    {
        // cette function sera appelle avant chaque update

        $this->updatedAt = new DateTime('now');
        // calcul de la pause déjeuner
        $lunchBreak = new DateTime($this->getStartlunch()->diff($this->getEndlunch())->format('%h:%i'));
        //calcul de la journée de travail
        $workDay = new DateTime($this->getStartshift()->diff($this->getEndshift())->format('%h:%i'));
        //on peux donc obentir maintenant le nombre d'ehures travaillée dans la journée sans la pause déjeuner
        $this->setHoursplanned(new DateTime(($workDay)->diff($lunchBreak)->format('%h:%i')));
        // et autre ...
        // eg : re-calcul du rating en fonction des critiques
        // enregistrer l'utilisateur qui a fait la modif, plus compliqué car on a pas de User ici
    }

     /**
     * @ORM\PrePersist
     */
    public function setValuesOnPrePersist(): void
    {
        // cette function sera appelle avant chaque update

        $this->createdAt = new DateTime('now');
        // calcul de la pause déjeuner
        $lunchBreak = new DateTime($this->getStartlunch()->diff($this->getEndlunch())->format('%h:%i'));
        //calcul de la journée de travail
        $workDay = new DateTime($this->getStartshift()->diff($this->getEndshift())->format('%h:%i'));
        //on peux donc obentir maintenant le nombre d'ehures travaillée dans la journée sans la pause déjeuner
        $this->setHoursplanned(new DateTime(($workDay)->diff($lunchBreak)->format('%h:%i')));
        // et autre ...
        // eg : re-calcul du rating en fonction des critiques
        // enregistrer l'utilisateur qui a fait la modif, plus compliqué car on a pas de User ici
    }

   
}
