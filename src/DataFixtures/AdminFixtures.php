<?php
namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Entity\Administrateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Create main admin user
        $adminUser = new Utilisateur();
        $adminUser->setEmail('admin@shoplab.com');
        $adminUser->setNom('Super Admin');
        $adminUser->setPays('France');
        $adminUser->setDevise('EUR');
        $adminUser->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $adminUser->setIsVerified(true);
        
        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($adminUser, 'admin123');
        $adminUser->setPassword($hashedPassword);
        
        $manager->persist($adminUser);

        // Create admin profile
        $admin = new Administrateur();
        $admin->setUtilisateur($adminUser);
        $admin->setNiveau('super_admin');
        $admin->setPermissions([
            'manage_users',
            'manage_boutiques', 
            'manage_categories',
            'manage_payments',
            'manage_templates',
            'view_analytics',
            'bulk_operations',
            'email_management'
        ]);
        
        $manager->persist($admin);

        // Create secondary admin
        $adminUser2 = new Utilisateur();
        $adminUser2->setEmail('moderator@shoplab.com');
        $adminUser2->setNom('Moderator Admin');
        $adminUser2->setRoles(['ROLE_ADMIN']);
        $adminUser2->setIsVerified(true);
        $adminUser2->setPays('France');
        $adminUser2->setDevise('EUR');
        
        $hashedPassword2 = $this->passwordHasher->hashPassword($adminUser2, 'moderator123');
        $adminUser2->setPassword($hashedPassword2);
        
        $manager->persist($adminUser2);

        // Create moderator profile
        $admin2 = new Administrateur();
        $admin2->setUtilisateur($adminUser2);
        $admin2->setNiveau('moderator');
        $admin2->setPermissions([
            'manage_boutiques', 
            'manage_categories',
            'view_analytics'
        ]);
        
        $manager->persist($admin2);
        $manager->flush();
        $admin2->setPermissions([
            'manage_boutiques', 
            'manage_categories',
            'view_analytics'
        ]);
        
        $manager->persist($admin2);

        $manager->flush();
    }
}
