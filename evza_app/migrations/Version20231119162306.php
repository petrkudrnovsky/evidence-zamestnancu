<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119162306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update Employee->phoneNumber from INT to VARCHAR(255)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX idx_on_account_with_employee_id RENAME TO IDX_7D3656A48C03F15C');
        $this->addSql('ALTER TABLE employee ALTER phone_number TYPE VARCHAR(255)');
        $this->addSql('ALTER INDEX idx_on_employee_position_with_employee_id RENAME TO IDX_D613B5328C03F15C');
        $this->addSql('ALTER INDEX idx_on_employee_position_with_position_id RENAME TO IDX_D613B532DD842E46');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER INDEX idx_d613b532dd842e46 RENAME TO idx_on_employee_position_with_position_id');
        $this->addSql('ALTER INDEX idx_d613b5328c03f15c RENAME TO idx_on_employee_position_with_employee_id');
        $this->addSql('ALTER INDEX idx_7d3656a48c03f15c RENAME TO idx_on_account_with_employee_id');
        $this->addSql('ALTER TABLE employee ALTER phone_number TYPE INT');
    }
}
