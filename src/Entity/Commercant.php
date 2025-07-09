<?php

namespace App\Entity;

use App\Repository\CommercantRepository;
use Doctrine\ORM\Mapping as ORM;

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
}
