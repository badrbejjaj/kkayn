<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220812160435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vocabulary (id INT AUTO_INCREMENT NOT NULL, user_created INT NOT NULL, user_updated INT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, log_supp TINYINT(1) DEFAULT 0 NOT NULL, creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, INDEX IDX_9099C97BEA30A9B2 (user_created), INDEX IDX_9099C97B9E9688FD (user_updated), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vocabulary ADD CONSTRAINT FK_9099C97BEA30A9B2 FOREIGN KEY (user_created) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vocabulary ADD CONSTRAINT FK_9099C97B9E9688FD FOREIGN KEY (user_updated) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vocabulary');
    }
}
