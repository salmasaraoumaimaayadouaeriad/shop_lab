<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724102204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutique_subscriptions (id INT AUTO_INCREMENT NOT NULL, plan VARCHAR(50) NOT NULL, prix NUMERIC(10, 2) DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, date_creation DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, payment_method VARCHAR(50) NOT NULL, payment_reference VARCHAR(255) DEFAULT NULL, stripe_subscription_id VARCHAR(255) DEFAULT NULL, stripe_customer_id VARCHAR(255) DEFAULT NULL, business_name VARCHAR(255) DEFAULT NULL, business_type VARCHAR(255) DEFAULT NULL, business_address VARCHAR(255) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, vat_number VARCHAR(50) DEFAULT NULL, billing_name VARCHAR(255) DEFAULT NULL, billing_address VARCHAR(255) DEFAULT NULL, billing_city VARCHAR(100) DEFAULT NULL, billing_postal_code VARCHAR(20) DEFAULT NULL, billing_country VARCHAR(100) DEFAULT NULL, bank_name VARCHAR(255) DEFAULT NULL, account_holder VARCHAR(255) DEFAULT NULL, rib_code VARCHAR(50) DEFAULT NULL, iban VARCHAR(50) DEFAULT NULL, bic_swift VARCHAR(20) DEFAULT NULL, shop_name VARCHAR(255) DEFAULT NULL, shop_description LONGTEXT DEFAULT NULL, niche VARCHAR(100) DEFAULT NULL, template VARCHAR(100) DEFAULT NULL, custom_domain VARCHAR(255) DEFAULT NULL, step_data JSON DEFAULT NULL, commercant_id INT NOT NULL, boutique_id INT NOT NULL, INDEX IDX_CCBAFA9283FA6DD0 (commercant_id), INDEX IDX_CCBAFA92AB677BE6 (boutique_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE boutique_subscriptions ADD CONSTRAINT FK_CCBAFA9283FA6DD0 FOREIGN KEY (commercant_id) REFERENCES commercant (id)');
        $this->addSql('ALTER TABLE boutique_subscriptions ADD CONSTRAINT FK_CCBAFA92AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE boutique_subscription DROP FOREIGN KEY FK_B9E777E183FA6DD0');
        $this->addSql('ALTER TABLE boutique_subscription DROP FOREIGN KEY FK_B9E777E1AB677BE6');
        $this->addSql('DROP TABLE boutique_subscription');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutique_subscription (id INT AUTO_INCREMENT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, statut VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, payment_reference VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, business_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, business_address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, vat_number VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, step_data JSON DEFAULT NULL, commercant_id INT NOT NULL, boutique_id INT NOT NULL, card_number VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, card_expiry VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, card_cvc VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, card_holder_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, billing_address VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, billing_city VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, billing_postal_code VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, billing_country VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_B9E777E1AB677BE6 (boutique_id), INDEX IDX_B9E777E183FA6DD0 (commercant_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE boutique_subscription ADD CONSTRAINT FK_B9E777E183FA6DD0 FOREIGN KEY (commercant_id) REFERENCES commercant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE boutique_subscription ADD CONSTRAINT FK_B9E777E1AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE boutique_subscriptions DROP FOREIGN KEY FK_CCBAFA9283FA6DD0');
        $this->addSql('ALTER TABLE boutique_subscriptions DROP FOREIGN KEY FK_CCBAFA92AB677BE6');
        $this->addSql('DROP TABLE boutique_subscriptions');
    }
}
