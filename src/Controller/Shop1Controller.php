<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Boutique;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class Shop1Controller extends AbstractController
{
    private const DEFAULT_CONFIG = [
        // Colors
        'primary_color' => '#111111',
        'secondary_color' => '#6c757d',
        'background_color' => '#f8f8f8',
        'text_color' => '#212529',
        'link_color' => '#ce071e',
        'button_color' => '#e74c3c',
        'accent_color' => '#111111',
        'header_bg' => '#ffffff',
        'footer_bg' => '#f5f5f5',
        'card_bg' => '#ffffff',
        'border_color' => '#dee2e6',
        'success_color' => '#28a745',
        'warning_color' => '#ffc107',
        'danger_color' => '#dc3545',
        'info_color' => '#17a2b8',
        
        // Typography
        'font_family' => 'Inter',
        'heading_font' => 'Playfair Display',
        'font_size' => '16',
        'heading_size' => '2.5',
        'line_height' => '1.6',
        'letter_spacing' => '0',
        'font_weight' => '400',
        'heading_weight' => '700',
        
        // Layout
        'container_width' => '1200',
        'border_radius' => '8',
        'spacing' => '1',
        'header_height' => '80',
        'footer_height' => 'auto',
        'sidebar_width' => '250',
        
        // Content
        'site_name' => 'Stylish',
        'tagline' => 'Premium Online Store',
        'hero_title' => 'Stylish Shoes Collection',
        'hero_subtitle' => 'Discover the latest trends in footwear',
        'hero_button_text' => 'Shop Now',
        'hero_button_2_text' => 'Learn More',
        'discount_percentage' => '10',
        'discount_text' => '10% OFF Discount Coupons',
        'discount_description' => 'Subscribe us to get 10% OFF on all the purchases',
        'footer_text' => '© Copyright Stylish 2025.',
        
        // Contact
        'address' => 'Stylish Online Store 123 Main Street, Toulouse - France.',
        'phone' => '(+33) 800 456 789-987',
        'email' => 'contact@yourwebsite.com',
        'social_facebook' => '#',
        'social_instagram' => '#',
        'social_twitter' => '#',
        'social_youtube' => '#',
        
        // Features
        'show_search' => 'true',
        'show_cart' => 'true',
        'show_login' => 'true',
        'show_social' => 'true',
        'show_newsletter' => 'true',
        'show_breadcrumbs' => 'true',
        'enable_animations' => 'true',
        'enable_parallax' => 'true',
        'enable_lazy_loading' => 'true',
        
        // Advanced
        'custom_css' => '',
        'custom_js' => '',
        'google_analytics' => '',
        'favicon_url' => '',
        'logo_url' => '',
        'background_image' => '',
        'overlay_opacity' => '0.5',
        'blur_effect' => '0',
        'shadow_intensity' => '0.1',
    ];

    #[Route('/preview', name: 'preview')]
    public function preview(Request $request): Response
    {
        $config = [];
        
        foreach (self::DEFAULT_CONFIG as $key => $default) {
            $config[$key] = $request->query->get($key, $default);
        }
        // Add saveUrl for the create route
        $config['saveUrl'] = $this->generateUrl('api_boutique_create');
        $config['mode'] = 'create';
        return $this->render('shop/shop_1/index.html.twig', $config);
    }

    #[Route('/api/save-config', name: 'save_shop_config', methods: ['POST'])]
    public function saveConfig(Request $request): Response
    {
        $config = json_decode($request->getContent(), true);
        $request->getSession()->set('shop_config', $config);
        
        return $this->json(['status' => 'success']);
    }

    #[Route('/api/export-config', name: 'export_shop_config', methods: ['GET'])]
    public function exportConfig(Request $request): Response
    {
        $config = $request->getSession()->get('shop_config', self::DEFAULT_CONFIG);
        
        return $this->json($config);
    }

    #[Route('/boutique/{id}/edit', name: 'boutique_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function editBoutique(
        Boutique $boutique,
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response|JsonResponse {
        $user = $this->getUser();
        // Security: Only allow the owner to edit
        if ($boutique->getCommercant()->getUtilisateur() !== $user) {
            throw $this->createAccessDeniedException();
        }

        // Handle AJAX save (POST)
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return new JsonResponse(['error' => 'Invalid JSON'], 400);
            }

            $csrfToken = $request->headers->get('X-CSRF-TOKEN');
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('edit_boutique', $csrfToken))) {
                return new JsonResponse(['error' => 'Invalid CSRF token'], 403);
            }

            $boutique->setCustomPanelJson($data);
            // Optionally update name/slug if you want to allow that
            if (isset($data['nom'])) $boutique->setNom($data['nom']);
            if (isset($data['slug'])) $boutique->setSlug($data['slug']);
            $entityManager->flush();
            return new JsonResponse(['success' => true]);
        }

        // For GET: Render the preview/edit page with config
        $config = $boutique->getCustomPanelJson() ?? [];
        // Provide all expected variables with defaults
        $defaults = [
            'favicon_url' => null,
            'site_name' => 'Stylish',
            'tagline' => 'Premium Online Store',
            'primary_color' => '#111111',
            'secondary_color' => '#6c757d',
            'background_color' => '#f8f8f8',
            'text_color' => '#212529',
            'link_color' => '#ce071e',
            'button_color' => '#e74c3c',
            'accent_color' => '#111111',
            'header_bg' => '#ffffff',
            'footer_bg' => '#f5f5f5',
            'card_bg' => '#ffffff',
            'border_color' => '#dee2e6',
            'success_color' => '#28a745',
            'font_family' => 'Inter',
            'heading_font' => 'Playfair Display',
            'font_size' => '16',
            'heading_size' => '2.5',
            'line_height' => '1.6',
            'letter_spacing' => '0',
            'font_weight' => '400',
            'heading_weight' => '700',
            'container_width' => '1200',
            'border_radius' => '8',
            'spacing' => '1',
            'header_height' => '80',
            'sidebar_width' => '250',
            'overlay_opacity' => '0.5',
            'blur_effect' => '0',
            'shadow_intensity' => '0.1',
            'light_color' => '#fff',
            'dark_color' => '#000',
            'background_image' => null,
            'hero_title' => 'Stylish Shoes Collection',
            'hero_subtitle' => 'Discover the latest trends in footwear',
            'hero_button_text' => 'Shop Now',
            'hero_button_2_text' => 'Learn More',
            'logo_url' => '',
            'favicon_url' => '',
            'email' => 'contact@yourwebsite.com',
            'phone' => '(+33) 800 456 789-987',
            'address' => '123 Main Street, Toulouse - France',
            'social_facebook' => '#',
            'social_instagram' => '#',
            'social_twitter' => '#',
            'social_youtube' => '#',
            'footer_text' => '© Copyright Stylish 2025.',
            'custom_css' => '',
            'custom_js' => '',
            'show_search' => 'true',
            'show_cart' => 'true',
            'show_login' => 'true',
            'show_social' => 'true',
            'enable_animations' => 'true',
            'products' => [],
        ];
        return $this->render('shop/shop_1/index.html.twig', array_merge(
            $defaults,
            $config,
            [
                'mode' => 'edit',
                'saveUrl' => $this->generateUrl('boutique_edit', ['id' => $boutique->getId()]),
                'boutique' => $boutique,
            ]
        ));
    }

    #[Route('/shop/{slug}', name: 'public_shop')]
    public function publicShop(
        \App\Repository\BoutiqueRepository $boutiqueRepository,
        \App\Repository\ProduitRepository $produitRepository,
        string $slug
    ): Response {
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            throw $this->createNotFoundException('Shop not found');
        }
        $config = $boutique->getCustomPanelJson() ?? [];
        $defaults = [
            'favicon_url' => null,
            'site_name' => 'Stylish',
            'tagline' => 'Premium Online Store',
            'primary_color' => '#111111',
            'secondary_color' => '#6c757d',
            'background_color' => '#f8f8f8',
            'text_color' => '#212529',
            'link_color' => '#ce071e',
            'button_color' => '#e74c3c',
            'accent_color' => '#111111',
            'header_bg' => '#ffffff',
            'footer_bg' => '#f5f5f5',
            'card_bg' => '#ffffff',
            'border_color' => '#dee2e6',
            'success_color' => '#28a745',
            'font_family' => 'Inter',
            'heading_font' => 'Playfair Display',
            'font_size' => '16',
            'heading_size' => '2.5',
            'line_height' => '1.6',
            'letter_spacing' => '0',
            'font_weight' => '400',
            'heading_weight' => '700',
            'container_width' => '1200',
            'border_radius' => '8',
            'spacing' => '1',
            'header_height' => '80',
            'sidebar_width' => '250',
            'overlay_opacity' => '0.5',
            'blur_effect' => '0',
            'shadow_intensity' => '0.1',
            'light_color' => '#fff',
            'dark_color' => '#000',
            'background_image' => null,
            'hero_title' => 'Stylish Shoes Collection',
            'hero_subtitle' => 'Discover the latest trends in footwear',
            'hero_button_text' => 'Shop Now',
            'hero_button_2_text' => 'Learn More',
            'logo_url' => '',
            'favicon_url' => '',
            'email' => 'contact@yourwebsite.com',
            'phone' => '(+33) 800 456 789-987',
            'address' => '123 Main Street, Toulouse - France',
            'social_facebook' => '#',
            'social_instagram' => '#',
            'social_twitter' => '#',
            'social_youtube' => '#',
            'footer_text' => '© Copyright Stylish 2025.',
            'custom_css' => '',
            'custom_js' => '',
            'show_search' => 'true',
            'show_cart' => 'true',
            'show_login' => 'true',
            'show_social' => 'true',
            'enable_animations' => 'true',
            'products' => [],
        ];
        $products = $produitRepository->findBy(['boutique' => $boutique]);
        return $this->render('shop/shop_1/public.html.twig', array_merge(
            $defaults,
            $config,
            [
                'boutique' => $boutique,
                'products' => $products,
            ]
        ));
    }

    #[Route('/shop/{slug}/register', name: 'shop_register', methods: ['POST'])]
    public function shopRegister(
        Request $request,
        EntityManagerInterface $entityManager,
        \App\Repository\BoutiqueRepository $boutiqueRepository,
        string $slug
    ): Response {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                return $this->json(['success' => false, 'message' => 'Invalid JSON'], 400);
            }
            $required = ['firstName', 'lastName', 'email', 'password', 'street', 'city', 'postalCode', 'country'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return $this->json(['success' => false, 'message' => "Missing field: $field"], 400);
                }
            }
            $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
            if (!$boutique) {
                return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
            }
            // Check if user already exists
            $existingUser = $entityManager->getRepository(\App\Entity\Utilisateur::class)->findOneBy(['email' => $data['email']]);
            if ($existingUser) {
                return $this->json(['success' => false, 'message' => 'Email already registered'], 409);
            }
            // Create Utilisateur
            $user = new \App\Entity\Utilisateur();
            $user->setNom($data['firstName'] . ' ' . $data['lastName']);
            $user->setEmail($data['email']);
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
            $user->setRoles(['ROLE_CLIENT']);
            $user->setIsVerified(true);
            $user->setDevise('EUR'); // Default, or get from data
            $user->setPays('FR'); // Default, or get from data
            $entityManager->persist($user);
            $entityManager->flush();
            // Create Adresse
            $adresse = new \App\Entity\Adresse();
            $adresse->setUtilisateur($user);
            $adresse->setRue($data['street']);
            $adresse->setVille($data['city']);
            $adresse->setCodePostal($data['postalCode']);
            $adresse->setPay($data['country']);
            $entityManager->persist($adresse);
            $entityManager->flush();
            // Create ClientFinal
            $clientFinal = new \App\Entity\ClientFinal();
            $clientFinal->setUtilisateur($user);
            $clientFinal->setBoutique($boutique);
            $clientFinal->setAdressePrincipale($adresse);
            $entityManager->persist($clientFinal);
            $entityManager->flush();
            return $this->json(['success' => true, 'message' => 'Registration successful']);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/shop/{slug}/login', name: 'shop_login', methods: ['POST'])]
    public function shopLogin(
        Request $request,
        EntityManagerInterface $entityManager,
        \App\Repository\UtilisateurRepository $utilisateurRepository,
        string $slug
    ): Response {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data || empty($data['email']) || empty($data['password']) || empty($data['_csrf_token'])) {
                return $this->json(['success' => false, 'message' => 'Missing email, password, or CSRF token'], 400);
            }
            // Validate CSRF token
            if (!$this->isCsrfTokenValid('authenticate', $data['_csrf_token'])) {
                return $this->json(['success' => false, 'message' => 'Invalid CSRF token'], 400);
            }
            $user = $utilisateurRepository->findOneBy(['email' => $data['email']]);
            if (!$user) {
                return $this->json(['success' => false, 'message' => 'User not found'], 404);
            }
            // Check password (assuming bcrypt)
            if (!password_verify($data['password'], $user->getPassword())) {
                return $this->json(['success' => false, 'message' => 'Invalid password'], 401);
            }
            // Log in the user (set token in session for 'main' firewall)
            $token = new \Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken(
                $user,
                'main',
                $user->getRoles()
            );
            $this->container->get('security.token_storage')->setToken($token);
            $request->getSession()->set('_security_main', serialize($token));

            $roles = $user->getRoles();
            if (in_array('ROLE_COMMERCANT', $roles)) {
                // Redirect commercant to dashboard
                return $this->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => $this->generateUrl('commercant_dashboard', ['slug' => $slug]),
                    'role' => 'ROLE_COMMERCANT',
                ]);
            } elseif (in_array('ROLE_CLIENT', $roles)) {
                // For client, redirect/reload to shop page
                return $this->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => $this->generateUrl('public_shop', ['slug' => $slug]),
                    'role' => 'ROLE_CLIENT',
                ]);
            } else {
                // Other roles, fallback
                return $this->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'role' => $roles,
                ]);
            }
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/shop/{slug}/cart/checkout', name: 'shop_cart_checkout', methods: ['POST'])]
    public function cartCheckout(
        Request $request,
        EntityManagerInterface $entityManager,
        \App\Repository\BoutiqueRepository $boutiqueRepository,
        \App\Repository\ProduitRepository $produitRepository,
        \App\Repository\UtilisateurRepository $utilisateurRepository,
        \App\Repository\ClientFinalRepository $clientFinalRepository,
        string $slug
    ): Response {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data || !isset($data['cart']) || !is_array($data['cart']) || empty($data['email'])) {
                return $this->json(['success' => false, 'message' => 'Missing cart or email'], 400);
            }
            $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
            if (!$boutique) {
                return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
            }
            $user = $utilisateurRepository->findOneBy(['email' => $data['email']]);
            if (!$user) {
                return $this->json(['success' => false, 'message' => 'User not found. Please register first.'], 404);
            }
            $clientFinal = $clientFinalRepository->findOneBy(['utilisateur' => $user, 'boutique' => $boutique]);
            if (!$clientFinal) {
                return $this->json(['success' => false, 'message' => 'Client profile not found. Please register first.'], 404);
            }
            // Find or create Panier for this client and boutique
            $panier = $entityManager->getRepository(\App\Entity\Panier::class)
                ->findOneBy(['client' => $clientFinal]);
            if (!$panier) {
                $panier = new \App\Entity\Panier();
                $panier->setClient($clientFinal);
                $entityManager->persist($panier);
            }
            // For each product in cart, create or update PanierProduit
            foreach ($data['cart'] as $item) {
                if (empty($item['productId']) || empty($item['quantity'])) continue;
                $produit = $produitRepository->find($item['productId']);
                if (!$produit) continue;
                $panierProduitRepo = $entityManager->getRepository(\App\Entity\PanierProduit::class);
                $panierProduit = $panierProduitRepo->findOneBy(['panier' => $panier, 'produit' => $produit]);
                if (!$panierProduit) {
                    $panierProduit = new \App\Entity\PanierProduit();
                    $panierProduit->setPanier($panier);
                    $panierProduit->setProduit($produit);
                }
                $panierProduit->setQuantite($item['quantity']);
                $entityManager->persist($panierProduit);
            }
            // Create Commande
            $commande = new \App\Entity\Commande();
            $commande->setClient($clientFinal);
            $commande->setDate(new \DateTime());
            $commande->setStatut('pending');
            $commande->setMontant(0);
            $commande->setMethodePaiment($data['payment_method'] ?? '');
            $entityManager->persist($commande);
            $total = 0;
            foreach ($data['cart'] as $item) {
                if (empty($item['productId']) || empty($item['quantity'])) continue;
                $produit = $produitRepository->find($item['productId']);
                if (!$produit) continue;
                $ligne = new \App\Entity\LigneCommande();
                $ligne->setCommande($commande);
                $ligne->setProduit($produit);
                $ligne->setQuantite($item['quantity']);
                $ligne->setPrixUnitaire($produit->getPrix());
                $entityManager->persist($ligne);
                $total += $produit->getPrix() * $item['quantity'];
            }
            $commande->setMontant($total);
            // Create PaiementBoutique record
            $paiement = new \App\Entity\PaiementBoutique();
            $paiement->setBoutique($boutique);
            $paiement->setMontant($total);
            $paiement->setDate(new \DateTime());
            $paiement->setMethode($data['payment_method'] ?? '');
            $entityManager->persist($paiement);
            $entityManager->flush();
            return $this->json(['success' => true, 'message' => 'Order placed successfully', 'orderId' => $commande->getId()]);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/shop/{slug}/payment', name: 'shop_payment', methods: ['GET'])]
    public function paymentPage(string $slug, EntityManagerInterface $entityManager): Response
    {
        $stripePublicKey = $this->getParameter('stripe_public_key');
        $user = $this->getUser();
        $cartItems = [];
        if ($user) {
            $boutique = $entityManager->getRepository(\App\Entity\Boutique::class)->findOneBy(['slug' => $slug]);
            if ($boutique) {
                $clientFinal = $entityManager->getRepository(\App\Entity\ClientFinal::class)->findOneBy(['utilisateur' => $user, 'boutique' => $boutique]);
                if ($clientFinal) {
                    $panier = $entityManager->getRepository(\App\Entity\Panier::class)->findOneBy(['client' => $clientFinal]);
                    if ($panier) {
                        $panierProduits = $entityManager->getRepository(\App\Entity\PanierProduit::class)->findBy(['panier' => $panier]);
                        foreach ($panierProduits as $pp) {
                            $produit = $pp->getProduit();
                            $cartItems[] = [
                                'id' => $produit->getId(),
                                'name' => $produit->getNom(),
                                'price' => $produit->getPrix(),
                                'image' => $produit->getImage(),
                                'description' => $produit->getDescription(),
                                'quantity' => $pp->getQuantite(),
                            ];
                        }
                    }
                }
            }
        }
        return $this->render('shop/shop_1/payment.html.twig', [
            'slug' => $slug,
            'stripe_public_key' => $stripePublicKey,
            'cartItems' => $cartItems,
        ]);
    }

    #[Route('/shop/{slug}/cart/add', name: 'shop_cart_add', methods: ['POST'])]
    public function addToCart(
        Request $request,
        EntityManagerInterface $entityManager,
        \App\Repository\BoutiqueRepository $boutiqueRepository,
        \App\Repository\ProduitRepository $produitRepository,
        \App\Repository\UtilisateurRepository $utilisateurRepository,
        \App\Repository\ClientFinalRepository $clientFinalRepository,
        string $slug
    ): Response {
        $data = json_decode($request->getContent(), true);
        if (empty($data['email']) || empty($data['productId']) || !isset($data['quantity'])) {
            return $this->json(['success' => false, 'message' => 'Missing email, productId, or quantity'], 400);
        }
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
        }
        $user = $utilisateurRepository->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'User not found'], 404);
        }
        $clientFinal = $clientFinalRepository->findOneBy(['utilisateur' => $user, 'boutique' => $boutique]);
        if (!$clientFinal) {
            return $this->json(['success' => false, 'message' => 'Client profile not found'], 404);
        }
        $panier = $entityManager->getRepository(\App\Entity\Panier::class)
            ->findOneBy(['client' => $clientFinal]);
        if (!$panier) {
            $panier = new \App\Entity\Panier();
            $panier->setClient($clientFinal);
            $entityManager->persist($panier);
        }
        $produit = $produitRepository->find($data['productId']);
        if (!$produit) {
            return $this->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        $panierProduitRepo = $entityManager->getRepository(\App\Entity\PanierProduit::class);
        $panierProduit = $panierProduitRepo->findOneBy(['panier' => $panier, 'produit' => $produit]);
        if (!$panierProduit) {
            $panierProduit = new \App\Entity\PanierProduit();
            $panierProduit->setPanier($panier);
            $panierProduit->setProduit($produit);
        }
        $panierProduit->setQuantite($data['quantity']);
        $entityManager->persist($panierProduit);
        $entityManager->flush();
        return $this->json(['success' => true, 'message' => 'Cart updated']);
    }

    #[Route('/shop/{slug}/cart/remove', name: 'shop_cart_remove', methods: ['POST'])]
    public function removeFromCart(
        Request $request,
        EntityManagerInterface $entityManager,
        \App\Repository\BoutiqueRepository $boutiqueRepository,
        \App\Repository\ProduitRepository $produitRepository,
        \App\Repository\UtilisateurRepository $utilisateurRepository,
        \App\Repository\ClientFinalRepository $clientFinalRepository,
        string $slug
    ): Response {
        $data = json_decode($request->getContent(), true);
        if (empty($data['email']) || empty($data['productId'])) {
            return $this->json(['success' => false, 'message' => 'Missing email or productId'], 400);
        }
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
        }
        $user = $utilisateurRepository->findOneBy(['email' => $data['email']]);
        if (!$user) {
            return $this->json(['success' => false, 'message' => 'User not found'], 404);
        }
        $clientFinal = $clientFinalRepository->findOneBy(['utilisateur' => $user, 'boutique' => $boutique]);
        if (!$clientFinal) {
            return $this->json(['success' => false, 'message' => 'Client profile not found'], 404);
        }
        $panier = $entityManager->getRepository(\App\Entity\Panier::class)
            ->findOneBy(['client' => $clientFinal]);
        if (!$panier) {
            return $this->json(['success' => false, 'message' => 'Cart not found'], 404);
        }
        $produit = $produitRepository->find($data['productId']);
        if (!$produit) {
            return $this->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        $panierProduitRepo = $entityManager->getRepository(\App\Entity\PanierProduit::class);
        $panierProduit = $panierProduitRepo->findOneBy(['panier' => $panier, 'produit' => $produit]);
        if ($panierProduit) {
            $entityManager->remove($panierProduit);
            $entityManager->flush();
        }
        return $this->json(['success' => true, 'message' => 'Product removed from cart']);
    }

    #[Route('/shop/{slug}/order/place', name: 'shop_order_place', methods: ['POST'])]
    public function placeOrder(
        Request $request,
        EntityManagerInterface $entityManager,
        \App\Repository\BoutiqueRepository $boutiqueRepository,
        \App\Repository\UtilisateurRepository $utilisateurRepository,
        string $slug
    ): Response {
        try {
            $data = json_decode($request->getContent(), true);
            if (!$data || empty($data['email']) || empty($data['cart']) || empty($data['payment_method'])) {
                return $this->json(['success' => false, 'message' => 'Missing data'], 400);
            }
            $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
            if (!$boutique) {
                return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
            }
            $user = $utilisateurRepository->findOneBy(['email' => $data['email']]);
            if (!$user) {
                return $this->json(['success' => false, 'message' => 'User not found'], 404);
            }
            // Here, you would process payment, update order status, save shipping info, etc.
            // For now, just return success.
            return $this->json(['success' => true, 'message' => 'Order placed and payment processed']);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/shop/{slug}/logout', name: 'shop_logout', methods: ['POST'])]
    public function shopLogout(
        Request $request,
        string $slug
    ): Response {
        // Clear the session
        $request->getSession()->invalidate();
        
        // Clear the security token
        $this->container->get('security.token_storage')->setToken(null);
        
        return $this->json([
            'success' => true,
            'message' => 'Logged out successfully',
            'redirect' => $this->generateUrl('public_shop', ['slug' => $slug])
        ]);
    }
}