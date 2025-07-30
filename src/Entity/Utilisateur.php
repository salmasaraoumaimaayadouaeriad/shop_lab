<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Commercant;

#[ORM\Entity]
#[ORM\Table(name: 'utilisateur')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Full Name is required.')]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', length: 3)]
    private ?string $devise = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $pays = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Administrateur::class)]
    private Collection $administrateur;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Commercant::class)]
    private Collection $commercants;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Session::class)]
    private Collection $session;

    #[ORM\Column(type: 'datetime', nullable: true, options: ['default' => null])]
    private ?\DateTimeInterface $dateCreation = null;

    public function __construct()
    {
        $this->administrateur = new ArrayCollection();
        $this->commercants = new ArrayCollection();
        $this->session = new ArrayCollection();
        $this->dateCreation = new \DateTime();
    }

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): self
    {
        $this->devise = $devise;
        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Clear sensitive data if any
    }

    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    // Inverse relations
    public function getAdministrateur(): Collection
    {
        return $this->administrateur;
    }

    public function getCommercants(): Collection
    {
        return $this->commercants;
    }

    public function addCommercant(Commercant $commercant): self
    {
        if (!$this->commercants->contains($commercant)) {
            $this->commercants->add($commercant);
            $commercant->setUtilisateur($this);
        }
        return $this;
    }

    public function removeCommercant(Commercant $commercant): self
    {
        if ($this->commercants->removeElement($commercant)) {
            // set the owning side to null (unless already changed)
            if ($commercant->getUtilisateur() === $this) {
                $commercant->setUtilisateur(null);
            }
        }
        return $this;
    }

    public function getSession(): Collection
    {
        return $this->session;
    }
}
