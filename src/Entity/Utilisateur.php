<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 3)]
    private ?string $devise = null;

    #[ORM\Column(length: 100)]
    private ?string $pays = null;

    #[ORM\Column(length: 50)]
    private ?string $role = null;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'utilisateur')]
    private Collection $session;

    /**
     * @var Collection<int, Administrateur>
     */
    #[ORM\OneToMany(targetEntity: Administrateur::class, mappedBy: 'utilisateur')]
    private Collection $administrateur;

    /**
     * @var Collection<int, Commercant>
     */
    #[ORM\OneToMany(targetEntity: Commercant::class, mappedBy: 'utilisateur')]
    private Collection $comercant;

    public function __construct()
    {
        $this->session = new ArrayCollection();
        $this->administrateur = new ArrayCollection();
        $this->comercant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): static
    {
        $this->devise = $devise;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSession(): Collection
    {
        return $this->session;
    }

    public function addSession(Session $session): static
    {
        if (!$this->session->contains($session)) {
            $this->session->add($session);
            $session->setUtilisateur($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->session->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getUtilisateur() === $this) {
                $session->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Administrateur>
     */
    public function getAdministrateur(): Collection
    {
        return $this->administrateur;
    }

    public function addAdministrateur(Administrateur $administrateur): static
    {
        if (!$this->administrateur->contains($administrateur)) {
            $this->administrateur->add($administrateur);
            $administrateur->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdministrateur(Administrateur $administrateur): static
    {
        if ($this->administrateur->removeElement($administrateur)) {
            // set the owning side to null (unless already changed)
            if ($administrateur->getUtilisateur() === $this) {
                $administrateur->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commercant>
     */
    public function getComercant(): Collection
    {
        return $this->comercant;
    }

    public function addComercant(Commercant $comercant): static
    {
        if (!$this->comercant->contains($comercant)) {
            $this->comercant->add($comercant);
            $comercant->setUtilisateur($this);
        }

        return $this;
    }

    public function removeComercant(Commercant $comercant): static
    {
        if ($this->comercant->removeElement($comercant)) {
            // set the owning side to null (unless already changed)
            if ($comercant->getUtilisateur() === $this) {
                $comercant->setUtilisateur(null);
            }
        }

        return $this;
    }
}
