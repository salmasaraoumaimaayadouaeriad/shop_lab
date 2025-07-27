<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724091452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique ADD description LONGTEXT DEFAULT NULL, ADD niche VARCHAR(100) DEFAULT NULL, ADD template VARCHAR(100) DEFAULT NULL, ADD url VARCHAR(255) DEFAULT NULL, ADD domaine_personnalise VARCHAR(255) DEFAULT NULL, ADD statut VARCHAR(50) DEFAULT NULL, ADD date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, CHANGE slug slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE commercant ADD nom VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD telephone VARCHAR(50) DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL, ADD ville VARCHAR(100) DEFAULT NULL, ADD code_postal VARCHAR(20) DEFAULT NULL, ADD pays VARCHAR(100) DEFAULT NULL, ADD nom_entreprise VARCHAR(255) DEFAULT NULL, ADD type_entreprise VARCHAR(100) DEFAULT NULL, ADD site_web VARCHAR(255) DEFAULT NULL, ADD date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique DROP description, DROP niche, DROP template, DROP url, DROP domaine_personnalise, DROP statut, DROP date_creation, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commercant DROP nom, DROP email, DROP telephone, DROP adresse, DROP ville, DROP code_postal, DROP pays, DROP nom_entreprise, DROP type_entreprise, DROP site_web, DROP date_creation');
    }
}
