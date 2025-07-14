<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: BoutiqueRepository::class)]
class Boutique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commercant $commercant = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slogan = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteName = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $backgroundColor = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $textColor = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $linkColor = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $buttonColor = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $accentColor = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $getInTouch = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCommercant(): ?Commercant
    {
        return $this->commercant;
    }

    public function setCommercant(?Commercant $commercant): static
    {
        $this->commercant = $commercant;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): static
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getSiteName(): ?string
    {
        return $this->siteName;
    }
    public function setSiteName(?string $siteName): static
    {
        $this->siteName = $siteName;
        return $this;
    }
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }
    public function setBackgroundColor(?string $backgroundColor): static
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }
    public function getTextColor(): ?string
    {
        return $this->textColor;
    }
    public function setTextColor(?string $textColor): static
    {
        $this->textColor = $textColor;
        return $this;
    }
    public function getLinkColor(): ?string
    {
        return $this->linkColor;
    }
    public function setLinkColor(?string $linkColor): static
    {
        $this->linkColor = $linkColor;
        return $this;
    }
    public function getButtonColor(): ?string
    {
        return $this->buttonColor;
    }
    public function setButtonColor(?string $buttonColor): static
    {
        $this->buttonColor = $buttonColor;
        return $this;
    }
    public function getAccentColor(): ?string
    {
        return $this->accentColor;
    }
    public function setAccentColor(?string $accentColor): static
    {
        $this->accentColor = $accentColor;
        return $this;
    }
    public function getGetInTouch(): ?string
    {
        return $this->getInTouch;
    }
    public function setGetInTouch(?string $getInTouch): static
    {
        $this->getInTouch = $getInTouch;
        return $this;
    }
}
