<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721145646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscriptions (id INT AUTO_INCREMENT NOT NULL, plan VARCHAR(50) NOT NULL, statut VARCHAR(20) NOT NULL, prix NUMERIC(10, 2) DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, date_creation DATETIME NOT NULL, stripe_subscription_id VARCHAR(255) DEFAULT NULL, stripe_customer_id VARCHAR(255) DEFAULT NULL, commercant_id INT NOT NULL, INDEX IDX_4778A0183FA6DD0 (commercant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE subscriptions ADD CONSTRAINT FK_4778A0183FA6DD0 FOREIGN KEY (commercant_id) REFERENCES commercant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscriptions DROP FOREIGN KEY FK_4778A0183FA6DD0');
        $this->addSql('DROP TABLE subscriptions');
    }
}
