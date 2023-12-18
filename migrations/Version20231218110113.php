<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218110113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE invitation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE invitation_status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE invitation (id INT NOT NULL, recipient_id INT NOT NULL, to_event_id INT NOT NULL, status_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F11D61A2E92F8F78 ON invitation (recipient_id)');
        $this->addSql('CREATE INDEX IDX_F11D61A2BCAE7625 ON invitation (to_event_id)');
        $this->addSql('CREATE INDEX IDX_F11D61A26BF700BD ON invitation (status_id)');
        $this->addSql('CREATE TABLE invitation_status (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2E92F8F78 FOREIGN KEY (recipient_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2BCAE7625 FOREIGN KEY (to_event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A26BF700BD FOREIGN KEY (status_id) REFERENCES invitation_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE invitation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE invitation_status_id_seq CASCADE');
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A2E92F8F78');
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A2BCAE7625');
        $this->addSql('ALTER TABLE invitation DROP CONSTRAINT FK_F11D61A26BF700BD');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP TABLE invitation_status');
    }
}
