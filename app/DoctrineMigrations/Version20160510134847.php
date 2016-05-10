<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160510134847 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rotavirus_cases CHANGE disch_class disch_class INT DEFAULT NULL COMMENT \'(DC2Type:RVDischargeClassification)\', CHANGE rv_received rv_received INT DEFAULT NULL COMMENT \'(DC2Type:VaccinationReceived)\', CHANGE rv_type rv_type INT DEFAULT NULL COMMENT \'(DC2Type:RVVaccinationType)\', CHANGE disch_outcome disch_outcome INT DEFAULT NULL COMMENT \'(DC2Type:RVDischargeOutcome)\'');
        $this->addSql('ALTER TABLE ibd_cases CHANGE mening_type mening_type INT DEFAULT NULL COMMENT \'(DC2Type:IBDVaccinationType)\', CHANGE disch_outcome disch_outcome INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeOutcome)\', CHANGE disch_dx disch_dx INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeDiagnosis)\', CHANGE disch_class disch_class INT DEFAULT NULL COMMENT \'(DC2Type:IBDDischargeClassification)\'');
        $this->addSql('ALTER TABLE sites CHANGE ibdIntenseSupport ibdIntenseSupport INT DEFAULT NULL COMMENT \'(DC2Type:IntenseSupport)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rotavirus_cases CHANGE disch_class disch_class INT DEFAULT NULL COMMENT \'(DC2Type:RotaVirusDischargeClassification)\', CHANGE rv_received rv_received INT DEFAULT NULL COMMENT \'(DC2Type:RotavirusVaccinationReceived)\', CHANGE rv_type rv_type INT DEFAULT NULL COMMENT \'(DC2Type:RotavirusVaccinationType)\', CHANGE disch_outcome disch_outcome INT DEFAULT NULL COMMENT \'(DC2Type:RotavirusDischargeOutcome)\'');
        $this->addSql('ALTER TABLE ibd_cases CHANGE mening_type mening_type INT DEFAULT NULL COMMENT \'(DC2Type:MeningitisVaccinationType)\', CHANGE disch_outcome disch_outcome INT DEFAULT NULL COMMENT \'(DC2Type:DischargeOutcome)\', CHANGE disch_dx disch_dx INT DEFAULT NULL COMMENT \'(DC2Type:DischargeDiagnosis)\', CHANGE disch_class disch_class INT DEFAULT NULL COMMENT \'(DC2Type:DischargeClassification)\'');
        $this->addSql('ALTER TABLE sites CHANGE ibdIntenseSupport ibdIntenseSupport INT DEFAULT NULL COMMENT \'(DC2Type:IBDIntenseSupport)\'');
    }
}
