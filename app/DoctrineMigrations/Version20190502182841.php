<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20190502182841 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_E2AF9244CABE0DA ON pneu_site_labs');
        $this->addSql('DROP INDEX UNIQ_C94F22D0CABE0DA ON mening_site_labs');
        $this->addSql('ALTER TABLE mening_site_labs DROP rl_csf_sent, DROP rl_csf_date, DROP rl_isol_csf_sent, DROP rl_isol_csf_date, DROP rl_isol_blood_sent, DROP rl_isol_blood_date, DROP rl_broth_sent, DROP rl_broth_date, DROP rl_other_sent, DROP rl_other_date');
        $this->addSql('DROP INDEX UNIQ_5215E955CABE0DA ON rotavirus_site_labs');
        $this->addSql('ALTER TABLE rotavirus_site_labs ADD updatedAt DATETIME NOT NULL, ADD status INT NOT NULL COMMENT \'(DC2Type:CaseStatus)\', DROP sentToReferenceLab, DROP sentToNationalLab');
        $this->addSql('DROP INDEX UNIQ_B09CEA98CABE0DA ON ibd_site_labs');    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_B09CEA98CABE0DA ON ibd_site_labs (caseFile_id)');
        $this->addSql('ALTER TABLE mening_site_labs ADD rl_csf_sent TINYINT(1) DEFAULT NULL, ADD rl_csf_date DATE DEFAULT NULL, ADD rl_isol_csf_sent TINYINT(1) DEFAULT NULL, ADD rl_isol_csf_date DATE DEFAULT NULL, ADD rl_isol_blood_sent TINYINT(1) DEFAULT NULL, ADD rl_isol_blood_date DATE DEFAULT NULL, ADD rl_broth_sent TINYINT(1) DEFAULT NULL, ADD rl_broth_date DATE DEFAULT NULL, ADD rl_other_sent TINYINT(1) DEFAULT NULL, ADD rl_other_date DATE DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C94F22D0CABE0DA ON mening_site_labs (caseFile_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E2AF9244CABE0DA ON pneu_site_labs (caseFile_id)');
        $this->addSql('ALTER TABLE rotavirus_site_labs ADD sentToReferenceLab TINYINT(1) NOT NULL, ADD sentToNationalLab TINYINT(1) NOT NULL, DROP updatedAt, DROP status');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5215E955CABE0DA ON rotavirus_site_labs (caseFile_id)');
    }
}
