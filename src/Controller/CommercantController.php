<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommercantController extends AbstractController
{
    #[Route('/commercant', name: 'app_commercant')]
    public function index(): Response
    {
        return $this->render('commercant/index.html.twig', [
            'controller_name' => 'CommercantController',
        ]);
    }
}
