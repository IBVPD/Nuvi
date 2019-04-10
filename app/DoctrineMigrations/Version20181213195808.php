<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20181213195808 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rota_national_labs ADD elisaDone INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD elisaKit INT DEFAULT NULL COMMENT \'(DC2Type:ElisaKit)\', ADD elisaKitOther VARCHAR(255) DEFAULT NULL, ADD elisaLoadNumber VARCHAR(255) DEFAULT NULL, ADD elisaExpiryDate DATE DEFAULT NULL, ADD elisaTestDate DATE DEFAULT NULL, ADD elisaResult INT DEFAULT NULL COMMENT \'(DC2Type:ElisaResult)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rota_national_labs DROP elisaDone, DROP elisaKit, DROP elisaKitOther, DROP elisaLoadNumber, DROP elisaExpiryDate, DROP elisaTestDate, DROP elisaResult');
    }
}
