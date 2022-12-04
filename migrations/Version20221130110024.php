<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130110024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet_podium (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, podium_countrie_id INT NOT NULL, first_countrie_user_id INT DEFAULT NULL, second_countrie_user_id INT DEFAULT NULL, third_countrie_user_id INT DEFAULT NULL, calculation TINYINT(1) NOT NULL, INDEX IDX_5FA29314A76ED395 (user_id), INDEX IDX_5FA29314624CC32D (podium_countrie_id), INDEX IDX_5FA29314412FDD38 (first_countrie_user_id), INDEX IDX_5FA293144F0D1DAB (second_countrie_user_id), INDEX IDX_5FA2931472A7317A (third_countrie_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE podium_countries (id INT AUTO_INCREMENT NOT NULL, first_place_id INT DEFAULT NULL, second_place_id INT DEFAULT NULL, third_place_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_EDA5A1ABB9BE9042 (first_place_id), INDEX IDX_EDA5A1AB3EB2FD7C (second_place_id), INDEX IDX_EDA5A1AB785EA6DC (third_place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet_podium ADD CONSTRAINT FK_5FA29314A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE bet_podium ADD CONSTRAINT FK_5FA29314624CC32D FOREIGN KEY (podium_countrie_id) REFERENCES podium_countries (id)');
        $this->addSql('ALTER TABLE bet_podium ADD CONSTRAINT FK_5FA29314412FDD38 FOREIGN KEY (first_countrie_user_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE bet_podium ADD CONSTRAINT FK_5FA293144F0D1DAB FOREIGN KEY (second_countrie_user_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE bet_podium ADD CONSTRAINT FK_5FA2931472A7317A FOREIGN KEY (third_countrie_user_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE podium_countries ADD CONSTRAINT FK_EDA5A1ABB9BE9042 FOREIGN KEY (first_place_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE podium_countries ADD CONSTRAINT FK_EDA5A1AB3EB2FD7C FOREIGN KEY (second_place_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE podium_countries ADD CONSTRAINT FK_EDA5A1AB785EA6DC FOREIGN KEY (third_place_id) REFERENCES countries_teams (id)');
        $this->addSql("INSERT INTO `podium_countries` (`id`, `first_place_id`, `second_place_id`, `third_place_id`, `date`) VALUES (NULL, NULL, NULL, NULL, '2022-12-13 20:00:00')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet_podium DROP FOREIGN KEY FK_5FA29314A76ED395');
        $this->addSql('ALTER TABLE bet_podium DROP FOREIGN KEY FK_5FA29314624CC32D');
        $this->addSql('ALTER TABLE bet_podium DROP FOREIGN KEY FK_5FA29314412FDD38');
        $this->addSql('ALTER TABLE bet_podium DROP FOREIGN KEY FK_5FA293144F0D1DAB');
        $this->addSql('ALTER TABLE bet_podium DROP FOREIGN KEY FK_5FA2931472A7317A');
        $this->addSql('ALTER TABLE podium_countries DROP FOREIGN KEY FK_EDA5A1ABB9BE9042');
        $this->addSql('ALTER TABLE podium_countries DROP FOREIGN KEY FK_EDA5A1AB3EB2FD7C');
        $this->addSql('ALTER TABLE podium_countries DROP FOREIGN KEY FK_EDA5A1AB785EA6DC');
        $this->addSql('DROP TABLE bet_podium');
        $this->addSql('DROP TABLE podium_countries');
    }
}
