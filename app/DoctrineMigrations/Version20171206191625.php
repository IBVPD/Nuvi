<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171206191625 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE import_results CHANGE pheanstalkStatus status VARCHAR(255) NOT NULL, DROP pheanstalkJobId, CHANGE pheanstalkstacktrace stackTrace LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE import_results ADD pheanstalkJobId INT DEFAULT NULL, CHANGE status pheanstalkStatus VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE stacktrace pheanstalkStackTrace LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
