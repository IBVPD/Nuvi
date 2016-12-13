<?php

namespace NS\SentinelBundle\Tests\Generator;

use \Doctrine\ORM\Query;
use \InvalidArgumentException;
use \NS\SentinelBundle\Entity\Generator\BaseCaseGenerator;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Entity\Region;
use \NS\SentinelBundle\Entity\Country;
use \NS\SentinelBundle\Entity\Site;

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
        $entityMgr = $this->createMock('Doctrine\ORM\EntityManager');

        $entity = new \stdClass();

        $generator = new BaseCaseGenerator();
        $generator->generate($entityMgr, $entity);
    }

    /**
     * @expectedExceptionMessage Can't generate an id for entities without an site or country
     * @expectedException \UnexpectedValueException
     */
    public function testEntityNullSite()
    {
        $entityMgr = $this->createMock('Doctrine\ORM\EntityManager');

        $entity = new IBD();

        $generator = new BaseCaseGenerator();
        $generator->generate($entityMgr, $entity);
    }

    public function testEntitySiteNullWithCountry()
    {
        $entityMgr = $this->createMock('Doctrine\ORM\EntityManager');

        $country = new Country('CDN', 'Canada');

        $entity = new IBD();
        $entity->setAdmDate(new \DateTime('2015-11-13'));
        $entity->setCountry($country);

        $generator = new BaseCaseGenerator();
        $id = $generator->generate($entityMgr, $entity);
        $this->assertEquals('CDN-XXX-15-', substr($id, 0, 11));
    }

    public function testCaseIdGeneration()
    {
        $nativeQuery = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(['_doExecute', 'getSQL', 'getResult'])
            ->getMock();
        $nativeQuery->expects($this->once())
            ->method('getResult')
            ->willReturn(12);

        $entityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(['beginTransaction', 'createNativeQuery', 'rollback', 'commit', 'createQuery', 'setParameter', 'execute'])
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
            ->with('code', 'SITE')
            ->willReturnSelf();
        $entityMgr->expects($this->once())
            ->method('execute');
        $entityMgr->expects($this->never())
            ->method('rollback');

        $region  = new Region();
        $region->setCode('REG');
        $country = new Country();
        $country->setCode('CNT');
        $country->setRegion($region);
        $site    = new Site();
        $site->setCode('SITE');
        $site->setCountry($country);

        $entity = new IBD();
        $entity->setSite($site);

        $generator = new BaseCaseGenerator();
        $id        = $generator->generate($entityMgr, $entity);
        $this->assertEquals(sprintf("CNT-SITE-%d-000012", date('y')), $id);
    }
}
