<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use App\Entity\BoutiqueSubscription;
use App\Repository\BoutiqueRepository;
use App\Repository\CategorieRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\BoutiqueSubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BoutiqueRepository $boutiqueRepository,
        private CategorieRepository $categorieRepository,
        private UtilisateurRepository $utilisateurRepository,
        private BoutiqueSubscriptionRepository $subscriptionRepository
    ) {}

    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        // Get statistics for dashboard
        $stats = [
            'boutiques' => $this->boutiqueRepository->getStatistics(),
            'users' => $this->getUserStatistics(),
            'categories' => $this->categorieRepository->countTotal(),
            'subscriptions' => $this->getSubscriptionStatistics(),
        ];

        // Get recent activities
        $recentBoutiques = $this->boutiqueRepository->findBy([], ['dateCreation' => 'DESC'], 5);
        $flaggedBoutiques = $this->boutiqueRepository->findFlagged();
        $mostActiveBoutiques = $this->boutiqueRepository->findMostActive(5);

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'recent_boutiques' => $recentBoutiques,
            'flagged_boutiques' => $flaggedBoutiques,
            'most_active_boutiques' => $mostActiveBoutiques,
        ]);
    }

    // BOUTIQUE MANAGEMENT
    #[Route('/boutiques', name: 'admin_boutiques')]
    public function boutiques(Request $request): Response
    {
        $status = $request->query->get('status');
        $search = $request->query->get('search');

        if ($search) {
            $boutiques = $this->boutiqueRepository->searchForAdmin($search);
        } elseif ($status) {
            $boutiques = $this->boutiqueRepository->findByStatus($status);
        } else {
            $boutiques = $this->boutiqueRepository->findBy([], ['dateCreation' => 'DESC']);
        }

        return $this->render('admin/boutiques/index.html.twig', [
            'boutiques' => $boutiques,
            'current_status' => $status,
            'search_query' => $search,
        ]);
    }

    #[Route('/boutiques/{id}', name: 'admin_boutique_show')]
    public function showBoutique(Boutique $boutique): Response
    {
        return $this->render('admin/boutiques/show.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    #[Route('/boutiques/{id}/edit', name: 'admin_boutique_edit')]
    public function editBoutique(Boutique $boutique, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $boutique->setNom($request->request->get('nom'));
            $boutique->setDescription($request->request->get('description'));
            $boutique->setNiche($request->request->get('niche'));
            $boutique->setTemplate($request->request->get('template'));
            $boutique->setStatut($request->request->get('statut'));
            $boutique->setSlogan($request->request->get('slogan'));
        
            // Update colors
            $boutique->setBackgroundColor($request->request->get('backgroundColor'));
            $boutique->setTextColor($request->request->get('textColor'));
            $boutique->setLinkColor($request->request->get('linkColor'));
            $boutique->setButtonColor($request->request->get('buttonColor'));
        
            // Update custom content
            $boutique->setCustomTitle($request->request->get('customTitle'));
            $boutique->setCustomDescription($request->request->get('customDescription'));
        
            $this->entityManager->flush();
        
            $this->addFlash('success', 'Boutique modifiée avec succès.');
            return $this->redirectToRoute('admin_boutique_show', ['id' => $boutique->getId()]);
        }

        return $this->render('admin/boutiques/edit.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    #[Route('/boutiques/{id}/delete', name: 'admin_boutique_delete', methods: ['POST'])]
    public function deleteBoutique(Boutique $boutique): Response
    {
        $this->entityManager->remove($boutique);
        $this->entityManager->flush();

        $this->addFlash('success', 'Boutique supprimée avec succès.');
        return $this->redirectToRoute('admin_boutiques');
    }

    #[Route('/boutiques/{id}/toggle-status', name: 'admin_boutique_toggle_status', methods: ['POST'])]
    public function toggleBoutiqueStatus(Boutique $boutique): Response
    {
        $newStatus = $boutique->getStatut() === 'actif' ? 'suspendu' : 'actif';
        $boutique->setStatut($newStatus);
        $this->entityManager->flush();

        $this->addFlash('success', 'Statut de la boutique modifié avec succès.');
        return $this->redirectToRoute('admin_boutique_show', ['id' => $boutique->getId()]);
    }

    // CATEGORY MANAGEMENT
    #[Route('/categories', name: 'admin_categories')]
    public function categories(): Response
    {
        $categories = $this->categorieRepository->findBy([], ['ordre' => 'ASC']);

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/new', name: 'admin_category_new')]
    public function newCategory(Request $request): Response
    {
        $categorie = new Categorie();

        if ($request->isMethod('POST')) {
            $categorie->setNom($request->request->get('nom'));
            $categorie->setSlug($this->generateSlug($request->request->get('nom')));
            $categorie->setDescription($request->request->get('description'));
            $categorie->setIcone($request->request->get('icone'));
            $categorie->setCouleur($request->request->get('couleur'));
            $categorie->setOrdre((int) $request->request->get('ordre', 0));

            $this->entityManager->persist($categorie);
            $this->entityManager->flush();

            $this->addFlash('success', 'Catégorie créée avec succès.');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/form.html.twig', [
            'categorie' => $categorie,
            'is_edit' => false,
        ]);
    }

    #[Route('/categories/{id}/edit', name: 'admin_category_edit')]
    public function editCategory(Categorie $categorie, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $categorie->setNom($request->request->get('nom'));
            $categorie->setSlug($this->generateSlug($request->request->get('nom')));
            $categorie->setDescription($request->request->get('description'));
            $categorie->setIcone($request->request->get('icone'));
            $categorie->setCouleur($request->request->get('couleur'));
            $categorie->setOrdre((int) $request->request->get('ordre', 0));
            $categorie->setDateModification(new \DateTime());

            $this->entityManager->flush();

            $this->addFlash('success', 'Catégorie modifiée avec succès.');
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/form.html.twig', [
            'categorie' => $categorie,
            'is_edit' => true,
        ]);
    }

    #[Route('/categories/{id}/delete', name: 'admin_category_delete', methods: ['POST'])]
    public function deleteCategory(Categorie $categorie): Response
    {
        $this->entityManager->remove($categorie);
        $this->entityManager->flush();

        $this->addFlash('success', 'Catégorie supprimée avec succès.');
        return $this->redirectToRoute('admin_categories');
    }

    #[Route('/categories/{id}/toggle-status', name: 'admin_category_toggle_status', methods: ['POST'])]
    public function toggleCategoryStatus(Categorie $categorie, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $categorie->setActif($data['active']);
        $categorie->setDateModification(new \DateTime());
        
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/categories/{id}/move/{direction}', name: 'admin_category_move', methods: ['POST'])]
    public function moveCategory(Categorie $categorie, string $direction): Response
    {
        $currentOrder = $categorie->getOrdre();
        
        if ($direction === 'up') {
            $categorie->setOrdre($currentOrder - 1);
        } else {
            $categorie->setOrdre($currentOrder + 1);
        }
        
        $categorie->setDateModification(new \DateTime());
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }

    // USER MANAGEMENT
    #[Route('/users', name: 'admin_users')]
    public function users(): Response
    {
        $users = $this->utilisateurRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}', name: 'admin_user_show')]
    public function showUser(Utilisateur $user): Response
    {
        return $this->render('admin/users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/users/new', name: 'admin_user_new')]
    public function newUser(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $user = new Utilisateur();
            $user->setNom($request->request->get('nom'));
            $user->setEmail($request->request->get('email'));
            $user->setPassword(password_hash($request->request->get('password'), PASSWORD_DEFAULT));
            $user->setPays($request->request->get('pays'));
            $user->setDevise($request->request->get('devise', 'EUR'));
            $user->setRoles([$request->request->get('role', 'ROLE_USER')]);
            $user->setIsVerified(true);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès.');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/users/form.html.twig', [
            'user' => new Utilisateur(),
            'is_edit' => false,
        ]);
    }

    #[Route('/users/{id}/edit', name: 'admin_user_edit')]
    public function editUser(Utilisateur $user, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $user->setNom($request->request->get('nom'));
            $user->setEmail($request->request->get('email'));
            $user->setPays($request->request->get('pays'));
            $user->setDevise($request->request->get('devise'));
        
            $roles = ['ROLE_USER'];
            if ($request->request->get('is_admin')) {
                $roles[] = 'ROLE_ADMIN';
            }
            $user->setRoles($roles);

            $this->entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès.');
            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/users/form.html.twig', [
            'user' => $user,
            'is_edit' => true,
        ]);
    }

    #[Route('/users/{id}/suspend', name: 'admin_user_suspend', methods: ['POST'])]
    public function suspendUser(Utilisateur $user, Request $request): Response
    {
        $reason = $request->request->get('reason');
    
        // Add suspended role or update status
        $roles = $user->getRoles();
        if (!in_array('ROLE_SUSPENDED', $roles)) {
            $roles[] = 'ROLE_SUSPENDED';
            $user->setRoles($roles);
        }

        $this->entityManager->flush();

        $this->addFlash('success', 'Utilisateur suspendu avec succès.');
        return $this->redirectToRoute('admin_users');
    }

    #[Route('/users/{id}/toggle-role', name: 'admin_user_toggle_role', methods: ['POST'])]
    public function toggleUserRole(Utilisateur $user): Response
    {
        $roles = $user->getRoles();
        
        if (in_array('ROLE_ADMIN', $roles)) {
            $user->setRoles(['ROLE_USER']);
        } else {
            $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        }

        $this->entityManager->flush();

        $this->addFlash('success', 'Rôle utilisateur modifié avec succès.');
        return $this->redirectToRoute('admin_users');
    }

    // PAYMENT MANAGEMENT
    #[Route('/payments', name: 'admin_payments')]
    public function payments(): Response
    {
        $subscriptions = $this->subscriptionRepository->findBy([], ['dateCreation' => 'DESC']);

        return $this->render('admin/payments/index.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }

    #[Route('/payments/{id}/toggle-status', name: 'admin_payment_toggle_status', methods: ['POST'])]
    public function togglePaymentStatus(BoutiqueSubscription $subscription): Response
    {
        $newStatus = $subscription->getStatut() === 'active' ? 'frozen' : 'active';
        $subscription->setStatut($newStatus);
        $this->entityManager->flush();

        $this->addFlash('success', 'Statut du paiement modifié avec succès.');
        return $this->redirectToRoute('admin_payments');
    }

    // TEMPLATE MANAGEMENT
    #[Route('/templates', name: 'admin_templates')]
    public function templates(): Response
    {
        return $this->render('admin/templates/index.html.twig');
    }

    #[Route('/templates/save', name: 'admin_template_save', methods: ['POST'])]
    public function saveTemplate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // Here you would save the template to database or file system
        // For now, we'll just return success
        
        return $this->json(['success' => true, 'message' => 'Template sauvegardé avec succès']);
    }

    // ANALYTICS
    #[Route('/analytics', name: 'admin_analytics')]
    public function analytics(): Response
    {
        // Get analytics data
        $analyticsData = [
            'revenue' => $this->getRevenueData(),
            'stores' => $this->getStoreAnalytics(),
            'users' => $this->getUserAnalytics(),
            'geographic' => $this->getGeographicData(),
        ];

        return $this->render('admin/analytics/index.html.twig', [
            'analytics' => $analyticsData,
        ]);
    }

    #[Route('/analytics/data/{type}', name: 'admin_analytics_data')]
    public function getAnalyticsData(string $type): JsonResponse
    {
        switch ($type) {
            case 'revenue':
                return $this->json($this->getRevenueData());
            case 'stores':
                return $this->json($this->getStoreAnalytics());
            case 'users':
                return $this->json($this->getUserAnalytics());
            default:
                return $this->json(['error' => 'Invalid analytics type']);
        }
    }

    // BULK OPERATIONS
    #[Route('/bulk', name: 'admin_bulk')]
    public function bulkOperations(): Response
    {
        return $this->render('admin/bulk/index.html.twig');
    }

    #[Route('/bulk/execute', name: 'admin_bulk_execute', methods: ['POST'])]
    public function executeBulkOperation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $action = $data['action'];
        $type = $data['type'];
        $ids = $data['ids'];

        try {
            switch ($type) {
                case 'boutiques':
                    $this->executeBoutiqueBulkAction($action, $ids);
                    break;
                case 'users':
                    $this->executeUserBulkAction($action, $ids);
                    break;
                case 'categories':
                    $this->executeCategoryBulkAction($action, $ids);
                    break;
                case 'payments':
                    $this->executePaymentBulkAction($action, $ids);
                    break;
                default:
                    throw new \InvalidArgumentException('Invalid bulk operation type');
            }

            return $this->json(['success' => true, 'message' => 'Opération exécutée avec succès']);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // EMAIL MANAGEMENT
    #[Route('/emails', name: 'admin_emails')]
    public function emails(): Response
    {
        return $this->render('admin/emails/index.html.twig');
    }

    #[Route('/emails/send', name: 'admin_email_send', methods: ['POST'])]
    public function sendEmail(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // Here you would implement actual email sending logic
        // For now, we'll simulate the process
        
        $recipients = $this->getEmailRecipients($data['recipients']);
        
        // Simulate email sending
        foreach ($recipients as $recipient) {
            // Send email to $recipient
            // You would use Symfony Mailer here
        }

        return $this->json([
            'success' => true, 
            'message' => 'Email envoyé à ' . count($recipients) . ' destinataires'
        ]);
    }

    // HELPER METHODS
    private function getUserStatistics(): array
    {
        $qb = $this->utilisateurRepository->createQueryBuilder('u');
        
        return [
            'total' => $qb->select('COUNT(u.id)')->getQuery()->getSingleScalarResult(),
            'verified' => $qb->select('COUNT(u.id)')->andWhere('u.isVerified = :verified')->setParameter('verified', true)->getQuery()->getSingleScalarResult(),
        ];
    }

    private function getSubscriptionStatistics(): array
    {
        $qb = $this->subscriptionRepository->createQueryBuilder('s');
        
        return [
            'total' => $qb->select('COUNT(s.id)')->getQuery()->getSingleScalarResult(),
            'active' => $qb->select('COUNT(s.id)')->andWhere('s.statut = :status')->setParameter('status', 'active')->getQuery()->getSingleScalarResult(),
        ];
    }

    private function generateSlug(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    // HELPER METHODS FOR ANALYTICS
    private function getRevenueData(): array
    {
        // This would typically query your database for actual revenue data
        return [
            'monthly' => [1200, 1900, 3000, 5000, 2000, 3000, 4500, 6000, 7500, 8200, 9100, 12450],
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'total' => 12450,
            'growth' => 15.3
        ];
    }

    private function getStoreAnalytics(): array
    {
        $qb = $this->boutiqueRepository->createQueryBuilder('b');
        
        return [
            'total' => $qb->select('COUNT(b.id)')->getQuery()->getSingleScalarResult(),
            'active' => $qb->select('COUNT(b.id)')->andWhere('b.statut = :status')->setParameter('status', 'actif')->getQuery()->getSingleScalarResult(),
            'new_this_month' => 47, // This would be calculated from actual data
            'by_template' => [
                'ecommerce' => 45,
                'portfolio' => 23,
                'restaurant' => 18,
                'business' => 12
            ]
        ];
    }

    private function getUserAnalytics(): array
    {
        $qb = $this->utilisateurRepository->createQueryBuilder('u');
        
        return [
            'total' => $qb->select('COUNT(u.id)')->getQuery()->getSingleScalarResult(),
            'verified' => $qb->select('COUNT(u.id)')->andWhere('u.isVerified = :verified')->setParameter('verified', true)->getQuery()->getSingleScalarResult(),
            'active' => 1234, // This would be calculated based on recent activity
            'growth' => 12.5
        ];
    }

    private function getGeographicData(): array
    {
        // This would query actual geographic distribution
        return [
            'countries' => ['France', 'Belgique', 'Suisse', 'Canada', 'Maroc', 'Tunisie'],
            'counts' => [450, 89, 67, 45, 32, 28]
        ];
    }

    // BULK OPERATION HELPERS
    private function executeBoutiqueBulkAction(string $action, array $ids): void
    {
        $boutiques = $this->boutiqueRepository->findBy(['id' => $ids]);
        
        foreach ($boutiques as $boutique) {
            switch ($action) {
                case 'activate':
                    $boutique->setStatut('actif');
                    break;
                case 'suspend':
                    $boutique->setStatut('suspendu');
                    break;
                case 'delete':
                    $this->entityManager->remove($boutique);
                    break;
            }
        }
        
        $this->entityManager->flush();
    }

    private function executeUserBulkAction(string $action, array $ids): void
    {
        $users = $this->utilisateurRepository->findBy(['id' => $ids]);
        
        foreach ($users as $user) {
            switch ($action) {
                case 'verify':
                    $user->setIsVerified(true);
                    break;
                case 'suspend':
                    $roles = $user->getRoles();
                    if (!in_array('ROLE_SUSPENDED', $roles)) {
                        $roles[] = 'ROLE_SUSPENDED';
                        $user->setRoles($roles);
                    }
                    break;
                case 'promote':
                    $roles = $user->getRoles();
                    if (!in_array('ROLE_ADMIN', $roles)) {
                        $roles[] = 'ROLE_ADMIN';
                        $user->setRoles($roles);
                    }
                    break;
            }
        }
        
        $this->entityManager->flush();
    }

    private function executeCategoryBulkAction(string $action, array $ids): void
    {
        $categories = $this->categorieRepository->findBy(['id' => $ids]);
        
        foreach ($categories as $category) {
            switch ($action) {
                case 'activate':
                    $category->setActif(true);
                    break;
                case 'deactivate':
                    $category->setActif(false);
                    break;
            }
        }
        
        $this->entityManager->flush();
    }

    private function executePaymentBulkAction(string $action, array $ids): void
    {
        $subscriptions = $this->subscriptionRepository->findBy(['id' => $ids]);
        
        foreach ($subscriptions as $subscription) {
            switch ($action) {
                case 'activate':
                    $subscription->setStatut('active');
                    break;
                case 'freeze':
                    $subscription->setStatut('frozen');
                    break;
            }
        }
        
        $this->entityManager->flush();
    }

    private function getEmailRecipients(array $recipientGroups): array
    {
        $recipients = [];
        
        foreach ($recipientGroups as $group) {
            switch ($group) {
                case 'all_users':
                    $users = $this->utilisateurRepository->findAll();
                    foreach ($users as $user) {
                        $recipients[] = $user->getEmail();
                    }
                    break;
                case 'verified_users':
                    $users = $this->utilisateurRepository->findBy(['isVerified' => true]);
                    foreach ($users as $user) {
                        $recipients[] = $user->getEmail();
                    }
                    break;
                // Add more recipient groups as needed
            }
        }
        
        return array_unique($recipients);
    }

    #[Route('/admin/fix-commercants', name: 'admin_fix_commercants')]
    public function fixCommercants(EntityManagerInterface $entityManager): Response
    {
        RegistrationController::ensureCommercantsForAllUsers($entityManager);
        return new Response('All users now have a commercant. You can remove this route now.');
    }
}
