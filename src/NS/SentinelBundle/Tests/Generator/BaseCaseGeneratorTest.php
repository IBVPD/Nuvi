<?php

namespace NS\SentinelBundle\Tests\Generator;

use \InvalidArgumentException;
use \NS\SentinelBundle\Generator\BaseCaseGenerator;

/**
 * Description of EntityGeneratorTest
 *
 * @author gnat
 */
class BaseCaseGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedExceptionMessage Entity must implement IdentityAssignmentInterface
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidInterface()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entity = new \stdClass();

        $generator = new BaseCaseGenerator();
        $generator->generate($entityMgr, $entity);
    }

    /**
     * @expectedExceptionMessage Can't generate an id for entities without an assigned site
     * @expectedException \UnexpectedValueException
     */
    public function testEntityNullSite()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entity = new \NS\SentinelBundle\Entity\IBD();

        $generator = new BaseCaseGenerator();
        $generator->generate($entityMgr, $entity);
    }

    /**
     * @expectedExceptionMessage Can't generate an id for entities with a site without an id ''
     * @expectedException \UnexpectedValueException
     */
    public function testEntitySiteHasNoId()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $site = $this->getMock('\NS\SentinelBundle\Entity\Site');
        $site->expects($this->once())
            ->method('hasId')
            ->willReturn(false);

        $entity    = $this->getMock('\NS\SentinelBundle\Entity\IBD');
        $entity->expects($this->once())
            ->method('getSite')
            ->willReturn($site);

        $generator = new BaseCaseGenerator();
        $generator->generate($entityMgr, $entity);
    }

    public function testCaseIdGeneration()
    {
        $nativeQuery = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(array('_doExecute', 'getSQL', 'getResult'))
            ->getMock();
        $nativeQuery->expects($this->once())
            ->method('getResult')
            ->willReturn(12);

        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->getMock();
        $connection->expects($this->once())
            ->method('executeUpdate')
            ->with('UPDATE sites SET currentCaseId = currentCaseId +1 WHERE code = :code', array(
                'code' => 'SITE'));

        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('beginTransaction', 'createNativeQuery', 'getConnection',
                'rollback', 'commit'))
            ->getMock();
        $entityMgr->expects($this->once())
            ->method('createNativeQuery')
            ->willReturn($nativeQuery);
        $entityMgr->expects($this->once())
            ->method('getConnection')
            ->willReturn($connection);
        $entityMgr->expects($this->never())
            ->method('rollback');

        $region  = new \NS\SentinelBundle\Entity\Region();
        $region->setCode('REG');
        $country = new \NS\SentinelBundle\Entity\Country();
        $country->setCode('CNT');
        $country->setRegion($region);
        $site    = new \NS\SentinelBundle\Entity\Site();
        $site->setCode('SITE');
        $site->setCountry($country);

        $entity = new \NS\SentinelBundle\Entity\IBD();
        $entity->setSite($site);

        $generator = new BaseCaseGenerator();
        $id        = $generator->generate($entityMgr, $entity);
        $this->assertEquals(sprintf("CNT-SITE-%d-000012", date('y')), $id);
    }
}
