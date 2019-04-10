<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Version20190409051306 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rota_national_labs ADD stoolSentToRRL INT DEFAULT NULL COMMENT \'(DC2Type:TripleChoice)\', ADD stoolSentToRRLDate DATE DEFAULT NULL');
    }

    public function postUp(Schema $schema): void
    {
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
        $this->entityManager->getConnection()->exec('UPDATE rota_national_labs rnl JOIN rotavirus_site_labs rsl ON rnl.caseFile_id = rsl.caseFile_id SET rnl.stoolSentToRRL = rsl.stoolSentToRRL, rnl.stoolSentToRRLDate = rsl.stoolSentToRRLDate');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rota_national_labs DROP stoolSentToRRL, DROP stoolSentToRRLDate');
    }
}
