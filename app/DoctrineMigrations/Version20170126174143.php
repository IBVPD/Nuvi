<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20170126174143 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_cases ADD blood_number_of_samples INT DEFAULT NULL, ADD pleural_fluid_collected INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD pleural_fluid_collect_date DATE DEFAULT NULL, ADD pleural_fluid_collect_time TIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ibd_cases DROP blood_number_of_samples, DROP pleural_fluid_collected, DROP pleural_fluid_collect_date, DROP pleural_fluid_collect_time');
    }
}
