<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181213195808 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rota_national_labs ADD elisaDone INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD elisaKit INT DEFAULT NULL COMMENT \'(DC2Type:ElisaKit)\', ADD elisaKitOther VARCHAR(255) DEFAULT NULL, ADD elisaLoadNumber VARCHAR(255) DEFAULT NULL, ADD elisaExpiryDate DATE DEFAULT NULL, ADD elisaTestDate DATE DEFAULT NULL, ADD elisaResult INT DEFAULT NULL COMMENT \'(DC2Type:ElisaResult)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rota_national_labs DROP elisaDone, DROP elisaKit, DROP elisaKitOther, DROP elisaLoadNumber, DROP elisaExpiryDate, DROP elisaTestDate, DROP elisaResult');
    }
}
