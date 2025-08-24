<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250731115013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'No changes: all columns already exist in panier table.';
    }

    public function up(Schema $schema): void
    {
        // No changes needed, columns already exist
    }

    public function down(Schema $schema): void
    {
        // No changes needed, nothing to revert
    }
}
