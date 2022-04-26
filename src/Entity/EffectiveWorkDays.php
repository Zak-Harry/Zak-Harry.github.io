<?php

namespace App\Entity;

use App\Repository\EffectiveWorkDaysRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EffectiveWorkDaysRepository::class)
 */
class EffectiveWorkDays
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startlog;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startlunch;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endlunch;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endlog;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $hoursworked;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="effectiveWorkDays")
     */
    private $user;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartlog(): ?\DateTimeInterface
    {
        return $this->startlog;
    }

    public function setStartlog(?\DateTimeInterface $startlog): self
    {
        $this->startlog = $startlog;

        return $this;
    }

    public function getStartlunch(): ?\DateTimeInterface
    {
        return $this->startlunch;
    }

    public function setStartlunch(?\DateTimeInterface $startlunch): self
    {
        $this->startlunch = $startlunch;

        return $this;
    }

    public function getEndlunch(): ?\DateTimeInterface
    {
        return $this->endlunch;
    }

    public function setEndlunch(?\DateTimeInterface $endlunch): self
    {
        $this->endlunch = $endlunch;

        return $this;
    }

    public function getEndlog(): ?\DateTimeInterface
    {
        return $this->endlog;
    }

    public function setEndlog(?\DateTimeInterface $endlog): self
    {
        $this->endlog = $endlog;

        return $this;
    }

    public function getHoursworked(): ?\DateTimeInterface
    {
        return $this->hoursworked;
    }

    public function setHoursworked(?\DateTimeInterface $hoursworked): self
    {
        $this->hoursworked = $hoursworked;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setEffectiveWorkDays(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getEffectiveWorkDays() !== $this) {
            $user->setEffectiveWorkDays($this);
        }

        $this->user = $user;

        return $this;
    }
}
