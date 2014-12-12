<?php

namespace NS\SentinelBundle\Generator;

use \Doctrine\ORM\EntityManager;
use \Doctrine\ORM\Id\AbstractIdGenerator;
use \NS\SentinelBundle\Entity\ReferenceLab as ReferenceLabEntity;
use \NS\SentinelBundle\Entity\Region;

/**
 *
 * @author gnat
 */
class ReferenceLab extends AbstractIdGenerator
{
    static $cache = array();

    public function generate(EntityManager $entityMgr, $entity)
    {
        if (!$entity instanceOf ReferenceLabEntity)
            throw new \InvalidArgumentException("Entity must implement IdentityAssignmentInterface");

        $region = $entity->getCountry()->getRegion();

        if (is_null($region))
            throw new \UnexpectedValueException("Can't generate an id for entities without an assigned country and region");

        if (!$region instanceof Region)
            throw new \UnexpectedValueException("Region is not a proper class");

        if (!$region->getCode())
            throw new \UnexpectedValueException(sprintf("Can't generate an id for entities with a region without an code '%s'", $region->getCode()));

        return sprintf("%s-%s", $region->getCode(), $entity->getUserId());
    }
}
