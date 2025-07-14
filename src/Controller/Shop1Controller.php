<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Shop1Controller extends AbstractController
{
    #[Route('/perview', name: 'perview')]
    public function perview(Request $request): Response
    {
        $primaryColor = $request->query->get('primary_color', '#111111');
        $secondaryColor = $request->query->get('secondary_color', '#6c757d');
        $backgroundColor = $request->query->get('background_color', '#f8f8f8');
        $textColor = $request->query->get('text_color', '#212529');
        $linkColor = $request->query->get('link_color', '#ce071e');
        $buttonColor = $request->query->get('button_color', '#e74c3c');
        $accentColor = $request->query->get('accent_color', '#111111');
        $siteName = $request->query->get('site_name', 'Stylish');
        $address = $request->query->get('address', 'Stylish Online Store 123 Main Street, Toulouse - France.');
        $phone = $request->query->get('phone', '(+33) 800 456 789-987');
        $email = $request->query->get('email', 'contact@yourwebsite.com');
        $getInTouch = $request->query->get('get_in_touch', "Stylish Online Store 123 Main Street, Toulouse - France.\nCall us: (+33) 800 456 789-987\ncontact@yourwebsite.com");
        $footer = $request->query->get('footer', '© Copyright Stylish 2025.');

        return $this->render('shop/shop_1/index.html.twig', [
            'primary_color' => $primaryColor,
            'secondary_color' => $secondaryColor,
            'background_color' => $backgroundColor,
            'text_color' => $textColor,
            'link_color' => $linkColor,
            'button_color' => $buttonColor,
            'accent_color' => $accentColor,
            'site_name' => $siteName,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'get_in_touch' => $getInTouch,
            'footer' => $footer,
        ]);
    }
} 