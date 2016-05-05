<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManagerInterface;
use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Form\Types\Role;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160502000000 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $entityMgr;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acls ADD options LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function postUp(Schema $schema)
    {
        $this->entityMgr = $this->container->get('doctrine.orm.entity_manager');
        $users = $this->entityMgr->getRepository('NSSentinelBundle:User')->createQueryBuilder('u')->leftJoin('u.acls', 'a')->getQuery()->getResult();

        foreach ($users as $user) {
            $options = array();

            if ($user->getCanCreateCases()) {
                $options[] = 'case';
            }

            if ($user->getCanCreateLabs()) {
                $options[] = 'lab';
            }

            if ($user->getCanCreateNLLabs()) {
                $options[] = 'nl';
            }

            if ($user->getCanCreateRRLLabs()) {
                $options[] = 'rrl';
            }

            $this->modifyAcls($user, Role::REGION_API, Role::REGION_IMPORT, $options);
            $this->modifyAcls($user, Role::COUNTRY_API, Role::COUNTRY_IMPORT, $options);
            $this->modifyAcls($user, Role::SITE_API, Role::SITE_IMPORT, $options);
            $this->entityMgr->persist($user);
        }

        $this->entityMgr->flush();
    }

    private function modifyAcls(User $user, $apiRole, $importRole, array &$options)
    {
        $toReplicate = array();

        foreach ($user->getAcls() as $acl) {
            if ($acl->getType()->equal($apiRole)) {
                $options[] = 'api';
            } elseif ($acl->getType()->equal($importRole)) {
                $options[] = 'import';
            } else {
                $toReplicate[] = $acl;
            }

            $user->removeAcl($acl);
        }

        $newOptions = array_unique($options);

        foreach ($toReplicate as $acl) {
            $newAcl = new ACL();
            $newAcl->setUser($acl->getUser());
            $newAcl->setType($acl->getType());
            $newAcl->setObjectId($acl->getObjectId());
            $newAcl->setOptions($newOptions);
            $newAcl->setValidFrom($acl->getValidFrom());
            $newAcl->setValidTo($acl->getValidTo());

            $user->addAcl($newAcl);
        }
    }

    public function preDown(Schema $schema)
    {

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE acls DROP options');
    }
}
