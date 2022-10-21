<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021122746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE countries_teams (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, iso_flag VARCHAR(20) NOT NULL, categories VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matches (id INT AUTO_INCREMENT NOT NULL, countrie_1_id INT DEFAULT NULL, countrie_2_id INT DEFAULT NULL, date DATETIME NOT NULL, score_countrie_1 INT DEFAULT NULL, score_countrie_2 INT DEFAULT NULL, type_match INT NOT NULL, INDEX IDX_62615BAC829810E (countrie_1_id), INDEX IDX_62615BADA9C2EE0 (countrie_2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BAC829810E FOREIGN KEY (countrie_1_id) REFERENCES countries_teams (id)');
        $this->addSql('ALTER TABLE matches ADD CONSTRAINT FK_62615BADA9C2EE0 FOREIGN KEY (countrie_2_id) REFERENCES countries_teams (id)');
        $this->addSql("INSERT INTO `countries_teams` (`id`, `name`, `iso_flag`, `categories`) VALUES (NULL, 'Qatar', 'qa', 'A'), (NULL, 'Équateur', 'ec', 'A'),
                                                                              (NULL, 'Sénégal', 'sn', 'A'), (NULL, 'Pays-Bas', 'nl', 'A'), 
                                                                              (NULL, 'Angleterre', 'gb', 'B'), (NULL, 'Iran', 'ir', 'B'),
                                                                              (NULL, 'États-Unis', 'us', 'B'), (NULL, 'Pays de Galles', 'gb-wls', 'B'),
                                                                              (NULL, 'Argentine', 'ar', 'C'), (NULL, 'Arabie saoudite', 'sa', 'C'),
                                                                              (NULL, 'Mexique', 'mx', 'C'), (NULL, 'Pologne', 'pl', 'C'),
                                                                              (NULL, 'France', 'fr', 'D'), (NULL, 'Australie', 'au', 'D'),
                                                                              (NULL, 'Danemark', 'dk', 'D'), (NULL, 'Tunisie', 'tn', 'D'),
                                                                              (NULL, 'Espagne', 'es', 'E'), (NULL, 'Costa Rica', 'cr', 'E'),
                                                                              (NULL, 'Allemagne', 'de', 'E'), (NULL, 'Japon', 'jp', 'E'),
                                                                              (NULL, 'Belgique', 'be', 'F'), (NULL, 'Canada', 'ca', 'F'),
                                                                              (NULL, 'Maroc', 'ma', 'F'), (NULL, 'Croatie', 'hr', 'F'),
                                                                              (NULL, 'Brésil', 'br', 'G'), (NULL, 'Serbie', 'rs', 'G'),
                                                                              (NULL, 'Suisse', 'ch', 'G'), (NULL, 'Cameroun', 'cm', 'G'),
                                                                              (NULL, 'Portugal', 'pt', 'H'), (NULL, 'Ghana', 'gh', 'H'),
                                                                              (NULL, 'Uruguay', 'uy', 'H'), (NULL, 'Corée du Sud', 'kr', 'H');");
        $this->addSql("INSERT INTO `matches` (`id`, `countrie_1_id`, `countrie_2_id`, `date`, `score_countrie_1`, `score_countrie_2`, `type_match`) VALUES 
                                                                                            (NULL, '1', '2', '2022-11-20 17:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '5', '6', '2022-11-21 14:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '3', '4', '2022-11-21 17:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '7', '8', '2022-11-21 20:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '9', '10', '2022-11-22 11:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '15', '16', '2022-11-22 14:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '11', '12', '2022-11-22 17:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '13', '14', '2022-11-22 20:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '23', '24', '2022-11-23 11:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '19', '20', '2022-11-23 14:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '17', '18', '2022-11-23 17:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '21', '22', '2022-11-23 20:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '27', '28', '2022-11-24 11:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '31', '32', '2022-11-24 14:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '29', '30', '2022-11-24 17:00:00.000000', NULL, NULL, 7),
                                                                                            (NULL, '25', '26', '2022-11-24 20:00:00.000000', NULL, NULL, 7),       
                                                                                            (NULL, '8', '6', '2022-11-25 11:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '1', '3', '2022-11-25 14:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '4', '2', '2022-11-25 17:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '5', '7', '2022-11-25 20:00:00.000000', NULL, NULL, 6),                 
                                                                                            (NULL, '16', '14', '2022-11-26 11:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '12', '10', '2022-11-26 14:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '13', '15', '2022-11-26 17:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '9', '11', '2022-11-26 20:00:00.000000', NULL, NULL, 6),                           
                                                                                            (NULL, '20', '18', '2022-11-27 11:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '21', '23', '2022-11-27 14:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '24', '22', '2022-11-27 17:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '17', '19', '2022-11-27 20:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '28', '26', '2022-11-28 11:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '32', '30', '2022-11-28 14:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '25', '27', '2022-11-28 17:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '29', '31', '2022-11-28 20:00:00.000000', NULL, NULL, 6),
                                                                                            (NULL, '2', '3', '2022-11-29 16:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '4', '1', '2022-11-29 16:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '6', '7', '2022-11-29 20:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '8', '5', '2022-11-29 20:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '16', '13', '2022-11-30 16:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '14', '15', '2022-11-30 16:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '12', '9', '2022-11-30 20:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '10', '11', '2022-11-30 20:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '24', '21', '2022-12-01 16:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '22', '23', '2022-12-01 16:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '20', '17', '2022-12-01 20:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '18', '19', '2022-12-01 20:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '32', '29', '2022-12-02 16:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '30', '31', '2022-12-02 16:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '26', '27', '2022-12-02 20:00:00.000000', NULL, NULL, 5),
                                                                                            (NULL, '28', '25', '2022-12-02 20:00:00.000000', NULL, NULL, 5),                                          
                                                                                            (NULL, NULL, NULL, '2022-12-03 16:00:00.000000', NULL, NULL, 4),
                                                                                            (NULL, NULL, NULL, '2022-12-03 20:00:00.000000', NULL, NULL, 4),
                                                                                            (NULL, NULL, NULL, '2022-12-04 16:00:00.000000', NULL, NULL, 4),
                                                                                            (NULL, NULL, NULL, '2022-12-04 20:00:00.000000', NULL, NULL, 4),
                                                                                            (NULL, NULL, NULL, '2022-12-05 16:00:00.000000', NULL, NULL, 4),
                                                                                            (NULL, NULL, NULL, '2022-12-05 20:00:00.000000', NULL, NULL, 4),
                                                                                            (NULL, NULL, NULL, '2022-12-06 16:00:00.000000', NULL, NULL, 4),
                                                                                            (NULL, NULL, NULL, '2022-12-06 20:00:00.000000', NULL, NULL, 4),
                                                                                            (NULL, NULL, NULL, '2022-12-09 16:00:00.000000', NULL, NULL, 3),
                                                                                            (NULL, NULL, NULL, '2022-12-09 20:00:00.000000', NULL, NULL, 3),
                                                                                            (NULL, NULL, NULL, '2022-12-10 16:00:00.000000', NULL, NULL, 3),
                                                                                            (NULL, NULL, NULL, '2022-12-10 20:00:00.000000', NULL, NULL, 3),
                                                                                            (NULL, NULL, NULL, '2022-12-13 20:00:00.000000', NULL, NULL, 2),
                                                                                            (NULL, NULL, NULL, '2022-12-14 20:00:00.000000', NULL, NULL, 2),
                                                                                            (NULL, NULL, NULL, '2022-12-18 16:00:00.000000', NULL, NULL, 1);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BAC829810E');
        $this->addSql('ALTER TABLE matches DROP FOREIGN KEY FK_62615BADA9C2EE0');
        $this->addSql('DROP TABLE countries_teams');
        $this->addSql('DROP TABLE matches');
    }
}
