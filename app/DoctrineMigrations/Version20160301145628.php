<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160301145628 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acls CHANGE object_id object_id VARCHAR(15) NOT NULL');
        $this->addSql('ALTER TABLE rotavirus_cases CHANGE dob birthdate DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE ibd_cases CHANGE dob birthdate DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE ibd_site_labs CHANGE csfGramResult csfGramStain INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', CHANGE bloodGramResult bloodGramStain INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', CHANGE csfGramResultOrganism csfGramResult INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\', CHANGE bloodGramResultOrganism bloodGramResult INT DEFAULT NULL COMMENT \'(DC2Type:GramStainResult)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acls CHANGE object_id object_id CHAR(15) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE ibd_cases CHANGE birthdate dob DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE ibd_site_labs ADD csfGramResultOrganism INT DEFAULT NULL COMMENT \'(DC2Type:GramStainOrganism)\', ADD bloodGramResultOrganism INT DEFAULT NULL COMMENT \'(DC2Type:GramStainOrganism)\', DROP csfGramStain, DROP bloodGramStain, CHANGE csfGramResult csfGramResult INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\', CHANGE bloodGramResult bloodGramResult INT DEFAULT NULL COMMENT \'(DC2Type:GramStain)\'');
        $this->addSql('ALTER TABLE rotavirus_cases CHANGE birthdate dob DATE DEFAULT NULL');
    }
}
