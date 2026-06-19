<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260619151542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP selector, DROP requested_at, CHANGE hashed_token token VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user ADD is_active TINYINT DEFAULT 0 NOT NULL, ADD activation_token VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request ADD selector VARCHAR(20) NOT NULL, ADD requested_at DATETIME NOT NULL, CHANGE token hashed_token VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user DROP is_active, DROP activation_token');
    }
}
