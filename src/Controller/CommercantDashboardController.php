<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Repository\BoutiqueRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use App\Repository\PaiementBoutiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Produit;

#[Route('/dashboard')]
#[IsGranted('ROLE_COMMERCANT')]
class CommercantDashboardController extends AbstractController
{
    #[Route('/shop/{slug}', name: 'commercant_dashboard')]
    public function dashboard(
        string $slug,
        BoutiqueRepository $boutiqueRepository,
        CommandeRepository $commandeRepository,
        ProduitRepository $produitRepository,
        PaiementBoutiqueRepository $paiementRepository
    ) {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            throw $this->createNotFoundException('Boutique not found');
        }

        // Security: ensure the user owns this shop
        if (method_exists($boutique, 'getCommercant') && $boutique->getCommercant() && $boutique->getCommercant()->getUtilisateur() !== $user) {
            throw $this->createAccessDeniedException('Unauthorized access to this shop');
        }

        // Fetch orders for this boutique
        $orders = $commandeRepository->createQueryBuilder('c')
            ->join('c.client', 'client')
            ->where('client.boutique = :boutique')
            ->setParameter('boutique', $boutique)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();

        // Fetch products for this boutique
        $products = $produitRepository->findBy(['boutique' => $boutique]);

        // Serialize products for JS
        $productsArray = array_map(function($product) {
            return [
                'id' => $product->getId(),
                'nom' => $product->getNom(),
                'prix' => $product->getPrix(),
                'categorie' => $product->getCategorie(),
                'image' => $product->getImage(),
                'description' => $product->getDescription(),
            ];
        }, $products);

        // Fetch payments for this boutique
        $payments = $paiementRepository->findBy(['boutique' => $boutique]);

        // Pass custom_panel_json as config
        $config = $boutique->getCustomPanelJson() ?? [];

        return $this->render('commercant/dashboardCommercant.html.twig', [
            'user' => $user,
            'boutique' => $boutique,
            'orders' => $orders,
            'products' => $productsArray, // <-- use the array here
            'payments' => $payments,
            'config' => $config,
        ]);
    }

    #[Route('/shop/{slug}/settings', name: 'dashboard_shop_settings', methods: ['POST'])]
    public function updateShopSettings(
        Request $request,
        string $slug,
        BoutiqueRepository $boutiqueRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
        }
        // Security: ensure the user owns this shop
        if (method_exists($boutique, 'getCommercant') && $boutique->getCommercant() && $boutique->getCommercant()->getUtilisateur() !== $user) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['success' => false, 'message' => 'Invalid JSON'], 400);
        }
        $boutique->setCustomPanelJson($data);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    #[Route('/shop/{slug}/order/{orderId}/status', name: 'update_order_status', methods: ['POST'])]
    public function updateOrderStatus(
        Request $request,
        string $slug,
        int $orderId,
        BoutiqueRepository $boutiqueRepository,
        CommandeRepository $commandeRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
        }
        // Security check
        if (method_exists($boutique, 'getCommercant') && $boutique->getCommercant() && $boutique->getCommercant()->getUtilisateur() !== $user) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $order = $commandeRepository->find($orderId);
        if (!$order) {
            return $this->json(['success' => false, 'message' => 'Order not found'], 404);
        }
        // Verify order belongs to this boutique
        if ($order->getClient()->getBoutique() !== $boutique) {
            return $this->json(['success' => false, 'message' => 'Order does not belong to this shop'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['status'])) {
            return $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }
        $validStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($data['status'], $validStatuses)) {
            return $this->json(['success' => false, 'message' => 'Invalid status'], 400);
        }
        $order->setStatut($data['status']);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    #[Route('/shop/{slug}/product/{productId}', name: 'update_product', methods: ['PATCH'])]
    public function updateProduct(
        Request $request,
        string $slug,
        int $productId,
        BoutiqueRepository $boutiqueRepository,
        ProduitRepository $produitRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
        }
        // Security check
        if (method_exists($boutique, 'getCommercant') && $boutique->getCommercant() && $boutique->getCommercant()->getUtilisateur() !== $user) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $product = $produitRepository->find($productId);
        if (!$product || $product->getBoutique() !== $boutique) {
            return $this->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }
        foreach ($data as $field => $value) {
            switch ($field) {
                case 'nom':
                    $product->setNom($value);
                    break;
                case 'description':
                    $product->setDescription($value);
                    break;
                case 'prix':
                    $product->setPrix((float)$value);
                    break;
                case 'categorie':
                    $product->setCategorie($value);
                    break;
                case 'image':
                    $product->setImage($value);
                    break;
            }
        }
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    #[Route('/shop/{slug}/product/{productId}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(
        string $slug,
        int $productId,
        BoutiqueRepository $boutiqueRepository,
        ProduitRepository $produitRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
        }
        // Security check
        if (method_exists($boutique, 'getCommercant') && $boutique->getCommercant() && $boutique->getCommercant()->getUtilisateur() !== $user) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $product = $produitRepository->find($productId);
        if (!$product || $product->getBoutique() !== $boutique) {
            return $this->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    #[Route('/shop/{slug}/product', name: 'add_product', methods: ['POST'])]
    public function addProduct(
        Request $request,
        string $slug,
        BoutiqueRepository $boutiqueRepository,
        ProduitRepository $produitRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
        }
        if (method_exists($boutique, 'getCommercant') && $boutique->getCommercant() && $boutique->getCommercant()->getUtilisateur() !== $user) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['success' => false, 'message' => 'Invalid data'], 400);
        }
        $product = new Produit();
        $product->setBoutique($boutique);
        $product->setNom($data['nom'] ?? '');
        $product->setPrix((float)($data['prix'] ?? 0));
        $product->setCategorie($data['categorie'] ?? '');
        $product->setImage($data['image'] ?? '');
        $product->setDescription($data['description'] ?? '');
        $entityManager->persist($product);
        $entityManager->flush();
        return $this->json(['success' => true, 'id' => $product->getId()]);
    }

    #[Route('/shop/{slug}/product/upload-image', name: 'upload_product_image', methods: ['POST'])]
    public function uploadProductImage(
        Request $request,
        string $slug,
        BoutiqueRepository $boutiqueRepository
    ): Response {
        $user = $this->getUser();
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        if (!$boutique) {
            return $this->json(['success' => false, 'message' => 'Boutique not found'], 404);
        }
        if (method_exists($boutique, 'getCommercant') && $boutique->getCommercant() && $boutique->getCommercant()->getUtilisateur() !== $user) {
            return $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $file = $request->files->get('image');
        if (!$file) {
            return $this->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            return $this->json(['success' => false, 'message' => 'Invalid file type'], 400);
        }
        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/products';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = uniqid('prod_') . '.' . $file->guessExtension();
        $file->move($uploadDir, $filename);
        $publicUrl = '/uploads/products/' . $filename;
        return $this->json(['success' => true, 'url' => $publicUrl]);
    }
}
