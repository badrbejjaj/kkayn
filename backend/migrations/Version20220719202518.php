<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719202518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_created INT NOT NULL, user_updated INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, gender VARCHAR(75) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', creation_date DATETIME NOT NULL, update_date DATETIME NOT NULL, INDEX IDX_8D93D649EA30A9B2 (user_created), INDEX IDX_8D93D6499E9688FD (user_updated), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EA30A9B2 FOREIGN KEY (user_created) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499E9688FD FOREIGN KEY (user_updated) REFERENCES user (id)');

        $encoder = new NativePasswordHasher();
        $SuperAdminUser = [
            'id' => 1,
            'username' => 'superAdmin',
            'gender' => 'm',
            'password' => $encoder->hash('azeaze', null),
            'email' => 'superadmin@mycoach.com',
            'roles' => "a:1:{i:0;s:9:\"SUPER_ADMIN_ROLE\";}",
            'user' => 1,
            'date' => date("Y-m-d h:m:s"),
        ];

        $this->addSql('INSERT INTO `user` (`id`, `user_created`, `user_updated`, `first_name`, `last_name`, `username`,`gender`, `password`, `email`, `creation_date`, `update_date`, `roles`) 
        VALUES (:id, :user, :user, :username, :username, :username,:gender, :password, :email, :date, :date, roles ) ', $SuperAdminUser);
        

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EA30A9B2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499E9688FD');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
