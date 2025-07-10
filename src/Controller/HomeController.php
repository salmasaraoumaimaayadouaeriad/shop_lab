<?php

namespace App\Controller;

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
            'username' => $user ? $user->getNom() : 'Guest',
        ]);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(): Response
    {
        // Example list of niches and templates
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
        return $this->render('dashboard/index.html.twig', [
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