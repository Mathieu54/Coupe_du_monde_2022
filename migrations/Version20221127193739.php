<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221127193739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet_qualification_countries (id INT AUTO_INCREMENT NOT NULL, countrie_1_id INT NOT NULL, countrie_2_id INT NOT NULL, qualification_countries_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4339CA0C829810E (countrie_1_id), INDEX IDX_4339CA0DA9C2EE0 (countrie_2_id), INDEX IDX_4339CA0CDDD3CEF (qualification_countries_id), INDEX IDX_4339CA0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE qualification_countries (id INT AUTO_INCREMENT NOT NULL, first_country_res_id INT DEFAULT NULL, second_country_res_id INT DEFAULT NULL, countrie_1_eighth_id INT DEFAULT NULL, countrie_2_eighth_id INT DEFAULT NULL, countrie_3_eighth_id INT DEFAULT NULL, countrie_4_eighth_id INT DEFAULT NULL, date DATETIME NOT NULL, type_phase VARCHAR(255) NOT NULL, INDEX IDX_3B36F040EC5A3719 (first_country_res_id), INDEX IDX_3B36F0401D245085 (second_country_res_id), INDEX IDX_3B36F040DBD0749A (countrie_1_eighth_id), INDEX IDX_3B36F0404232129B (countrie_2_eighth_id), INDEX IDX_3B36F04083BCCD5B (countrie_3_eighth_id), INDEX IDX_3B36F040AA87D8D8 (countrie_4_eighth_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet_qualification_countries ADD CONSTRAINT FK_4339CA0C829810E FOREIGN KEY (countrie_1_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE bet_qualification_countries ADD CONSTRAINT FK_4339CA0DA9C2EE0 FOREIGN KEY (countrie_2_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE bet_qualification_countries ADD CONSTRAINT FK_4339CA0CDDD3CEF FOREIGN KEY (qualification_countries_id) REFERENCES qualification_countries (id)');
        $this->addSql('ALTER TABLE bet_qualification_countries ADD CONSTRAINT FK_4339CA0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE qualification_countries ADD CONSTRAINT FK_3B36F040EC5A3719 FOREIGN KEY (first_country_res_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE qualification_countries ADD CONSTRAINT FK_3B36F0401D245085 FOREIGN KEY (second_country_res_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE qualification_countries ADD CONSTRAINT FK_3B36F040DBD0749A FOREIGN KEY (countrie_1_eighth_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE qualification_countries ADD CONSTRAINT FK_3B36F0404232129B FOREIGN KEY (countrie_2_eighth_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE qualification_countries ADD CONSTRAINT FK_3B36F04083BCCD5B FOREIGN KEY (countrie_3_eighth_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE qualification_countries ADD CONSTRAINT FK_3B36F040AA87D8D8 FOREIGN KEY (countrie_4_eighth_id) REFERENCES countries_teams (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet_qualification_countries DROP FOREIGN KEY FK_4339CA0C829810E');
        $this->addSql('ALTER TABLE bet_qualification_countries DROP FOREIGN KEY FK_4339CA0DA9C2EE0');
        $this->addSql('ALTER TABLE bet_qualification_countries DROP FOREIGN KEY FK_4339CA0CDDD3CEF');
        $this->addSql('ALTER TABLE bet_qualification_countries DROP FOREIGN KEY FK_4339CA0A76ED395');
        $this->addSql('ALTER TABLE qualification_countries DROP FOREIGN KEY FK_3B36F040EC5A3719');
        $this->addSql('ALTER TABLE qualification_countries DROP FOREIGN KEY FK_3B36F0401D245085');
        $this->addSql('ALTER TABLE qualification_countries DROP FOREIGN KEY FK_3B36F040DBD0749A');
        $this->addSql('ALTER TABLE qualification_countries DROP FOREIGN KEY FK_3B36F0404232129B');
        $this->addSql('ALTER TABLE qualification_countries DROP FOREIGN KEY FK_3B36F04083BCCD5B');
        $this->addSql('ALTER TABLE qualification_countries DROP FOREIGN KEY FK_3B36F040AA87D8D8');
        $this->addSql('DROP TABLE bet_qualification_countries');
        $this->addSql('DROP TABLE qualification_countries');
    }
}
