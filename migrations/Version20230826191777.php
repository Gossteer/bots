<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230826191777 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'doctrine messenger';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE database_queue (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E50F9AFB7336F0 ON database_queue (queue_name)');
        $this->addSql('CREATE INDEX IDX_E50F9AE3BD61CE ON database_queue (available_at)');
        $this->addSql('CREATE INDEX IDX_E50F9A16BA31DB ON database_queue (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_database_queue() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'database_queue\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON database_queue;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON database_queue FOR EACH ROW EXECUTE PROCEDURE notify_database_queue();');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE database_queue');
    }
}
