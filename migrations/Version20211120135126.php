<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211120135126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE difficulty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE records (id INT AUTO_INCREMENT NOT NULL, user VARCHAR(255) NOT NULL, score VARCHAR(255) NOT NULL, white_test VARCHAR(255) NOT NULL, certification VARCHAR(255) NOT NULL, date DATE NOT NULL, total INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE certification ADD company_id INT DEFAULT NULL, ADD difficulty_id INT DEFAULT NULL, DROP company, DROP difficulty, CHANGE category_id category_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE picture picture VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulty (id)');
        $this->addSql('CREATE INDEX IDX_6C3C6D75979B1AD6 ON certification (company_id)');
        $this->addSql('CREATE INDEX IDX_6C3C6D75FCFA9DAE ON certification (difficulty_id)');
        $this->addSql('ALTER TABLE document DROP file');
        $this->addSql('ALTER TABLE question ADD choice_a VARCHAR(255) NOT NULL, ADD choice_b VARCHAR(255) NOT NULL, ADD choice_c VARCHAR(255) DEFAULT NULL, ADD duration INT NOT NULL, DROP choices');
        $this->addSql('ALTER TABLE white_test ADD certification_id INT NOT NULL, ADD title VARCHAR(255) NOT NULL, DROP date, DROP limit_time, DROP time');
        $this->addSql('ALTER TABLE white_test ADD CONSTRAINT FK_DC66FD60CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id)');
        $this->addSql('CREATE INDEX IDX_DC66FD60CB47068A ON white_test (certification_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75979B1AD6');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75FCFA9DAE');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE difficulty');
        $this->addSql('DROP TABLE records');
        $this->addSql('DROP INDEX IDX_6C3C6D75979B1AD6 ON certification');
        $this->addSql('DROP INDEX IDX_6C3C6D75FCFA9DAE ON certification');
        $this->addSql('ALTER TABLE certification ADD company VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD difficulty VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP company_id, DROP difficulty_id, CHANGE category_id category_id INT NOT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE picture picture VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE document ADD file VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE question ADD choices LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', DROP choice_a, DROP choice_b, DROP choice_c, DROP duration');
        $this->addSql('ALTER TABLE white_test DROP FOREIGN KEY FK_DC66FD60CB47068A');
        $this->addSql('DROP INDEX IDX_DC66FD60CB47068A ON white_test');
        $this->addSql('ALTER TABLE white_test ADD date DATE DEFAULT NULL, ADD limit_time TIME DEFAULT NULL, ADD time TIME DEFAULT NULL, DROP certification_id, DROP title');
    }
}
