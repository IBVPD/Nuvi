<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170228170131 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_site_labs ADD rl_csf_sent TINYINT(1) DEFAULT NULL, ADD rl_isol_csf_sent TINYINT(1) DEFAULT NULL, ADD rl_isol_blood_sent TINYINT(1) DEFAULT NULL, ADD rl_broth_sent TINYINT(1) DEFAULT NULL, ADD rl_other_sent TINYINT(1) DEFAULT NULL, ADD rl_other_date DATE DEFAULT NULL, ADD nl_csf_sent TINYINT(1) DEFAULT NULL, ADD nl_csf_date DATE DEFAULT NULL, ADD nl_isol_csf_sent TINYINT(1) DEFAULT NULL, ADD nl_isol_csf_date DATE DEFAULT NULL, ADD nl_isol_blood_sent TINYINT(1) DEFAULT NULL, ADD nl_isol_blood_date DATE DEFAULT NULL, ADD nl_broth_sent TINYINT(1) DEFAULT NULL, ADD nl_broth_date DATE DEFAULT NULL, ADD nl_other_sent TINYINT(1) DEFAULT NULL, ADD nl_other_date DATE DEFAULT NULL, DROP sentToReferenceLab, DROP sentToNationalLab');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_site_labs ADD sentToReferenceLab TINYINT(1) NOT NULL, ADD sentToNationalLab TINYINT(1) NOT NULL, DROP rl_csf_sent, DROP rl_isol_csf_sent, DROP rl_isol_blood_sent, DROP rl_broth_sent, DROP rl_other_sent, DROP rl_other_date, DROP nl_csf_sent, DROP nl_csf_date, DROP nl_isol_csf_sent, DROP nl_isol_csf_date, DROP nl_isol_blood_sent, DROP nl_isol_blood_date, DROP nl_broth_sent, DROP nl_broth_date, DROP nl_other_sent, DROP nl_other_date');
    }
}
