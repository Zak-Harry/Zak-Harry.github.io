<?php
namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Service\HoursPerWeek;
use Symfony\Component\Validator\Constraints\Unique;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("emailpro")
 * @method string getUserIdentifier()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $lastname = NULL;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $firstname = NULL;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $picture = NULL;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $email;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $emailpro;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=5,
     *     max=255,
     *     minMessage="Votre mot de passe doit contenir au minimum {{ limit }} caractére",
     *     maxMessage="Votre mot de passe doit contenir au maximum {{ limit }} caractére",
     *     normalizer="trim"
     * )
     */
    private string $password;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $phonenumber;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $phonenumberpro;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $address;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $city;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $zipcode;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $rib;
    /**
     * @ORM\Column(type="boolean", options = {"default" = true})
     */
    private ?bool $status = null;
    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt = null;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Role $role;
    /**
     * @ORM\OneToMany(targetEntity=Contract::class, mappedBy="user")
     */
    private Collection $contracts;
    /**
     * @ORM\ManyToMany(targetEntity=Documentation::class, inversedBy="users")
     */
    private Collection $documentations;
    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="users")
     */
    private ?Job $job;
    /**
     * @ORM\OneToMany(targetEntity=Payslip::class, mappedBy="user")
     */
    private Collection $payslips;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $dateOfBirth;
    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="users")
     */
    private ?Departement $departement;

    /**
     * @ORM\ManyToMany(targetEntity=PlannedWorkDays::class, inversedBy="users")
     */
    private Collection $plannedWorkDays;

    /**
     * @ORM\OneToMany(targetEntity=EffectiveWorkDays::class, mappedBy="user")
     */
    private Collection $effectiveWorkDays;

    public function __construct()
    {
        $this->contracts = new ArrayCollection();
        $this->documentations = new ArrayCollection();
        $this->payslips = new ArrayCollection();
        $this->plannedWorkDays = new ArrayCollection();
        $this->effectiveWorkDays = new ArrayCollection();
    }
    public function getLastname(): ?string
    {
        return $this->lastname;
    }
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }
    public function getPicture(): ?string
    {
        return $this->picture;
    }
    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;
        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function getEmailpro(): ?string
    {
        return $this->emailpro;
    }
    public function setEmailpro(string $emailpro): self
    {
        $this->emailpro = $emailpro;
        return $this;
    }
     /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }
    public function setPhonenumber(string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;
        return $this;
    }
    public function getPhonenumberpro(): ?string
    {
        return $this->phonenumberpro;
    }
    public function setPhonenumberpro(string $phonenumberpro): self
    {
        $this->phonenumberpro = $phonenumberpro;
        return $this;
    }
    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }
    public function getCity(): ?string
    {
        return $this->city;
    }
    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }
    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;
        return $this;
    }
    public function getRib(): ?string
    {
        return $this->rib;
    }
    public function setRib(string $rib): self
    {
        $this->rib = $rib;
        return $this;
    }
    public function getStatus(): ?bool
    {
        return $this->status;
    }
    public function setStatus(bool $status): self
    {
        $this->status = $status;
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

  
    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return array($this->getRole()->getRoleString());
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        return $this->emailpro;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
    
    /**
     * @return Collection<int, Contract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }
    public function addContract(Contract $contract): self
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts[] = $contract;
            $contract->setUser($this);
        }
        return $this;
    }
    public function removeContract(Contract $contract): self
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getUser() === $this) {
                $contract->setUser(null);
            }
        }
        return $this;
    }
    /**
     * @return Collection<int, Documentation>
     */
    public function getDocumentations(): Collection
    {
        return $this->documentations;
    }
    public function addDocumentation(Documentation $documentation): self
    {
        if (!$this->documentations->contains($documentation)) {
            $this->documentations[] = $documentation;
        }
        return $this;
    }
    public function removeDocumentation(Documentation $documentation): self
    {
        $this->documentations->removeElement($documentation);
        return $this;
    }
    public function getJob(): ?Job
    {
        return $this->job;
    }
    public function setJob(?Job $job): self
    {
        $this->job = $job;
        return $this;
    }
   
    /**
     * @return Collection<int, Payslip>
     */
    public function getPayslips(): Collection
    {
        return $this->payslips;
    }
    public function addPayslip(Payslip $payslip): self
    {
        if (!$this->payslips->contains($payslip)) {
            $this->payslips[] = $payslip;
            $payslip->setUser($this);
        }
        return $this;
    }
    public function removePayslip(Payslip $payslip): self
    {
        if ($this->payslips->removeElement($payslip)) {
            // set the owning side to null (unless already changed)
            if ($payslip->getUser() === $this) {
                $payslip->setUser(null);
            }
        }
        return $this;
    }
    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }
    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }
    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }
    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;
        return $this;
    }

    /**
     * @return Collection<int, PlannedWorkDays>
     */
    public function getPlannedWorkDays(): Collection
    {
        return $this->plannedWorkDays;
    }

    public function addPlannedWorkDay(PlannedWorkDays $plannedWorkDay): self
    {
        if (!$this->plannedWorkDays->contains($plannedWorkDay)) {
            $this->plannedWorkDays[] = $plannedWorkDay;
        }

        return $this;
    }

    public function removePlannedWorkDay(PlannedWorkDays $plannedWorkDay): self
    {
        $this->plannedWorkDays->removeElement($plannedWorkDay);

        return $this;
    }

    /**
     * @return Collection<int, EffectiveWorkDays>
     */
    public function getEffectiveWorkDays(): Collection
    {
        return $this->effectiveWorkDays;
    }

    public function addEffectiveWorkDay(EffectiveWorkDays $effectiveWorkDay): self
    {
        if (!$this->effectiveWorkDays->contains($effectiveWorkDay)) {
            $this->effectiveWorkDays[] = $effectiveWorkDay;
            $effectiveWorkDay->setUser($this);
        }

        return $this;
    }

    public function removeEffectiveWorkDay(EffectiveWorkDays $effectiveWorkDay): self
    {
        if ($this->effectiveWorkDays->removeElement($effectiveWorkDay)) {
            // set the owning side to null (unless already changed)
            if ($effectiveWorkDay->getUser() === $this) {
                $effectiveWorkDay->setUser(null);
            }
        }

        return $this;
    }

    public function getFullname(): string
    {
        return $this->getFirstname().' '.$this->getLastname();
    }

}