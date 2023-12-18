<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218140811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE supported_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE supported_standalone_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE supported_standalone (id INT NOT NULL, associated_event_id INT NOT NULL, title TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9496EF85D24FD ON supported_standalone (associated_event_id)');
        $this->addSql('ALTER TABLE supported_standalone ADD CONSTRAINT FK_9496EF85D24FD FOREIGN KEY (associated_event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE supported_standalone_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE supported_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE supported_standalone DROP CONSTRAINT FK_9496EF85D24FD');
        $this->addSql('DROP TABLE supported_standalone');
    }
}
