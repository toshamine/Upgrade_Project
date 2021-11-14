<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211106105843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE white_test ADD certification_id INT NOT NULL, CHANGE limit_time limit_time TIME NOT NULL');
        $this->addSql('ALTER TABLE white_test ADD CONSTRAINT FK_DC66FD60CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id)');
        $this->addSql('CREATE INDEX IDX_DC66FD60CB47068A ON white_test (certification_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE white_test DROP FOREIGN KEY FK_DC66FD60CB47068A');
        $this->addSql('DROP INDEX IDX_DC66FD60CB47068A ON white_test');
        $this->addSql('ALTER TABLE white_test DROP certification_id, CHANGE limit_time limit_time TIME DEFAULT NULL');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
