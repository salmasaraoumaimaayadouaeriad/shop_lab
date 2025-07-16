<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Boutique;
use App\Entity\Commercant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CommercantController extends AbstractController
{
    #[Route('/commercant', name: 'app_commercant')]
    public function index(): Response
    {
        return $this->render('commercant/index.html.twig', [
            'controller_name' => 'CommercantController',
        ]);
    }

    #[Route('/api/boutique/create', name: 'api_boutique_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createBoutique(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }
        // Get the first commercant for this user
        $commercant = $entityManager->getRepository(Commercant::class)->findOneBy(['utilisateur' => $user]);
        if (!$commercant) {
            return new JsonResponse(['error' => 'No commercant found for user'], 400);
        }
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }
        $boutique = new Boutique();
        $boutique->setCommercant($commercant);
        $boutique->setCustomPanelJson($data);
        // Optionally set a name/slug or other required fields
        $boutique->setNom($data['nom'] ?? 'My Boutique');
        $boutique->setSlug($data['slug'] ?? uniqid('boutique_'));
        $entityManager->persist($boutique);
        $entityManager->flush();
        return new JsonResponse([
            'success' => true,
            'boutique_id' => $boutique->getId(),
        ]);
    }
}
