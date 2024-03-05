<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229145706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE covers (id INT AUTO_INCREMENT NOT NULL, games_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F08DF1B297FFC673 (games_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drawings (id INT AUTO_INCREMENT NOT NULL, tales_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_25F9D34FAE97231E (tales_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE covers ADD CONSTRAINT FK_F08DF1B297FFC673 FOREIGN KEY (games_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE drawings ADD CONSTRAINT FK_25F9D34FAE97231E FOREIGN KEY (tales_id) REFERENCES tales (id)');
        $this->addSql('ALTER TABLE games DROP cover');
        $this->addSql('ALTER TABLE tales DROP drawing');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE available_at available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covers DROP FOREIGN KEY FK_F08DF1B297FFC673');
        $this->addSql('ALTER TABLE drawings DROP FOREIGN KEY FK_25F9D34FAE97231E');
        $this->addSql('DROP TABLE covers');
        $this->addSql('DROP TABLE drawings');
        $this->addSql('ALTER TABLE games ADD cover VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE tales ADD drawing VARCHAR(255) DEFAULT NULL');
    }
}
