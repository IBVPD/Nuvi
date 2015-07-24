<?php

namespace NS\SentinelBundle\Generator;

use \Doctrine\ORM\EntityManager;
use \Doctrine\ORM\Id\AbstractIdGenerator;
use \NS\SentinelBundle\Entity\ReferenceLab as ReferenceLabEntity;

/**
 *
 * @author gnat
 */
class ReferenceLabGenerator extends AbstractIdGenerator
{
    /**
     * @param EntityManager $entityMgr
     * @param ReferenceLabEntity $entity
     * @return string
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function generate(EntityManager $entityMgr, $entity)
    {
        if (!$entity instanceOf ReferenceLabEntity)
        {
            throw new \InvalidArgumentException("Entity must implement IdentityAssignmentInterface");
        }

        $region = $entity->getCountry()->getRegion();

        if (is_null($region))
        {
            throw new \UnexpectedValueException("Can't generate an id for entities without an assigned country and region");
        }

        if (!$region->getCode())
        {
            throw new \UnexpectedValueException(sprintf("Can't generate an id for entities with a region without an code '%s'", $region->getCode()));
        }

        return sprintf("%s-%s", $region->getCode(), $entity->getUserId());
    }
}
