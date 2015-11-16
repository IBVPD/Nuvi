<?php

namespace NS\SentinelBundle\Entity\Generator;

use \Doctrine\Common\Persistence\ObjectManager;
use \Doctrine\ORM\Id\AbstractIdGenerator;
use \NS\SentinelBundle\Entity\BaseCase;
use \Doctrine\ORM\EntityManager;
use \Doctrine\ORM\Query\ResultSetMapping;
use \Doctrine\ORM\Query;
use \NS\SentinelBundle\Entity\Site;

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
        $country = $entity->getCountry();

        if ($site === null && $country == null) {
            throw new \UnexpectedValueException("Can't generate an id for entities without an site or country");
        }

        $year = $this->getYear($entity);

        if ($site) {
            $newId = $this->getNextId($entityMgr, $site);
            return $this->getFullIdentifier($newId, $year, $entity->getCountry()->getCode(),$site->getCode());
        }

        $newId = $this->getUUID();

        return $this->getUuidIdentifier( $newId, $year, $entity->getCountry()->getCode());
    }

    /**
     * @param ObjectManager $entityMgr
     * @param Site $site
     * @return mixed
     */
    public function getNextId(ObjectManager $entityMgr, Site $site)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('NS\SentinelBundle\Entity\Site', 's');
        $rsm->addFieldResult('s', 'currentCaseId', 'currentCaseId');

        try {
            $entityMgr->beginTransaction();

            $newId = $entityMgr
                ->createNativeQuery(sprintf("SELECT s.currentCaseId FROM sites s WHERE s.code = '%s'", $site->getCode()), $rsm)
                ->getResult(Query::HYDRATE_SINGLE_SCALAR);

            $entityMgr
                ->createQuery('UPDATE NS\SentinelBundle\Entity\Site s SET s.currentCaseId = s.currentCaseId +1 WHERE s.code = :code')
                ->setParameter('code', $site->getCode())
                ->execute();

            $entityMgr->commit();

            return $newId;
        } catch (\Exception $exception) {
            $entityMgr->rollback();
            throw new \RuntimeException(sprintf('Site issue: %s %s %s',$site->getName(),$site->getId(),$exception->getMessage()));
        }
    }

    public function getYear(BaseCase $case)
    {
        if (property_exists($case, 'admDate') && $case->getAdmDate() instanceof \DateTime) {
            return $case->getAdmDate()->format('y');
        } elseif (property_exists($case, 'onsetDate') && $case->getOnsetDate() instanceof \DateTime) {
            return $case->getOnsetDate()->format('y');
        } else {
            return date('y');
        }
    }

    /**
     * @return string
     */
    public function getUUID()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public function getFullIdentifier($id, $year, $countryCode, $siteCode ='XXX')
    {
        return sprintf("%s-%s-%d-%06d", $countryCode, $siteCode, $year, $id);
    }

    public function getUuidIdentifier($id, $year, $countryCode, $siteCode ='XXX')
    {
        return sprintf("%s-%s-%d-%s", $countryCode, $siteCode, $year, $id);
    }
}
