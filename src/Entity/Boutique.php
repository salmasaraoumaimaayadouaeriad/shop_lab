<?php

namespace App\Entity;

use App\Repository\BoutiqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\BoutiqueSubscription;

#[ORM\Entity(repositoryClass: BoutiqueRepository::class)]
class Boutique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'boutiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commercant $commercant = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $niche = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $template = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $domainePersonnalise = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $visites = 0;

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

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $customTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customDescription = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $customImage = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $customCategories = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $customPanelJson = null;

    #[ORM\OneToMany(mappedBy: 'boutique', targetEntity: BoutiqueSubscription::class)]
    private Collection $subscriptions;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->statut = 'en_cours';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getNiche(): ?string
    {
        return $this->niche;
    }

    public function setNiche(?string $niche): static
    {
        $this->niche = $niche;
        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): static
    {
        $this->template = $template;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function getDomainePersonnalise(): ?string
    {
        return $this->domainePersonnalise;
    }

    public function setDomainePersonnalise(?string $domainePersonnalise): static
    {
        $this->domainePersonnalise = $domainePersonnalise;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getVisites(): int
    {
        return $this->visites;
    }

    public function setVisites(int $visites): static
    {
        $this->visites = $visites;
        return $this;
    }

    public function incrementVisites(): static
    {
        $this->visites++;
        return $this;
    }

    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
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

    public function getCustomTitle(): ?string
    {
        return $this->customTitle;
    }

    public function setCustomTitle(?string $customTitle): static
    {
        $this->customTitle = $customTitle;
        return $this;
    }

    public function getCustomDescription(): ?string
    {
        return $this->customDescription;
    }

    public function setCustomDescription(?string $customDescription): static
    {
        $this->customDescription = $customDescription;
        return $this;
    }

    public function getCustomImage(): ?string
    {
        return $this->customImage;
    }

    public function setCustomImage(?string $customImage): static
    {
        $this->customImage = $customImage;
        return $this;
    }

    public function getCustomCategories(): ?array
    {
        return $this->customCategories;
    }

    public function setCustomCategories(?array $customCategories): static
    {
        $this->customCategories = $customCategories;
        return $this;
    }

    public function getCustomPanelJson(): ?array
    {
        return $this->customPanelJson;
    }

    public function setCustomPanelJson(?array $customPanelJson): static
    {
        $this->customPanelJson = $customPanelJson;
        return $this;
    }
}