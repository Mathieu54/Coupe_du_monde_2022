<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221030104309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD groupes_id INT DEFAULT NULL, ADD valide_register TINYINT(1) NOT NULL, ADD paid TINYINT(1) NOT NULL, ADD url_picture VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649305371B FOREIGN KEY (groupes_id) REFERENCES user_groups (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649305371B ON user (groupes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649305371B');
        $this->addSql('DROP TABLE user_groups');
        $this->addSql('DROP INDEX IDX_8D93D649305371B ON `user`');
        $this->addSql('ALTER TABLE `user` DROP groupes_id, DROP valide_register, DROP paid, DROP url_picture');
    }
}
