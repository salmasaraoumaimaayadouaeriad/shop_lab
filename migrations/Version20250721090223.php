<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721090223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique DROP custom_colors, DROP custom_products, DROP description, DROP custom_css, DROP custom_js, DROP favicon, DROP background_image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique ADD custom_colors JSON DEFAULT NULL, ADD custom_products JSON DEFAULT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD custom_css LONGTEXT DEFAULT NULL, ADD custom_js LONGTEXT DEFAULT NULL, ADD favicon VARCHAR(255) DEFAULT NULL, ADD background_image VARCHAR(255) DEFAULT NULL');
    }
}
