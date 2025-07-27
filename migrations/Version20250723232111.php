<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723232111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutique_subscription (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, payment_reference VARCHAR(255) DEFAULT NULL, business_name VARCHAR(255) DEFAULT NULL, business_address VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, vat_number VARCHAR(50) DEFAULT NULL, custom_fields JSON DEFAULT NULL, commercant_id INT NOT NULL, boutique_id INT NOT NULL, INDEX IDX_B9E777E183FA6DD0 (commercant_id), INDEX IDX_B9E777E1AB677BE6 (boutique_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE boutique_subscription ADD CONSTRAINT FK_B9E777E183FA6DD0 FOREIGN KEY (commercant_id) REFERENCES commercant (id)');
        $this->addSql('ALTER TABLE boutique_subscription ADD CONSTRAINT FK_B9E777E1AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique_subscription DROP FOREIGN KEY FK_B9E777E183FA6DD0');
        $this->addSql('ALTER TABLE boutique_subscription DROP FOREIGN KEY FK_B9E777E1AB677BE6');
        $this->addSql('DROP TABLE boutique_subscription');
    }
}
