<?php

namespace App\Controller;

use App\Entity\Commercant;
use App\Entity\Boutique;
use App\Entity\Subscription;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class SubscriptionController extends AbstractController
{
    private $stripeSecretKey;

    public function __construct(string $stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;
        Stripe::setApiKey($stripeSecretKey);
    }

    #[Route('/subscription', name: 'subscription_index')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('subscription/index.html.twig', [
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ]);
    }

    #[Route('/subscription/create-payment-intent', name: 'subscription_create_payment_intent', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createPaymentIntent(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $plan = $data['plan'] ?? null;

            if (!$plan) {
                return new JsonResponse(['message' => 'Plan is required'], 400);
            }

            // Define plan prices (in cents)
            $planPrices = [
                'standard' => 1299, // $12.99
                'premium' => 1899, // $18.99
                'basic' => 0
            ];

            if (!isset($planPrices[$plan])) {
                return new JsonResponse(['message' => 'Invalid plan selected'], 400);
            }

            if ($plan === 'basic') {
                return new JsonResponse(['clientSecret' => null, 'message' => 'No payment required for Basic plan']);
            }

            $paymentIntent = PaymentIntent::create([
                'amount' => $planPrices[$plan],
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'plan' => $plan,
                    'user_id' => $this->getUser()->getId()
                ]
            ]);

            return new JsonResponse([
                'clientSecret' => $paymentIntent->client_secret,
                'message' => 'Payment intent created successfully'
            ]);

        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Failed to create payment intent: ' . $e->getMessage()], 400);
        }
    }

    #[Route('/subscription/process', name: 'subscription_process', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function process(
        Request $request, 
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        
        try {
            // Get form data
            $formData = $request->request->all();
            
            // Validate required fields
            $requiredFields = [
                'selected_plan', 'first_name', 'last_name', 'email', 'phone',
                'business_name', 'business_type', 'address', 'city', 'postal_code',
                'country', 'shop_name', 'shop_description', 'niche', 'template'
            ];
            
            foreach ($requiredFields as $field) {
                if (empty($formData[$field])) {
                    throw new \InvalidArgumentException("Field {$field} is required");
                }
            }

            // Validate payment for non-basic plans
            if ($formData['selected_plan'] !== 'basic') {
                $paymentMethod = $formData['payment_method'] ?? null;
                if (!$paymentMethod) {
                    throw new \InvalidArgumentException("Payment method is required for paid plans");
                }

                // Verify payment intent status
                $paymentIntentId = $formData['payment_intent'] ?? null;
                if ($paymentIntentId) {
                    $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
                    if ($paymentIntent->status !== 'succeeded') {
                        throw new \InvalidArgumentException("Payment verification failed");
                    }
                } else {
                    throw new \InvalidArgumentException("Payment intent ID is required");
                }
            }
            
            // Create Commercant entity
            $commercant = new Commercant();
            $commercant->setNom($formData['first_name'] . ' ' . $formData['last_name']);
            $commercant->setEmail($formData['email']);
            $commercant->setTelephone($formData['phone']);
            $commercant->setAdresse($formData['address']);
            $commercant->setVille($formData['city']);
            $commercant->setCodePostal($formData['postal_code']);
            $commercant->setPays($formData['country']);
            $commercant->setNomEntreprise($formData['business_name']);
            $commercant->setTypeEntreprise($formData['business_type']);
            $commercant->setSiteWeb($formData['website'] ?? null);
            $commercant->setUtilisateur($user);
            $commercant->setDateCreation(new \DateTime());
            
            // Validate Commercant
            $errors = $validator->validate($commercant);
            if (count($errors) > 0) {
                throw new \InvalidArgumentException('Validation failed for merchant data');
            }
            
            $entityManager->persist($commercant);
            
            // Create Boutique entity
            $boutique = new Boutique();
            $boutique->setNom($formData['shop_name']);
            $boutique->setDescription($formData['shop_description']);
            $boutique->setNiche($formData['niche']);
            $boutique->setTemplate($formData['template']);
            $boutique->setCommercant($commercant);
            $boutique->setDateCreation(new \DateTime());
            
            // Generate shop URL
            $customDomain = $formData['custom_domain'] ?? null;
            if ($customDomain) {
                $boutique->setDomainePersonnalise($customDomain);
                $boutique->setUrl($customDomain);
            } else {
                $shopSlug = $this->generateShopSlug($formData['shop_name']);
                $boutique->setUrl($shopSlug . '.shoplab.com');
            }
            
            // Set initial status
            $boutique->setStatut('en_cours');
            
            // Validate Boutique
            $errors = $validator->validate($boutique);
            if (count($errors) > 0) {
                throw new \InvalidArgumentException('Validation failed for shop data');
            }
            
            $entityManager->persist($boutique);
            
            // Create Subscription entity
            $subscription = new Subscription();
            $subscription->setCommercant($commercant);
            $subscription->setPlan($formData['selected_plan']);
            $subscription->setStatut('active');
            $subscription->setDateDebut(new \DateTime());
            $subscription->setDateFin(new \DateTime('+1 month'));
            if ($formData['selected_plan'] !== 'basic' && $paymentIntentId) {
                $subscription->setPaymentIntentId($paymentIntentId);
            }
            
            $entityManager->persist($subscription);
            
            // Save all entities
            $entityManager->flush();
            
            // Send confirmation email (implement this)
            // $this->sendConfirmationEmail($commercant, $boutique);
            
            // Return success response
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'shop_url' => $boutique->getUrl(),
                    'message' => 'Subscription created successfully'
                ]);
            }
            
            $this->addFlash('success', 'Your subscription has been created successfully!');
            return $this->redirectToRoute('dashboard');
            
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 400);
            }
            
            $this->addFlash('error', 'An error occurred while processing your subscription. Please try again.');
            return $this->redirectToRoute('subscription_index');
        }
    }
    
    private function generateShopSlug(string $shopName): string
    {
        $slug = strtolower(trim($shopName));
        $slug = preg_replace('/[^a-z0-9-]/', '', str_replace(' ', '-', $slug));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        if (empty($slug)) {
            $slug = 'shop-' . uniqid();
        }
        
        return $slug;
    }
    
    #[Route('/subscription/check-domain', name: 'subscription_check_domain', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function checkDomain(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $domain = $request->request->get('domain');
        
        if (empty($domain)) {
            return new JsonResponse(['available' => false, 'message' => 'Domain is required']);
        }
        
        $existingBoutique = $entityManager->getRepository(Boutique::class)
            ->findOneBy(['url' => $domain]);
        
        if ($existingBoutique) {
            return new JsonResponse(['available' => false, 'message' => 'Domain is already taken']);
        }
        
        return new JsonResponse(['available' => true, 'message' => 'Domain is available']);
    }
}