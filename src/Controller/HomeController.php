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

        // Get all Commercant entities for this user
        $commercants = $user->getCommercant();

        // Get all boutiques for these commercants
        $boutiques = [];
        foreach ($commercants as $commercant) {
            $userBoutiques = $boutiqueRepository->findBy(['commercant' => $commercant]);
            foreach ($userBoutiques as $boutique) {
                $boutiques[] = $boutique;
            }
        }

        // Restore the static niches variable for dashboard content
        $niches = [
            [
                'name' => 'Fashion & Cosmetics',
                'templates' => [
                    ['name' => 'Trendy Boutique', 'preview' => '/images/template1.jpg'],
                    ['name' => 'Beauty Store', 'preview' => '/images/template2.jpg'],
                ],
            ],
            [
                'name' => 'Electronics & Equipment',
                'templates' => [
                    ['name' => 'Gadget Shop', 'preview' => '/images/template3.jpg'],
                    ['name' => 'Tech Store', 'preview' => '/images/template4.jpg'],
                ],
            ],
            [
                'name' => 'Home & Living',
                'templates' => [
                    ['name' => 'Home Decor', 'preview' => '/images/template5.jpg'],
                ],
            ],
        ];

        // Pass both boutiques and niches to the template
        return $this->render('dashboard/index.html.twig', [
            'boutiques' => $boutiques,
            'niches' => $niches,
        ]);
    }

    #[Route('/niche/{slug}', name: 'app_niche')]
    #[IsGranted('ROLE_USER')]
    public function niche(string $slug): Response
    {
        $niches = [
            [
                'slug' => 'fashion-cosmetics',
                'name' => 'Fashion & Cosmetics',
                'templates' => [
                    ['name' => 'Trendy Boutique', 'preview' => '/images/template1.jpg'],
                    ['name' => 'Beauty Store', 'preview' => '/images/template2.jpg'],
                ],
            ],
            [
                'slug' => 'electronics-equipment',
                'name' => 'Electronics & Equipment',
                'templates' => [
                    ['name' => 'Gadget Shop', 'preview' => '/images/template3.jpg'],
                    ['name' => 'Tech Store', 'preview' => '/images/template4.jpg'],
                ],
            ],
            [
                'slug' => 'home-living',
                'name' => 'Home & Living',
                'templates' => [
                    ['name' => 'Home Decor', 'preview' => '/images/template5.jpg'],
                ],
            ],
        ];
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