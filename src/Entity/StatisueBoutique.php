<?php

namespace App\Entity;

use App\Repository\StatisueBoutiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisueBoutiqueRepository::class)]
class StatisueBoutique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Boutique $boutique = null;

    #[ORM\Column]
    private ?int $nbVisite = null;

    #[ORM\Column]
    private ?float $revenus = null;

    #[ORM\Column]
    private array $produitPopulaire = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getBoutique(): ?Boutique
    {
        return $this->boutique;
    }

    public function setBoutique(?Boutique $boutique): static
    {
        $this->boutique = $boutique;

        return $this;
    }

    public function getNbVisite(): ?int
    {
        return $this->nbVisite;
    }

    public function setNbVisite(int $nbVisite): static
    {
        $this->nbVisite = $nbVisite;

        return $this;
    }

    public function getRevenus(): ?float
    {
        return $this->revenus;
    }

    public function setRevenus(float $revenus): static
    {
        $this->revenus = $revenus;

        return $this;
    }

    public function getProduitPopulaire(): array
    {
        return $this->produitPopulaire;
    }

    public function setProduitPopulaire(array $produitPopulaire): static
    {
        $this->produitPopulaire = $produitPopulaire;

        return $this;
    }
}
