<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if email already exists
            $email = $form->get('email')->getData();
            $existingUser = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'This email is already used. Please use another one or log in.');
                return $this->redirectToRoute('app_register');
            }

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Set additional user properties
            $user->setNom($form->get('nom')->getData());
            $user->setDevise($form->get('devise')->getData());
            $user->setPays($form->get('pays')->getData());
            $user->setRoles(['ROLE_COMMERCANT']);
            $user->setEmail($email);

            if (!$user->getEmail()) {
                throw new \InvalidArgumentException('Email cannot be null');
            }
            if (!$user->getNom()) {
                throw new \InvalidArgumentException('Name cannot be null');
            }

            // Encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setIsVerified(true);

            $entityManager->persist($user);
            $entityManager->flush();

            // Create and persist a Commercant for this user
            $commercant = new \App\Entity\Commercant();
            $commercant->setUtilisateur($user);
            $commercant->setNom($user->getNom() ?: $user->getEmail());
            $entityManager->persist($commercant);
            $entityManager->flush();

            $this->addFlash('success', 'Registration successful! Please log in.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var Utilisateur $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    // Utility method to ensure every user has a Commercant
    public static function ensureCommercantsForAllUsers(EntityManagerInterface $entityManager): void
    {
        $users = $entityManager->getRepository(\App\Entity\Utilisateur::class)->findAll();
        foreach ($users as $user) {
            $commercant = $entityManager->getRepository(\App\Entity\Commercant::class)->findOneBy(['utilisateur' => $user]);
            if (!$commercant) {
                $commercant = new \App\Entity\Commercant();
                $commercant->setUtilisateur($user);
                $commercant->setNom($user->getNom() ?: $user->getEmail());
                $entityManager->persist($commercant);
            }
        }
        $entityManager->flush();
    }
}