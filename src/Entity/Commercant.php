<?php

namespace App\Entity;

use App\Repository\CommercantRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CommercantRepository::class)]
class Commercant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commercant')] // ✅ Match this to the property name in Utilisateur
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne]
    private ?Boutique $boutiquePrincipale = null;

    #[ORM\OneToMany(mappedBy: 'commercant', targetEntity: Boutique::class, orphanRemoval: true)]
    private Collection $boutiques;

    public function __construct()
    {
        $this->boutiques = new ArrayCollection();
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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getBoutiquePrincipale(): ?Boutique
    {
        return $this->boutiquePrincipale;
    }

    public function setBoutiquePrincipale(?Boutique $boutiquePrincipale): static
    {
        $this->boutiquePrincipale = $boutiquePrincipale;
        return $this;
    }

    public function getBoutiques(): Collection
    {
        return $this->boutiques;
    }

    public function addBoutique(Boutique $boutique): static
    {
        if (!$this->boutiques->contains($boutique)) {
            $this->boutiques[] = $boutique;
            $boutique->setCommercant($this);
        }
        return $this;
    }

    public function removeBoutique(Boutique $boutique): static
    {
        if ($this->boutiques->removeElement($boutique)) {
            // set the owning side to null (unless already changed)
            if ($boutique->getCommercant() === $this) {
                $boutique->setCommercant(null);
            }
        }
        return $this;
    }
}
