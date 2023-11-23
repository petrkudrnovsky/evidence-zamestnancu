<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231123140818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add active and profilePhotoFilename properties to Employee';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ADD active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE employee ADD profile_photo_filename VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE employee DROP active');
        $this->addSql('ALTER TABLE employee DROP profile_photo_filename');
    }
}
