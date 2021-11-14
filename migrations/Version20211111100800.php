<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211111100800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE records CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE white_test DROP date, DROP limit_time, DROP time');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE records CHANGE date date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE white_test ADD date DATE DEFAULT NULL, ADD limit_time TIME NOT NULL, ADD time TIME DEFAULT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
