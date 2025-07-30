<?php

namespace App\Controller;

use App\Repository\BoutiqueRepository;
use App\Entity\Utilisateur;
use App\Entity\Commercant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    private function getNiches(): array
    {
        return [
            [
                'name' => 'Fashion & Cosmetics',
                'slug' => 'fashion-cosmetics',
                'templates' => [
                    ['name' => 'Trendy Boutique', 'preview' => '/images/template1.jpg'],
                    ['name' => 'Beauty Store', 'preview' => '/images/template2.jpg'],
                ],
            ],
            [
                'name' => 'Electronics & Equipment',
                'slug' => 'electronics-equipment',
                'templates' => [
                    ['name' => 'Gadget Shop', 'preview' => '/images/template3.jpg'],
                    ['name' => 'Tech Store', 'preview' => '/images/template4.jpg'],
                ],
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'templates' => [
                    ['name' => 'Home Decor', 'preview' => '/images/template5.jpg'],
                ],
            ],
        ];
    }

    #[Route('/home', name: 'app_home')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'username' => $user ? $user->getUserIdentifier() : 'Guest',
        ]);
    }

    #[Route('/dashboard', name: 'dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(BoutiqueRepository $boutiqueRepository): Response
    {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        // Check if user is an admin first
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('admin_dashboard');
        }

        // Initialize boutiques array
        $boutiques = [];

        // For merchants, get all their boutiques
        if (in_array('ROLE_COMMERCANT', $user->getRoles())) {
            $boutiques = $boutiqueRepository->createQueryBuilder('b')
                ->join('b.commercant', 'c')
                ->join('c.utilisateur', 'u')
                ->where('u = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
        }

        // Show the main dashboard with boutiques and niches
        return $this->render('dashboard/index.html.twig', [
            'boutiques' => $boutiques,
            'niches' => $this->getNiches(),
        ]);
    }

    #[Route('/niche/{slug}', name: 'app_niche')]
    #[IsGranted('ROLE_USER')]
    public function niche(string $slug): Response
    {
        $niches = $this->getNiches();
        $niche = null;
        
        foreach ($niches as $n) {
            if ($n['slug'] === $slug) {
                $niche = $n;
                break;
            }
        }
        
        if (!$niche) {
            throw $this->createNotFoundException('Niche not found');
        }
        
        return $this->render('dashboard/niche.html.twig', [
            'niche' => $niche,
        ]);
    }
}