<?php

namespace NS\SentinelBundle\Generator;

use Doctrine\ORM\Id\AbstractIdGenerator;
use NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;

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
            throw new \Exception("Entity must implement IdentityAssignmentInterface");
        
        $sId = $entity->getSite()->getId();

        $em->getConnection()->beginTransaction();
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('NS\SentinelBundle\Entity\Site', 's');
        $rsm->addFieldResult('s', 'currentCaseId', 'currentCaseId');
        
        $id = $em->createNativeQuery('SELECT s.currentCaseId FROM sites s WHERE s.id = '.$sId, $rsm)->getResult(Query::HYDRATE_SINGLE_SCALAR);

        $em->getConnection()->executeUpdate('UPDATE sites SET currentCaseId = currentCaseId +1 WHERE id = :id', array('id'=>$sId));
        $em->getConnection()->commit();

        return $entity->getFullIdentifier($id);
    }
}
