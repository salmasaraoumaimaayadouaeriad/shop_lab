<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250714144815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique ADD site_name VARCHAR(255) DEFAULT NULL, ADD background_color VARCHAR(20) DEFAULT NULL, ADD text_color VARCHAR(20) DEFAULT NULL, ADD link_color VARCHAR(20) DEFAULT NULL, ADD button_color VARCHAR(20) DEFAULT NULL, ADD accent_color VARCHAR(20) DEFAULT NULL, ADD get_in_touch LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique DROP site_name, DROP background_color, DROP text_color, DROP link_color, DROP button_color, DROP accent_color, DROP get_in_touch');
    }
}
