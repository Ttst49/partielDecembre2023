<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218134129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE suggestion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE supported_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE suggestion (id INT NOT NULL, supported_by_id INT DEFAULT NULL, associated_event_id INT NOT NULL, is_supported BOOLEAN NOT NULL, title TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DD80F31BDAB09DFA ON suggestion (supported_by_id)');
        $this->addSql('CREATE INDEX IDX_DD80F31B5D24FD ON suggestion (associated_event_id)');
        $this->addSql('CREATE TABLE supported (id INT NOT NULL, associated_to_suggestion_id INT DEFAULT NULL, associated_to_event_id INT NOT NULL, title TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F724DB04361D2561 ON supported (associated_to_suggestion_id)');
        $this->addSql('CREATE INDEX IDX_F724DB04BD9EB002 ON supported (associated_to_event_id)');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31BDAB09DFA FOREIGN KEY (supported_by_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE suggestion ADD CONSTRAINT FK_DD80F31B5D24FD FOREIGN KEY (associated_event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supported ADD CONSTRAINT FK_F724DB04361D2561 FOREIGN KEY (associated_to_suggestion_id) REFERENCES suggestion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supported ADD CONSTRAINT FK_F724DB04BD9EB002 FOREIGN KEY (associated_to_event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE suggestion_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE supported_id_seq CASCADE');
        $this->addSql('ALTER TABLE suggestion DROP CONSTRAINT FK_DD80F31BDAB09DFA');
        $this->addSql('ALTER TABLE suggestion DROP CONSTRAINT FK_DD80F31B5D24FD');
        $this->addSql('ALTER TABLE supported DROP CONSTRAINT FK_F724DB04361D2561');
        $this->addSql('ALTER TABLE supported DROP CONSTRAINT FK_F724DB04BD9EB002');
        $this->addSql('DROP TABLE suggestion');
        $this->addSql('DROP TABLE supported');
    }
}
