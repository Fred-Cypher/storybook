<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318153804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resume (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, hobbies LONGTEXT NOT NULL, legend VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_60C1D0A0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resume ADD CONSTRAINT FK_60C1D0A0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE portraits DROP FOREIGN KEY FK_31F4A4ABA76ED395');
        $this->addSql('DROP INDEX IDX_31F4A4ABA76ED395 ON portraits');
        $this->addSql('ALTER TABLE portraits DROP slug, DROP legend, DROP created_at, DROP updated_at, CHANGE user_id resume_id INT NOT NULL');
        $this->addSql('ALTER TABLE portraits ADD CONSTRAINT FK_31F4A4ABD262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id)');
        $this->addSql('CREATE INDEX IDX_31F4A4ABD262AF09 ON portraits (resume_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portraits DROP FOREIGN KEY FK_31F4A4ABD262AF09');
        $this->addSql('ALTER TABLE resume DROP FOREIGN KEY FK_60C1D0A0A76ED395');
        $this->addSql('DROP TABLE resume');
        $this->addSql('DROP INDEX IDX_31F4A4ABD262AF09 ON portraits');
        $this->addSql('ALTER TABLE portraits ADD slug VARCHAR(255) NOT NULL, ADD legend VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE resume_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE portraits ADD CONSTRAINT FK_31F4A4ABA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_31F4A4ABA76ED395 ON portraits (user_id)');
    }
}
