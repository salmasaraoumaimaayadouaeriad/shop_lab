<?php

namespace App\Controller;

use App\Entity\Commercant;
use App\Entity\Boutique;
use App\Entity\BoutiqueSubscription;
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

            /** @var \App\Entity\Utilisateur $user */
            $user = $this->getUser();

            $paymentIntent = PaymentIntent::create([
                'amount' => $planPrices[$plan],
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'plan' => $plan,
                    'user_id' => $user->getId()
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
    ): JsonResponse {
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        // Add logging for debugging
        error_log('Subscription process started for user: ' . $user->getId());
        
        try {
            // Get form data
            $formData = $request->request->all();
            error_log('Form data received: ' . json_encode(array_keys($formData)));
            
            // Validate required fields
            $requiredFields = [
                'selected_plan', 'first_name', 'last_name', 'email', 'phone',
                'business_name', 'business_type', 'address', 'city', 'postal_code',
                'country', 'shop_name', 'shop_description', 'niche', 'template'
            ];
            
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($formData[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                error_log('Missing required fields: ' . implode(', ', $missingFields));
                throw new \InvalidArgumentException("Missing required fields: " . implode(', ', $missingFields));
            }

            // Validate payment for non-basic plans
            if ($formData['selected_plan'] !== 'basic') {
                $paymentMethod = $formData['payment_method'] ?? null;
                if (!$paymentMethod) {
                    throw new \InvalidArgumentException("Payment method is required for paid plans");
                }

                if ($paymentMethod === 'card') {
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
                } elseif ($paymentMethod === 'bank') {
                    // Validate bank transfer fields
                    $bankFields = ['rib_code', 'bank_name', 'account_holder'];
                    foreach ($bankFields as $field) {
                        if (empty($formData[$field])) {
                            throw new \InvalidArgumentException("Bank field {$field} is required");
                        }
                    }
                }
            }

            // Start database transaction
            $entityManager->beginTransaction();

            try {
            // Find existing Commercant for this user, or create new
            $commercant = $entityManager->getRepository(Commercant::class)
                ->findOneBy(['utilisateur' => $user]);

            if (!$commercant) {
            $commercant = new Commercant();
                $commercant->setUtilisateur($user);
                $commercant->setDateCreation(new \DateTime());
            }

            // Always update fields (whether new or existing)
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
            
            // Validate Commercant
            $errors = $validator->validate($commercant);
            if (count($errors) > 0) {
                throw new \InvalidArgumentException('Validation failed for merchant data');
            }
            
            $entityManager->persist($commercant);
            
            // Debug: Log received boutique_id
            error_log('Received boutique_id: ' . ($formData['boutique_id'] ?? 'NONE'));
            $boutiqueId = $formData['boutique_id'] ?? null;
            if ($boutiqueId) {
                // Fetch the boutique by ID and ensure it belongs to this commercant
                $boutique = $entityManager->getRepository(Boutique::class)
                    ->findOneBy(['id' => $boutiqueId, 'commercant' => $commercant]);
                error_log('Boutique found: ' . ($boutique ? $boutique->getId() : 'NOT FOUND'));
                if (!$boutique) {
                    error_log('Invalid boutique selected. Not creating new.');
                    throw new \InvalidArgumentException('Boutique not found or does not belong to you.');
                }
            } else {
                error_log('No boutique_id provided. Not creating new Boutique.');
                throw new \InvalidArgumentException('No boutique selected.');
            }

            // Always update fields (whether new or existing)
            $boutique->setNom($formData['shop_name']);
            $boutique->setDescription($formData['shop_description']);
            $boutique->setNiche($formData['niche']);
            $boutique->setTemplate($formData['template']);
            
            // Generate shop URL and slug
            $customDomain = $formData['custom_domain'] ?? null;
            if ($customDomain) {
                $boutique->setDomainePersonnalise($customDomain);
                $boutique->setUrl($customDomain);
            } else {
                $shopSlug = $this->generateShopSlug($formData['shop_name']);
                $boutique->setSlug($shopSlug);
                $boutique->setUrl($shopSlug . '.shoplab.com');
            }
            
            // Set initial status
                $boutique->setStatut('active');
            
            // Validate Boutique
            $errors = $validator->validate($boutique);
            if (count($errors) > 0) {
                throw new \InvalidArgumentException('Validation failed for shop data');
            }
            $entityManager->persist($boutique);
            
                // Check for existing subscription
                $existing = $entityManager->getRepository(BoutiqueSubscription::class)
                    ->findOneBy(['commercant' => $commercant, 'boutique' => $boutique]);
                if ($existing) {
                    $entityManager->commit();
                    return new JsonResponse([
                        'success' => false,
                        'message' => 'You are already subscribed to this boutique.'
                    ]);
                }

                // Create BoutiqueSubscription entity (comprehensive tracking)
                $boutiqueSubscription = new BoutiqueSubscription();
                $boutiqueSubscription->setCommercant($commercant);
                $boutiqueSubscription->setBoutique($boutique);
                $boutiqueSubscription->setPlan($formData['selected_plan']);
                $boutiqueSubscription->setStatut('active');
                $boutiqueSubscription->setDateDebut(new \DateTime());
                $boutiqueSubscription->setDateFin(new \DateTime('+1 month'));

                // Set plan prices
                $planPrices = [
                    'basic' => '0.00',
                    'standard' => '12.99',
                    'premium' => '18.99'
                ];
                $boutiqueSubscription->setPrix($planPrices[$formData['selected_plan']]);

                // Store business information
                $boutiqueSubscription->setBusinessName($formData['business_name']);
                $boutiqueSubscription->setBusinessType($formData['business_type']);
                $boutiqueSubscription->setBusinessAddress($formData['address'] . ', ' . $formData['city'] . ', ' . $formData['postal_code'] . ', ' . $formData['country']);
                $boutiqueSubscription->setPhone($formData['phone']);

                // Store shop customization data
                $boutiqueSubscription->setShopName($formData['shop_name']);
                $boutiqueSubscription->setShopDescription($formData['shop_description']);
                $boutiqueSubscription->setNiche($formData['niche']);
                $boutiqueSubscription->setTemplate($formData['template']);
                $boutiqueSubscription->setCustomDomain($formData['custom_domain'] ?? null);

                // Store payment information
                if ($formData['selected_plan'] !== 'basic') {
                    $boutiqueSubscription->setPaymentMethod($formData['payment_method']);
                    
                    if ($formData['payment_method'] === 'card') {
                        // Store billing information (NO CARD DETAILS!)
                        $boutiqueSubscription->setBillingName($formData['billing_name'] ?? null);
                        $boutiqueSubscription->setBillingAddress($formData['billing_address1'] ?? null);
                        $boutiqueSubscription->setBillingCity($formData['billing_city'] ?? null);
                        $boutiqueSubscription->setBillingPostalCode($formData['billing_postal_code'] ?? null);
                        $boutiqueSubscription->setBillingCountry($formData['billing_country'] ?? null);
                        
                        if (isset($formData['payment_intent'])) {
                            $boutiqueSubscription->setPaymentReference($formData['payment_intent']);
                            $boutiqueSubscription->setStripeSubscriptionId($formData['payment_intent']);
                        }
                    } elseif ($formData['payment_method'] === 'bank') {
                        // Store bank transfer information
                        $boutiqueSubscription->setBankName($formData['bank_name'] ?? null);
                        $boutiqueSubscription->setAccountHolder($formData['account_holder'] ?? null);
                        $boutiqueSubscription->setRibCode($formData['rib_code'] ?? null);
                        $boutiqueSubscription->setIban($formData['iban'] ?? null);
                        $boutiqueSubscription->setBicSwift($formData['bic_swift'] ?? null);
                        $boutiqueSubscription->setPaymentReference('BANK_TRANSFER_' . uniqid());
                    }
                }

                // Store all form data as JSON for future reference
                $boutiqueSubscription->setStepData([
                    'step1' => ['plan' => $formData['selected_plan']],
                    'step2' => [
                        'first_name' => $formData['first_name'],
                        'last_name' => $formData['last_name'],
                        'email' => $formData['email'],
                        'phone' => $formData['phone'],
                        'business_name' => $formData['business_name'],
                        'business_type' => $formData['business_type'],
                        'website' => $formData['website'] ?? null
                    ],
                    'step3' => [
                        'address' => $formData['address'],
                        'city' => $formData['city'],
                        'postal_code' => $formData['postal_code'],
                        'country' => $formData['country']
                    ],
                    'step4' => [
                        'payment_method' => $formData['payment_method'] ?? null,
                        'payment_status' => $formData['selected_plan'] === 'basic' ? 'free' : 'paid'
                    ],
                    'step5' => [
                        'shop_name' => $formData['shop_name'],
                        'shop_description' => $formData['shop_description'],
                        'niche' => $formData['niche'],
                        'template' => $formData['template'],
                        'custom_domain' => $formData['custom_domain'] ?? null
                    ]
                ]);

                $entityManager->persist($boutiqueSubscription);
            
            // Save all entities
            $entityManager->flush();
                $entityManager->commit();

                // Ensure user has ROLE_COMMERCANT role and update session
                $user = $this->getUser();
                $roles = $user->getRoles();
                if (!in_array('ROLE_COMMERCANT', $roles)) {
                    $roles[] = 'ROLE_COMMERCANT';
                    $user->setRoles($roles);
                    $entityManager->persist($user);
                    $entityManager->flush();
                }

                // Refresh the user's token to update the session
                $token = new \Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken(
                    $user,
                    'main',
                    $user->getRoles()
                );
                $this->container->get('security.token_storage')->setToken($token);
                $request->getSession()->set('_security_main', serialize($token));

                // At the end, ensure we return proper JSON response
                $shopSlug = $boutique->getSlug();
                $shopUrl = $this->generateUrl('public_shop', ['slug' => $shopSlug], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);
                $dashboardUrl = $this->generateUrl('commercant_dashboard', ['slug' => $shopSlug], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

                $successMessage = sprintf(
                    'Subscription successful! You can launch your store through this link: %s',
                    $shopUrl
                );

                $responseData = [
                    'success' => true,
                    'shop_url' => $shopUrl,
                    'dashboard_url' => $dashboardUrl,
                    'message' => $successMessage,
                    'shop_name' => $boutique->getNom(),
                    'plan' => $boutiqueSubscription->getPlan(),
                    'subscription_id' => $boutiqueSubscription->getId(),
                    'boutique_id' => $boutique->getId(),
                    'commercant_id' => $commercant->getId()
                ];

                error_log('Success response: ' . json_encode($responseData));
                return new JsonResponse($responseData);

            } catch (\Exception $e) {
                $entityManager->rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            error_log('Subscription process error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
                return new JsonResponse([
                    'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'debug' => $e->getFile() . ':' . $e->getLine()
                ], 400);
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
