<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208122841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE records ADD user1_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE records ADD CONSTRAINT FK_9C9D584656AE248B FOREIGN KEY (user1_id) REFERENCES user1 (id)');
        $this->addSql('CREATE INDEX IDX_9C9D584656AE248B ON records (user1_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE records DROP FOREIGN KEY FK_9C9D584656AE248B');
        $this->addSql('DROP INDEX IDX_9C9D584656AE248B ON records');
        $this->addSql('ALTER TABLE records DROP user1_id');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
