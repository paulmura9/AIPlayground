<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250502173610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform ADD image_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE run CHANGE model_id model_id INT DEFAULT NULL, CHANGE prompt_id prompt_id INT DEFAULT NULL, CHANGE temperature temperature DOUBLE PRECISION NOT NULL, CHANGE actual_response actual_response VARCHAR(1000) NOT NULL, CHANGE rating rating DOUBLE PRECISION NOT NULL, CHANGE user_rating user_rating DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE platform DROP image_url');
        $this->addSql('ALTER TABLE run CHANGE model_id model_id INT NOT NULL, CHANGE prompt_id prompt_id INT NOT NULL, CHANGE temperature temperature INT NOT NULL, CHANGE actual_response actual_response LONGTEXT NOT NULL, CHANGE rating rating INT NOT NULL, CHANGE user_rating user_rating INT NOT NULL');
    }
}
