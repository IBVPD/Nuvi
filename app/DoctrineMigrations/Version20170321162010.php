<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321162010 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_national_labs ADD rl_isol_csf_sent TINYINT(1) DEFAULT NULL, ADD rl_isol_csf_date DATE DEFAULT NULL, ADD rl_isol_blood_sent TINYINT(1) DEFAULT NULL, ADD rl_isol_blood_date DATE DEFAULT NULL, ADD rl_other_sent TINYINT(1) DEFAULT NULL, ADD rl_other_date DATE DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_national_labs DROP rl_isol_csf_sent, DROP rl_isol_csf_date, DROP rl_isol_blood_sent, DROP rl_isol_blood_date, DROP rl_other_sent, DROP rl_other_date');
    }
}
