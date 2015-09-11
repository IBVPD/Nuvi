<?php

namespace NS\SentinelBundle\Generator;

use \Doctrine\ORM\Id\AbstractIdGenerator;
use \NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;
use \Doctrine\ORM\EntityManager;
use \Doctrine\ORM\Query\ResultSetMapping;
use \Doctrine\ORM\Query;

/**
 *
 * @author gnat
 */
class BaseCaseGenerator extends AbstractIdGenerator
{
    /**
     * @param EntityManager $entityMgr
     * @param \Doctrine\ORM\Mapping\Entity $entity
     * @return mixed
     */
    public function generate(EntityManager $entityMgr, $entity)
    {
        if (!$entity instanceOf IdentityAssignmentInterface) {
            throw new \InvalidArgumentException("Entity must implement IdentityAssignmentInterface");
        }

        $site = $entity->getSite();

        if (is_null($site)) {
            throw new \UnexpectedValueException("Can't generate an id for entities without an assigned site");
        }

        if (!$site->hasId()) {
            throw new \UnexpectedValueException(sprintf("Can't generate an id for entities with a site without an id '%s'", $site->getId()));
        }

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('NS\SentinelBundle\Entity\Site', 's');
        $rsm->addFieldResult('s', 'currentCaseId', 'currentCaseId');

        try {
            $entityMgr->beginTransaction();

            $newId = $entityMgr
                ->createNativeQuery(sprintf("SELECT s.currentCaseId FROM sites s WHERE s.code = '%s'", $site->getCode()), $rsm)
                ->getResult(Query::HYDRATE_SINGLE_SCALAR);

            $entityMgr
                ->createQuery('UPDATE sites SET currentCaseId = currentCaseId +1 WHERE code = :code')
                ->setParameter('code', $site->getCode())
                ->execute();

            $entityMgr->commit();

            return $entity->getFullIdentifier($newId);
        } catch (\Exception $exception) {
            $entityMgr->rollback();
            throw new \RuntimeException(sprintf('Site issue: %s %s %s',$site->getName(),$site->getId(),$exception->getMessage()));
        }
    }
}
