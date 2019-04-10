<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20180217184626 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_site_labs ADD blood_second_id VARCHAR(255) DEFAULT NULL, ADD blood_second_lab_date DATE DEFAULT NULL, ADD blood_second_lab_time TIME DEFAULT NULL, ADD blood_second_cult_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD blood_second_gram_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD blood_second_pcr_done INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD blood_second_cult_result INT DEFAULT NULL COMMENT \'(DC2Type:CultureResult)\', ADD blood_second_cult_other VARCHAR(255) DEFAULT NULL, ADD blood_second_gram_stain INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', ADD blood_second_gram_result INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', ADD blood_second_gram_other VARCHAR(255) DEFAULT NULL, ADD blood_second_pcr_result INT DEFAULT NULL COMMENT \'(DC2Type:PCRResult)\', ADD blood_second_pcr_other VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_site_labs DROP blood_second_id, DROP blood_second_lab_date, DROP blood_second_lab_time, DROP blood_second_cult_done, DROP blood_second_gram_done, DROP blood_second_pcr_done, DROP blood_second_cult_result, DROP blood_second_cult_other, DROP blood_second_gram_stain, DROP blood_second_gram_result, DROP blood_second_gram_other, DROP blood_second_pcr_result, DROP blood_second_pcr_other');
    }
}
