<?php

namespace NS\SentinelBundle\Tests\Generator;

use \NS\SentinelBundle\Entity\Generator\ReferenceLabGenerator;

/**
 * Description of ReferenceLabTest
 *
 * @author gnat
 */
class ReferenceLabTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidInterface()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $stdClass  = new \stdClass();
        $generator = new ReferenceLabGenerator();
        $generator->generate($entityMgr, $stdClass);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Can't generate an id for entities without an assigned country and region
     */
    public function testNullRegion()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entity = new \NS\SentinelBundle\Entity\ReferenceLab();
        $entity->addCountry(new \NS\SentinelBundle\Entity\Country());

        $generator = new ReferenceLabGenerator();
        $generator->generate($entityMgr, $entity);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Can't generate an id for entities with a region without an code ''
     */
    public function testRegionWithoutCode()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $region  = new \NS\SentinelBundle\Entity\Region();
        $country = new \NS\SentinelBundle\Entity\Country();
        $country->setRegion($region);
        $entity = new \NS\SentinelBundle\Entity\ReferenceLab();
        $entity->addCountry($country);

        $generator = new ReferenceLabGenerator();
        $generator->generate($entityMgr, $entity);
    }

    public function testRegionWithCode()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $region  = new \NS\SentinelBundle\Entity\Region();
        $region->setCode('REG');
        $country = new \NS\SentinelBundle\Entity\Country();
        $country->setRegion($region);
        $entity  = new \NS\SentinelBundle\Entity\ReferenceLab();
        $entity->addCountry($country);
        $entity->setUserId('RL12');

        $generator = new ReferenceLabGenerator();
        $id        = $generator->generate($entityMgr, $entity);
        $this->assertEquals('REG-RL12', $id);
    }
}
