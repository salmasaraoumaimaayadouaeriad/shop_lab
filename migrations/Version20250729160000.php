<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250729160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add cart history fields to Panier table';
    }

    public function up(Schema $schema): void
    {
        // Add new fields to panier table
        $this->addSql('ALTER TABLE panier ADD is_history BOOLEAN DEFAULT FALSE NOT NULL');
        $this->addSql('ALTER TABLE panier ADD created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD completed_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Remove the added fields
        $this->addSql('ALTER TABLE panier DROP is_history');
        $this->addSql('ALTER TABLE panier DROP created_at');
        $this->addSql('ALTER TABLE panier DROP completed_at');
    }
} 