<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/perview', name: 'perview')]
    public function perview(Request $request): Response
    {
        $config = [];
        
        foreach (self::DEFAULT_CONFIG as $key => $default) {
            $config[$key] = $request->query->get($key, $default);
        }
        
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
}
