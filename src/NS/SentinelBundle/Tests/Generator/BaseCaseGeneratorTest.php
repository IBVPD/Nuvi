<?php

namespace NS\SentinelBundle\Tests\Generator;

use Doctrine\ORM\Query;
use \InvalidArgumentException;
use \NS\SentinelBundle\Entity\Generator\BaseCaseGenerator;

/**
 * Description of EntityGeneratorTest
 *
 * @author gnat
 */
class BaseCaseGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedExceptionMessage Entity must extend NS\SentinelBundle\Entity\BaseCase
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

        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(array('beginTransaction', 'createNativeQuery', 'rollback', 'commit','createQuery','setParameter','execute'))
            ->getMock();
        $entityMgr->expects($this->once())
            ->method('createNativeQuery')
            ->willReturn($nativeQuery);
        $entityMgr->expects($this->once())
            ->method('createQuery')
            ->with('UPDATE NS\SentinelBundle\Entity\Site s SET s.currentCaseId = s.currentCaseId +1 WHERE s.code = :code')
            ->willReturnSelf();
        $entityMgr->expects($this->once())
            ->method('setParameter')
            ->with('code','SITE')
            ->willReturnSelf();
        $entityMgr->expects($this->once())
            ->method('execute');
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
