<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160511105343 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rota_reference_labs MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE rota_reference_labs DROP FOREIGN KEY `FK_BC5C4EE1CABE0DA`');
        $this->addSql('DROP INDEX UNIQ_BC5C4EE1CABE0DA ON rota_reference_labs');
        $this->addSql('ALTER TABLE rota_reference_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rota_reference_labs DROP id');
        $this->addSql('ALTER TABLE rota_reference_labs ADD PRIMARY KEY (caseFile_id)');
        $this->addSql('ALTER TABLE rota_reference_labs ADD FOREIGN KEY `FK_BC5C4EE1CABE0DA` (`caseFile_id`) REFERENCES `rotavirus_cases` (`id`)');

        $this->addSql('ALTER TABLE rota_national_labs DROP FOREIGN KEY `FK_B30B5E78CABE0DA`');
        $this->addSql('ALTER TABLE rota_national_labs MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_B30B5E78CABE0DA ON rota_national_labs');
        $this->addSql('ALTER TABLE rota_national_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rota_national_labs DROP id');
        $this->addSql('ALTER TABLE rota_national_labs ADD PRIMARY KEY (caseFile_id)');
        $this->addSql('ALTER TABLE rota_national_labs ADD FOREIGN KEY `FK_B30B5E78CABE0DA` (`caseFile_id`) REFERENCES `rotavirus_cases` (`id`)');


        $this->addSql('ALTER TABLE rotavirus_site_labs DROP FOREIGN KEY `FK_5215E955CABE0DA`');
        $this->addSql('ALTER TABLE rotavirus_site_labs MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE rotavirus_site_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rotavirus_site_labs DROP id');
        $this->addSql('ALTER TABLE rotavirus_site_labs ADD PRIMARY KEY (caseFile_id)');
        $this->addSql('ALTER TABLE rotavirus_site_labs ADD FOREIGN KEY `FK_5215E955CABE0DA` (`caseFile_id`) REFERENCES `rotavirus_cases` (`id`)');



        $this->addSql('ALTER TABLE ibd_reference_labs MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ibd_reference_labs DROP FOREIGN KEY `FK_C975A0E2CABE0DA`');
        $this->addSql('DROP INDEX UNIQ_C975A0E2CABE0DA ON ibd_reference_labs');
        $this->addSql('ALTER TABLE ibd_reference_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ibd_reference_labs DROP id');
        $this->addSql('ALTER TABLE ibd_reference_labs ADD PRIMARY KEY (caseFile_id)');
        $this->addSql('ALTER TABLE ibd_reference_labs ADD FOREIGN KEY `FK_C975A0E2CABE0DA` (`caseFile_id`) REFERENCES `ibd_cases` (`id`)');


        $this->addSql('ALTER TABLE ibd_national_labs DROP FOREIGN KEY `FK_F046C1BACABE0DA`');
        $this->addSql('ALTER TABLE ibd_national_labs MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_F046C1BACABE0DA ON ibd_national_labs');
        $this->addSql('ALTER TABLE ibd_national_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ibd_national_labs DROP id');
        $this->addSql('ALTER TABLE ibd_national_labs ADD PRIMARY KEY (caseFile_id)');
        $this->addSql('ALTER TABLE ibd_national_labs ADD FOREIGN KEY `FK_F046C1BACABE0DA` (`caseFile_id`) REFERENCES `ibd_cases` (`id`)');


        $this->addSql('ALTER TABLE ibd_site_labs DROP FOREIGN KEY `FK_B09CEA98CABE0DA`');
        $this->addSql('ALTER TABLE ibd_site_labs MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ibd_site_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ibd_site_labs DROP id');
        $this->addSql('ALTER TABLE ibd_site_labs ADD PRIMARY KEY (caseFile_id)');
        $this->addSql('ALTER TABLE ibd_site_labs ADD FOREIGN KEY `FK_B09CEA98CABE0DA` (`caseFile_id`) REFERENCES `ibd_cases` (`id`)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_national_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ibd_national_labs ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F046C1BACABE0DA ON ibd_national_labs (caseFile_id)');
        $this->addSql('ALTER TABLE ibd_national_labs ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ibd_reference_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ibd_reference_labs ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C975A0E2CABE0DA ON ibd_reference_labs (caseFile_id)');
        $this->addSql('ALTER TABLE ibd_reference_labs ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE ibd_site_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ibd_site_labs ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE ibd_site_labs ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE rota_national_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rota_national_labs ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B30B5E78CABE0DA ON rota_national_labs (caseFile_id)');
        $this->addSql('ALTER TABLE rota_national_labs ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE rota_reference_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rota_reference_labs ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC5C4EE1CABE0DA ON rota_reference_labs (caseFile_id)');
        $this->addSql('ALTER TABLE rota_reference_labs ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE rotavirus_site_labs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE rotavirus_site_labs ADD id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE rotavirus_site_labs ADD PRIMARY KEY (id)');
    }
}
