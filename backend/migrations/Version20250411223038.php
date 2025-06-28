<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411223038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prompt CHANGE system_message system_message VARCHAR(1000) NOT NULL, CHANGE user_message user_message VARCHAR(1000) NOT NULL, CHANGE expected_result expected_result VARCHAR(1000) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prompt CHANGE system_message system_message LONGTEXT NOT NULL, CHANGE user_message user_message LONGTEXT NOT NULL, CHANGE expected_result expected_result LONGTEXT NOT NULL');
    }
}
