<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230729111133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__artiste AS SELECT id, nom, description, site_web, photo, type, created_at, slug FROM artiste');
        $this->addSql('DROP TABLE artiste');
        $this->addSql('CREATE TABLE artiste (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, site_web VARCHAR(255) DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , slug VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO artiste (id, nom, description, site_web, photo, type, created_at, slug) SELECT id, nom, description, site_web, photo, type, created_at, slug FROM __temp__artiste');
        $this->addSql('DROP TABLE __temp__artiste');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artiste ADD COLUMN genre_musical VARCHAR(255) NOT NULL');
    }
}
