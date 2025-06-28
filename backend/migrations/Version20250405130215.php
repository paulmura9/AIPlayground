<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405130215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, platform_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D79572D9FFE6496F (platform_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, base_url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prompt (id INT AUTO_INCREMENT NOT NULL, scope_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, system_message LONGTEXT NOT NULL, user_message LONGTEXT NOT NULL, expected_result LONGTEXT NOT NULL, INDEX IDX_62EF6C38682B5931 (scope_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE run (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, prompt_id INT NOT NULL, temperature INT NOT NULL, actual_response LONGTEXT NOT NULL, rating INT NOT NULL, user_rating INT NOT NULL, INDEX IDX_5076A4C07975B7E7 (model_id), INDEX IDX_5076A4C0B5C4AA38 (prompt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scope (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id)');
        $this->addSql('ALTER TABLE prompt ADD CONSTRAINT FK_62EF6C38682B5931 FOREIGN KEY (scope_id) REFERENCES scope (id)');
        $this->addSql('ALTER TABLE run ADD CONSTRAINT FK_5076A4C07975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE run ADD CONSTRAINT FK_5076A4C0B5C4AA38 FOREIGN KEY (prompt_id) REFERENCES prompt (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9FFE6496F');
        $this->addSql('ALTER TABLE prompt DROP FOREIGN KEY FK_62EF6C38682B5931');
        $this->addSql('ALTER TABLE run DROP FOREIGN KEY FK_5076A4C07975B7E7');
        $this->addSql('ALTER TABLE run DROP FOREIGN KEY FK_5076A4C0B5C4AA38');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE prompt');
        $this->addSql('DROP TABLE run');
        $this->addSql('DROP TABLE scope');
    }
}
