<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723232446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique_subscription ADD card_number VARCHAR(100) DEFAULT NULL, ADD card_expiry VARCHAR(10) DEFAULT NULL, ADD card_cvc VARCHAR(10) DEFAULT NULL, ADD card_holder_name VARCHAR(255) DEFAULT NULL, ADD billing_address VARCHAR(255) DEFAULT NULL, ADD billing_city VARCHAR(100) DEFAULT NULL, ADD billing_postal_code VARCHAR(20) DEFAULT NULL, ADD billing_country VARCHAR(100) DEFAULT NULL, CHANGE custom_fields step_data JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique_subscription DROP card_number, DROP card_expiry, DROP card_cvc, DROP card_holder_name, DROP billing_address, DROP billing_city, DROP billing_postal_code, DROP billing_country, CHANGE step_data custom_fields JSON DEFAULT NULL');
    }
}
