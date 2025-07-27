<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250724095727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique CHANGE date_creation date_creation DATETIME NOT NULL');
        $this->addSql('ALTER TABLE commercant DROP FOREIGN KEY FK_ECB4268F1D3B42FA');
        $this->addSql('DROP INDEX IDX_ECB4268F1D3B42FA ON commercant');
        $this->addSql('ALTER TABLE commercant DROP boutique_principale_id, CHANGE utilisateur_id utilisateur_id INT NOT NULL, CHANGE date_creation date_creation DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique CHANGE date_creation date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE commercant ADD boutique_principale_id INT DEFAULT NULL, CHANGE date_creation date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commercant ADD CONSTRAINT FK_ECB4268F1D3B42FA FOREIGN KEY (boutique_principale_id) REFERENCES boutique (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_ECB4268F1D3B42FA ON commercant (boutique_principale_id)');
    }
}
