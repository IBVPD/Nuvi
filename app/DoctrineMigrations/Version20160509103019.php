<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160509103019 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP canCreateCases, DROP canCreateLabs, DROP canCreateRRLLabs, DROP canCreateNLLabs');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD canCreateCases TINYINT(1) NOT NULL, ADD canCreateLabs TINYINT(1) NOT NULL, ADD canCreateRRLLabs TINYINT(1) NOT NULL, ADD canCreateNLLabs TINYINT(1) NOT NULL');
    }
}
