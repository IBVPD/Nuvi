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
class Custom extends AbstractIdGenerator
{
    static $cache = array();

    public function generate(EntityManager $em, $entity)
    {
        if(!$entity instanceOf IdentityAssignmentInterface)
            throw new \InvalidArgumentException("Entity must implement IdentityAssignmentInterface");

        $site = $entity->getSite();

        if(is_null($site))
            throw new \UnexpectedValueException("Can't generate an id for entities without an assigned site");

        if(!$site instanceof Site)
            throw new \UnexpectedValueException("Site is not a proper class");

        if($site->getId() == 0)
            throw new \UnexpectedValueException("Can't generate an id for entities with a site without an id");

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('NS\SentinelBundle\Entity\Site', 's');
        $rsm->addFieldResult('s', 'currentCaseId', 'currentCaseId');

        try
        {
            $em->beginTransaction();
            $id = $em->createNativeQuery('SELECT s.currentCaseId FROM sites s WHERE s.id = '.$site->getId(), $rsm)->getResult(Query::HYDRATE_SINGLE_SCALAR);
            $em->getConnection()->executeUpdate('UPDATE sites SET currentCaseId = currentCaseId +1 WHERE id = :id', array('id'=>$site->getId()));
            $em->commit();
        }
        catch(\Exception $e)
        {
            $em->rollback();
            throw new \RuntimeException("Site issue: ".$site->getName()." ".$site->getId());
        }

        return $entity->getFullIdentifier($id);
    }
}
