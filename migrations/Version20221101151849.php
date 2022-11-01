<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221101151849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet_user (id INT AUTO_INCREMENT NOT NULL, matches_id INT NOT NULL, user_id INT NOT NULL, score_countrie_1 INT NOT NULL, score_countrie_2 INT NOT NULL, calculate TINYINT(1) NOT NULL, INDEX IDX_A84505754B30DD19 (matches_id), INDEX IDX_A8450575A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE countries_teams (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, iso_flag VARCHAR(20) NOT NULL, categories VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, countrie_1_id INT DEFAULT NULL, countrie_2_id INT DEFAULT NULL, date DATETIME NOT NULL, score_countrie_1 INT DEFAULT NULL, score_countrie_2 INT DEFAULT NULL, type_match INT NOT NULL, INDEX IDX_62615BAC829810E (countrie_1_id), INDEX IDX_62615BADA9C2EE0 (countrie_2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, groupes_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, valide_register TINYINT(1) NOT NULL, paid TINYINT(1) NOT NULL, url_picture VARCHAR(500) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, modified_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649305371B (groupes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_scores (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, scores INT NOT NULL, UNIQUE INDEX UNIQ_62022C63A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet_user ADD CONSTRAINT FK_A84505754B30DD19 FOREIGN KEY (matches_id) REFERENCES matches (id)');
        $this->addSql('ALTER TABLE bet_user ADD CONSTRAINT FK_A8450575A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAC829810E FOREIGN KEY (countrie_1_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BADA9C2EE0 FOREIGN KEY (countrie_2_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649305371B FOREIGN KEY (groupes_id) REFERENCES user_groups (id)');
        $this->addSql('ALTER TABLE user_scores ADD CONSTRAINT FK_62022C63A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet_user DROP FOREIGN KEY FK_A84505754B30DD19');
        $this->addSql('ALTER TABLE bet_user DROP FOREIGN KEY FK_A8450575A76ED395');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAC829810E');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BADA9C2EE0');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649305371B');
        $this->addSql('ALTER TABLE user_scores DROP FOREIGN KEY FK_62022C63A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE bet_user');
        $this->addSql('DROP TABLE countries_teams');
        $this->addSql('DROP TABLE matches');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_groups');
        $this->addSql('DROP TABLE user_scores');
        $this->addSql('DROP TABLE reset_password_request');
    }
}
