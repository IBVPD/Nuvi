<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


class Version20170228000306 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_site_labs ADD blood_lab_date DATE DEFAULT NULL, ADD blood_lab_time TIME DEFAULT NULL, ADD other_id VARCHAR(255) DEFAULT NULL, ADD other_lab_date DATE DEFAULT NULL, ADD other_lab_time TIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_site_labs DROP blood_lab_date, DROP blood_lab_time, DROP other_id, DROP other_lab_date, DROP other_lab_time');
    }
}
