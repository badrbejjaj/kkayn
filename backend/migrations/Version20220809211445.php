<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220809211445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, user_created INT NOT NULL, user_updated INT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, log_supp TINYINT(1) DEFAULT 0 NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, INDEX IDX_497DD634EA30A9B2 (user_created), INDEX IDX_497DD6349E9688FD (user_updated), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD634EA30A9B2 FOREIGN KEY (user_created) REFERENCES user (id)');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT FK_497DD6349E9688FD FOREIGN KEY (user_updated) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article CHANGE active active TINYINT(1) DEFAULT 1 NOT NULL, CHANGE log_supp log_supp TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categorie');
        $this->addSql('ALTER TABLE article CHANGE active active TINYINT(1) NOT NULL, CHANGE log_supp log_supp TINYINT(1) NOT NULL');
    }
}
