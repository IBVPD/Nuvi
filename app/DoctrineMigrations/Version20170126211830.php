<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170126211830 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_site_labs ADD pleural_fluid_culture_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD pleural_fluid_culture_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', ADD pleural_fluid_culture_other VARCHAR(255) DEFAULT NULL, ADD pleural_fluid_gram_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD pleural_fluid_gram_result INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', ADD pleural_fluid_gram_result_organism INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', ADD pleural_fluid_gram_result_other VARCHAR(255) DEFAULT NULL, ADD pleural_fluid_pcr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD pleural_fluid_pcr_result INT DEFAULT NULL COMMENT \'(DC2Type:PCRResult)\', ADD pleural_fluid_pcr_other VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_site_labs DROP pleural_fluid_culture_done, DROP pleural_fluid_culture_result, DROP pleural_fluid_culture_other, DROP pleural_fluid_gram_done, DROP pleural_fluid_gram_result, DROP pleural_fluid_gram_result_organism, DROP pleural_fluid_gram_result_other, DROP pleural_fluid_pcr_done, DROP pleural_fluid_pcr_result, DROP pleural_fluid_pcr_other');
    }
}
