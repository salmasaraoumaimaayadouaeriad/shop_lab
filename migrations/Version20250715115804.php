<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250715115804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique ADD custom_title VARCHAR(255) DEFAULT NULL, ADD custom_description LONGTEXT DEFAULT NULL, ADD custom_image VARCHAR(255) DEFAULT NULL, ADD custom_categories JSON DEFAULT NULL, ADD custom_panel_json JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique DROP custom_title, DROP custom_description, DROP custom_image, DROP custom_categories, DROP custom_panel_json');
    }
}
