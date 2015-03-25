<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\SiteLabConverter;

/**
 * Description of SiteLabConverterTest
 *
 * @author gnat
 */
class SiteLabConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsIBDClass()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr,'NS\SentinelBundle\Entity\IBD\SiteLab');
        
        $this->assertEquals('NS\SentinelBundle\Entity\IBD\SiteLab Converter',$converter->getName());
        $this->assertFalse($converter->supportsClass('NS\SentinelBundle\Entity\BaseCase'));
        $this->assertTrue($converter->supportsClass('NS\SentinelBundle\Entity\IBD'));
        $this->assertFalse($converter->supportsClass('NS\SentinelBundle\Entity\RotaVirus'));
    }

    public function testSupportsRotaVirusClass()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr,'NS\SentinelBundle\Entity\Rota\SiteLab');
        
        $this->assertEquals('NS\SentinelBundle\Entity\Rota\SiteLab Converter',$converter->getName());
        $this->assertFalse($converter->supportsClass('NS\SentinelBundle\Entity\BaseCase'));
        $this->assertTrue($converter->supportsClass('NS\SentinelBundle\Entity\RotaVirus'));
        $this->assertFalse($converter->supportsClass('NS\SentinelBundle\Entity\IBD'));
    }
    
    public function testHasNeededFieldsNotArray()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr,'NS\SentinelBundle\Entity\IBD\SiteLab');
        $this->assertFalse($converter->hasNeededFields(' '));
        $this->assertFalse($converter->hasNeededFields(2));
        $this->assertFalse($converter->hasNeededFields(new \stdClass()));
    }
    
    public function testHasNeededFieldsSiteLab()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr,'NS\SentinelBundle\Entity\Rota\SiteLab');
        $this->assertFalse($converter->hasNeededFields(array('field1'=>'something')));
        $this->assertFalse($converter->hasNeededFields(array('siteLab'=>'something')));
        $this->assertFalse($converter->hasNeededFields(array('siteLab'=>'something','site'=>'something')));
        $this->assertFalse($converter->hasNeededFields(array('siteLab'=>'something','caseId'=>'something')));
        $this->assertTrue($converter->hasNeededFields(array('siteLab'=>'something','site'=>'something','caseId'=>'another')));
    }
    
    public function testMethodSetter()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr,'NS\SentinelBundle\Entity\IBD\SiteLab');
        $siteLab = new \NS\SentinelBundle\Entity\IBD\SiteLab();
        $converter->setValue($siteLab,12,'setCsfId');
        $this->assertEquals(12,$siteLab->getCsfId());
        $converter->setValue($siteLab,10,'setCsfd');
        $this->assertEquals(12,$siteLab->getCsfId());
    }
}
