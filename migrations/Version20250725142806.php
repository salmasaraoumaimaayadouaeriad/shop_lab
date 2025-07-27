<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250725142806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE commercant DROP INDEX IDX_ECB4268FFB88E14F, ADD UNIQUE INDEX UNIQ_ECB4268FFB88E14F (utilisateur_id)');
        $this->addSql('ALTER TABLE commercant ADD UNIQUE INDEX UNIQ_ECB4268FFB88E14F (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE commercant DROP INDEX UNIQ_ECB4268FFB88E14F, ADD INDEX IDX_ECB4268FFB88E14F (utilisateur_id)');
        $this->addSql('ALTER TABLE commercant DROP INDEX UNIQ_ECB4268FFB88E14F');
    }
}
