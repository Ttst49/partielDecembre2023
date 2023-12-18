<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218142109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supported_standalone ADD supported_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE supported_standalone ADD CONSTRAINT FK_9496EF8DAB09DFA FOREIGN KEY (supported_by_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9496EF8DAB09DFA ON supported_standalone (supported_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE supported_standalone DROP CONSTRAINT FK_9496EF8DAB09DFA');
        $this->addSql('DROP INDEX IDX_9496EF8DAB09DFA');
        $this->addSql('ALTER TABLE supported_standalone DROP supported_by_id');
    }
}
