<?php

namespace NS\SentinelBundle\Generator;

use \Doctrine\ORM\Id\AbstractIdGenerator;
use \NS\SentinelBundle\Entity\BaseCase;
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
        if (!$entity instanceOf BaseCase) {
            throw new \InvalidArgumentException('Entity must extend NS\\SentinelBundle\\Entity\\BaseCase');
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

            return $this->getFullIdentifier($entity,$newId);
        } catch (\Exception $exception) {
            $entityMgr->rollback();
            throw new \RuntimeException(sprintf('Site issue: %s %s %s',$site->getName(),$site->getId(),$exception->getMessage()));
        }
    }

    /**
     * @param BaseCase $case
     * @param $id
     * @return string
     */
    public function getFullIdentifier(BaseCase $case, $id)
    {
        if (property_exists($case, 'admDate') && $case->getAdmDate() instanceof \DateTime) {
            $year = $case->getAdmDate()->format('y');
        } elseif (property_exists($case, 'onsetDate') && $case->getOnsetDate() instanceof \DateTime) {
            $year = $case->getOnsetDate()->format('y');
        } else {
            $year = date('y');
        }

        return sprintf("%s-%s-%d-%06d", $case->getCountry()->getCode(), $case->getSite()->getCode(), $year, $id);
    }
}
