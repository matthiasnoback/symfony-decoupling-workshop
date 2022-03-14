<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220314105218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email_address VARCHAR(255) NOT NULL)');
        $this->addSql('DROP INDEX IDX_CFBDFA148DB60186');
        $this->addSql('CREATE TEMPORARY TABLE __temp__note AS SELECT id, task_id, note FROM note');
        $this->addSql('DROP TABLE note');
        $this->addSql('CREATE TABLE note (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id INTEGER NOT NULL, note VARCHAR(255) NOT NULL, CONSTRAINT FK_CFBDFA148DB60186 FOREIGN KEY (task_id) REFERENCES task (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO note (id, task_id, note) SELECT id, task_id, note FROM __temp__note');
        $this->addSql('DROP TABLE __temp__note');
        $this->addSql('CREATE INDEX IDX_CFBDFA148DB60186 ON note (task_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, task, due_date FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, assigned_to_id INTEGER DEFAULT NULL, task VARCHAR(255) NOT NULL, due_date DATE NOT NULL, CONSTRAINT FK_527EDB25F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO task (id, task, due_date) SELECT id, task, due_date FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('CREATE INDEX IDX_527EDB25F4BD7827 ON task (assigned_to_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_CFBDFA148DB60186');
        $this->addSql('CREATE TEMPORARY TABLE __temp__note AS SELECT id, task_id, note FROM note');
        $this->addSql('DROP TABLE note');
        $this->addSql('CREATE TABLE note (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id INTEGER NOT NULL, note VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO note (id, task_id, note) SELECT id, task_id, note FROM __temp__note');
        $this->addSql('DROP TABLE __temp__note');
        $this->addSql('CREATE INDEX IDX_CFBDFA148DB60186 ON note (task_id)');
        $this->addSql('DROP INDEX IDX_527EDB25F4BD7827');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, task, due_date FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task VARCHAR(255) NOT NULL, due_date DATE NOT NULL)');
        $this->addSql('INSERT INTO task (id, task, due_date) SELECT id, task, due_date FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
    }
}
