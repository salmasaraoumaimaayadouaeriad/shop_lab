<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250725093734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_final ADD boutique_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_final ADD CONSTRAINT FK_4FB8C071AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('CREATE INDEX IDX_4FB8C071AB677BE6 ON client_final (boutique_id)');
        $this->addSql('ALTER TABLE produit CHANGE image image LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_final DROP FOREIGN KEY FK_4FB8C071AB677BE6');
        $this->addSql('DROP INDEX IDX_4FB8C071AB677BE6 ON client_final');
        $this->addSql('ALTER TABLE client_final DROP boutique_id');
        $this->addSql('ALTER TABLE produit CHANGE image image VARCHAR(255) NOT NULL');
    }
}
