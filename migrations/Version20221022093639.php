<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221022093639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet_user (id INT AUTO_INCREMENT NOT NULL, matches_id INT NOT NULL, user_id INT NOT NULL, score_countrie_1 INT NOT NULL, score_countrie_2 INT NOT NULL, INDEX IDX_A84505754B30DD19 (matches_id), INDEX IDX_A8450575A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet_user ADD CONSTRAINT FK_A84505754B30DD19 FOREIGN KEY (matches_id) REFERENCES matches (id)');
        $this->addSql('ALTER TABLE bet_user ADD CONSTRAINT FK_A8450575A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet_user DROP FOREIGN KEY FK_A84505754B30DD19');
        $this->addSql('ALTER TABLE bet_user DROP FOREIGN KEY FK_A8450575A76ED395');
        $this->addSql('DROP TABLE bet_user');
    }
}
