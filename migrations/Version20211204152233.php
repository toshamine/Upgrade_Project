<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211204152233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, certification_id INT DEFAULT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_10C31F86A76ED395 (user_id), INDEX IDX_10C31F86CB47068A (certification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F86A76ED395 FOREIGN KEY (user_id) REFERENCES user1 (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F86CB47068A FOREIGN KEY (certification_id) REFERENCES certification (id)');
        $this->addSql('ALTER TABLE user1 ADD r_dv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user1 ADD CONSTRAINT FK_8C518555A0A596B1 FOREIGN KEY (r_dv_id) REFERENCES rdv (id)');
        $this->addSql('CREATE INDEX IDX_8C518555A0A596B1 ON user1 (r_dv_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user1 DROP FOREIGN KEY FK_8C518555A0A596B1');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP INDEX IDX_8C518555A0A596B1 ON user1');
        $this->addSql('ALTER TABLE user1 DROP r_dv_id');
    }
    public function isTransactional(): bool
    {
        return false;
    }
}
