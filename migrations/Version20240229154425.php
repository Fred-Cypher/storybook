<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229154425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE illustrations (id INT AUTO_INCREMENT NOT NULL, recent_games_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_830A942DD0892B77 (recent_games_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE illustrations ADD CONSTRAINT FK_830A942DD0892B77 FOREIGN KEY (recent_games_id) REFERENCES recent_games (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE illustrations DROP FOREIGN KEY FK_830A942DD0892B77');
        $this->addSql('DROP TABLE illustrations');
    }
}
