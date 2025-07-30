<?php

namespace App\Command;

use App\Entity\Utilisateur;
use App\Entity\Administrateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create a new admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Admin email')
            ->addArgument('password', InputArgument::REQUIRED, 'Admin password')
            ->addArgument('name', InputArgument::REQUIRED, 'Admin name')
            ->addArgument('level', InputArgument::OPTIONAL, 'Admin level (admin|super_admin)', 'admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $name = $input->getArgument('name');
        $level = $input->getArgument('level');

        // Check if user already exists
        $existingUser = $this->entityManager->getRepository(Utilisateur::class)
            ->findOneBy(['email' => $email]);
            
        if ($existingUser) {
            $io->error('A user with this email already exists!');
            return Command::FAILURE;
        }

        // Create user
        $user = new Utilisateur();
        $user->setEmail($email);
        $user->setNom($name);
        $user->setIsVerified(true);
        $user->setPays('France');
        $user->setDevise('EUR');
        
        // Set roles based on level
        if ($level === 'super_admin') {
            $user->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
        } else {
            $user->setRoles(['ROLE_ADMIN']);
        }
        
        // Hash password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        
        $this->entityManager->persist($user);

        // Create admin profile
        $admin = new Administrateur();
        $admin->setUtilisateur($user);
        $admin->setNiveau($level);
        
        // Set permissions based on level
        if ($level === 'super_admin') {
            $admin->setPermissions([
                'manage_users',
                'manage_boutiques', 
                'manage_categories',
                'manage_payments',
                'manage_templates',
                'view_analytics',
                'bulk_operations',
                'email_management',
                'system_settings'
            ]);
        } else {
            $admin->setPermissions([
                'manage_boutiques', 
                'manage_categories',
                'view_analytics'
            ]);
        }
        
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success("Admin user created successfully!");
        $io->table(['Field', 'Value'], [
            ['Email', $email],
            ['Name', $name],
            ['Level', $level],
            ['Login URL', '/admin/login']
        ]);

        return Command::SUCCESS;
    }
}
