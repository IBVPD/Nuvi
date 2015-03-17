<?php

namespace NS\SentinelBundle\Generator;

use Doctrine\ORM\Id\AbstractIdGenerator;
use NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;
use NS\SentinelBundle\Entity\Site;

/**
 *
 * @author gnat
 */
class BaseCaseGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $entityMgr, $entity)
    {
        if (!$entity instanceOf IdentityAssignmentInterface)
        {
            throw new \InvalidArgumentException("Entity must implement IdentityAssignmentInterface");
        }

        $site = $entity->getSite();

        if (is_null($site))
        {
            throw new \UnexpectedValueException("Can't generate an id for entities without an assigned site");
        }

        if (!$site->hasId())
        {
            throw new \UnexpectedValueException(sprintf("Can't generate an id for entities with a site without an id '%s'", $site->getId()));
        }

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('NS\SentinelBundle\Entity\Site', 's');
        $rsm->addFieldResult('s', 'currentCaseId', 'currentCaseId');

        try
        {
            $entityMgr->beginTransaction();

            $native = sprintf("SELECT s.currentCaseId FROM sites s WHERE s.code = '%s'", $site->getCode());
            $newId  = $entityMgr
                ->createNativeQuery($native, $rsm)
                ->getResult(Query::HYDRATE_SINGLE_SCALAR);

            $entityMgr
                ->getConnection()
                ->executeUpdate('UPDATE sites SET currentCaseId = currentCaseId +1 WHERE code = :code', array('code' => $site->getCode()));

            $entityMgr->commit();

            return $entity->getFullIdentifier($newId);
        }
        catch (\Exception $e)
        {
            $entityMgr->rollback();
            throw new \RuntimeException("Site issue: " . $site->getName() . " " . $site->getId() . " " . $e->getMessage());
        }
    }
}