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
        $connection = $this->connection;
        $sql = "SHOW INDEX FROM commercant WHERE Key_name = 'UNIQ_ECB4268FFB88E14F'";
        $result = $connection->executeQuery($sql);

        if ($result->rowCount() === 0) {
            $this->addSql('ALTER TABLE commercant ADD UNIQUE INDEX UNIQ_ECB4268FFB88E14F (utilisateur_id)');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE commercant DROP INDEX UNIQ_ECB4268FFB88E14F');
    }
}
