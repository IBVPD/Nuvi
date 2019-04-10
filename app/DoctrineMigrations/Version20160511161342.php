<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160511161342 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE event_logs (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, action VARCHAR(255) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(255) NOT NULL, object_class VARCHAR(255) NOT NULL, data LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE ext_log_entries');

        $this->addSql('ALTER TABLE rota_reference_labs DROP FOREIGN KEY rota_reference_labs_ibfk_1');
        $this->addSql('ALTER TABLE rota_reference_labs ADD CONSTRAINT FK_BC5C4EE1CABE0DA FOREIGN KEY (caseFile_id) REFERENCES rotavirus_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rota_national_labs DROP FOREIGN KEY rota_national_labs_ibfk_1');
        $this->addSql('ALTER TABLE rota_national_labs ADD CONSTRAINT FK_B30B5E78CABE0DA FOREIGN KEY (caseFile_id) REFERENCES rotavirus_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rotavirus_site_labs DROP FOREIGN KEY rotavirus_site_labs_ibfk_1');
        $this->addSql('ALTER TABLE rotavirus_site_labs ADD CONSTRAINT FK_5215E955CABE0DA FOREIGN KEY (caseFile_id) REFERENCES rotavirus_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ibd_reference_labs DROP FOREIGN KEY ibd_reference_labs_ibfk_1');
        $this->addSql('ALTER TABLE ibd_reference_labs ADD CONSTRAINT FK_C975A0E2CABE0DA FOREIGN KEY (caseFile_id) REFERENCES ibd_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ibd_national_labs DROP FOREIGN KEY ibd_national_labs_ibfk_1');
        $this->addSql('ALTER TABLE ibd_national_labs ADD CONSTRAINT FK_F046C1BACABE0DA FOREIGN KEY (caseFile_id) REFERENCES ibd_cases (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ibd_site_labs DROP FOREIGN KEY ibd_site_labs_ibfk_1');
        $this->addSql('ALTER TABLE ibd_site_labs ADD CONSTRAINT FK_B09CEA98CABE0DA FOREIGN KEY (caseFile_id) REFERENCES ibd_cases (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL COLLATE utf8_unicode_ci, object_class VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, version INT NOT NULL, data LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE event_logs');

        $this->addSql('ALTER TABLE rota_reference_labs DROP FOREIGN KEY rota_reference_labs_ibfk_1');
        $this->addSql('ALTER TABLE rota_reference_labs ADD CONSTRAINT FK_BC5C4EE1CABE0DA FOREIGN KEY (caseFile_id) REFERENCES rotavirus_cases (id)');
        $this->addSql('ALTER TABLE rota_national_labs DROP FOREIGN KEY rota_national_labs_ibfk_1');
        $this->addSql('ALTER TABLE rota_national_labs ADD CONSTRAINT FK_B30B5E78CABE0DA FOREIGN KEY (caseFile_id) REFERENCES rotavirus_cases (id)');
        $this->addSql('ALTER TABLE rotavirus_site_labs DROP FOREIGN KEY rotavirus_site_labs_ibfk_1');
        $this->addSql('ALTER TABLE rotavirus_site_labs ADD CONSTRAINT FK_5215E955CABE0DA FOREIGN KEY (caseFile_id) REFERENCES rotavirus_cases (id)');
        $this->addSql('ALTER TABLE ibd_reference_labs DROP FOREIGN KEY ibd_reference_labs_ibfk_1');
        $this->addSql('ALTER TABLE ibd_reference_labs ADD CONSTRAINT FK_C975A0E2CABE0DA FOREIGN KEY (caseFile_id) REFERENCES ibd_cases (id)');
        $this->addSql('ALTER TABLE ibd_national_labs DROP FOREIGN KEY ibd_national_labs_ibfk_1');
        $this->addSql('ALTER TABLE ibd_national_labs ADD CONSTRAINT FK_F046C1BACABE0DA FOREIGN KEY (caseFile_id) REFERENCES ibd_cases (id)');
        $this->addSql('ALTER TABLE ibd_site_labs DROP FOREIGN KEY ibd_site_labs_ibfk_1');
        $this->addSql('ALTER TABLE ibd_site_labs ADD CONSTRAINT FK_B09CEA98CABE0DA FOREIGN KEY (caseFile_id) REFERENCES ibd_cases (id)');
    }
}
