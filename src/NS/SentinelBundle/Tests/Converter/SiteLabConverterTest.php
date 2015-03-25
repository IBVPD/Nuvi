<?php

namespace NS\SentinelBundle\Tests\Converter;

use NS\SentinelBundle\Converter\SiteLabConverter;
use NS\SentinelBundle\Entity\IBD\SiteLab;

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

        $converter = new SiteLabConverter($entityMgr, 'NS\SentinelBundle\Entity\IBD\SiteLab');

        $this->assertEquals('NS\SentinelBundle\Entity\IBD\SiteLab Converter', $converter->getName());
        $this->assertFalse($converter->supportsClass('NS\SentinelBundle\Entity\BaseCase'));
        $this->assertTrue($converter->supportsClass('NS\SentinelBundle\Entity\IBD'));
        $this->assertFalse($converter->supportsClass('NS\SentinelBundle\Entity\RotaVirus'));
    }

    public function testSupportsRotaVirusClass()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr, 'NS\SentinelBundle\Entity\Rota\SiteLab');

        $this->assertEquals('NS\SentinelBundle\Entity\Rota\SiteLab Converter', $converter->getName());
        $this->assertFalse($converter->supportsClass('NS\SentinelBundle\Entity\BaseCase'));
        $this->assertTrue($converter->supportsClass('NS\SentinelBundle\Entity\RotaVirus'));
        $this->assertFalse($converter->supportsClass('NS\SentinelBundle\Entity\IBD'));
    }

    public function testHasNeededFieldsNotArray()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr, 'NS\SentinelBundle\Entity\IBD\SiteLab');
        $this->assertFalse($converter->hasNeededFields(' '));
        $this->assertFalse($converter->hasNeededFields(2));
        $this->assertFalse($converter->hasNeededFields(new \stdClass()));
    }

    public function testHasNeededFieldsSiteLab()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr, 'NS\SentinelBundle\Entity\Rota\SiteLab');
        $this->assertFalse($converter->hasNeededFields(array('field1' => 'something')));
        $this->assertFalse($converter->hasNeededFields(array('siteLab' => 'something')));
        $this->assertFalse($converter->hasNeededFields(array('siteLab' => 'something',
                'site'    => 'something')));
        $this->assertFalse($converter->hasNeededFields(array('siteLab' => 'something',
                'caseId'  => 'something')));
        $this->assertTrue($converter->hasNeededFields(array('siteLab' => 'something',
                'site'    => 'something', 'caseId'  => 'another')));
    }

    public function testMethodSetter()
    {
        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $converter = new SiteLabConverter($entityMgr, 'NS\SentinelBundle\Entity\IBD\SiteLab');
        $siteLab   = new SiteLab();
        $converter->setValue($siteLab, 12, 'setCsfId');
        $this->assertEquals(12, $siteLab->getCsfId());
        $converter->setValue($siteLab, 10, 'setCsfd');
        $this->assertEquals(12, $siteLab->getCsfId());
    }

    public function testUpdateEntityItemValueNotSet()
    {
        $siteLab  = new SiteLab();
        $siteLab->setCsfId(12223);
        $class    = 'NS\SentinelBundle\Entity\IBD\SiteLab';
        $metaData = $this->getMockBuilder('Doctrine\Common\Persistence\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();
        $metaData->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(array('csfDateTime', 'csfId', 'csfWcc'));
        $metaData->expects($this->once())
            ->method('getAssociationNames')
            ->willReturn(array());

        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entityMgr->expects($this->once())
            ->method('getRepository')
            ->with($class)
            ->willReturn(null);

        $entityMgr->expects($this->once())
            ->method('getClassMetadata')
            ->with($class)
            ->willReturn($metaData);

        $converter = new SiteLabConverter($entityMgr, $class);
        $converter->initialize();
        $converter->updateEntity(array(), $siteLab);
        $this->assertEquals(12223, $siteLab->getCsfId());
        $this->assertNull($siteLab->getCsfDateTime());
        $this->assertNull($siteLab->getCsfWcc());
    }

    public function testUpdateEntityItemValueIsSet()
    {
        $siteLab  = new SiteLab();
        $siteLab->setCsfId(12223);
        $class    = 'NS\SentinelBundle\Entity\IBD\SiteLab';
        $metaData = $this->getMockBuilder('Doctrine\Common\Persistence\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();
        $metaData->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(array('csfDateTime', 'csfId', 'csfWcc'));
        $metaData->expects($this->once())
            ->method('getAssociationNames')
            ->willReturn(array());

        $metaData->expects($this->never())
            ->method('getFieldValue')
            ->with($siteLab, 'csfId')
            ->willReturn($siteLab->getCsfId());

        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entityMgr->expects($this->once())
            ->method('getRepository')
            ->with($class)
            ->willReturn(null);

        $entityMgr->expects($this->once())
            ->method('getClassMetadata')
            ->with($class)
            ->willReturn($metaData);

        $converter = new SiteLabConverter($entityMgr, $class);
        $converter->initialize();
        $converter->updateEntity(array('csfId' => 12224), $siteLab);
        $this->assertEquals(12224, $siteLab->getCsfId());
        $this->assertNull($siteLab->getCsfDateTime());
        $this->assertNull($siteLab->getCsfWcc());
    }

    public function testUpdateEntityItemValueIsUnchangedDate()
    {
        $date     = new \DateTime('2015-03-25');
        $siteLab  = new SiteLab();
        $siteLab->setCsfId(12223);
        $siteLab->setCsfDateTime($date);
        $class    = 'NS\SentinelBundle\Entity\IBD\SiteLab';
        $metaData = $this->getMockBuilder('\Doctrine\Common\Persistence\Mapping\ClassMetadata')
            ->setMethods(array('getFieldNames', 'getAssociationNames', 'getFieldValue',
                'getName', 'getReflectionClass', 'getIdentifier', 'isIdentifier',
                'hasAssociation', 'hasField', 'isSingleValuedAssociation', 'isCollectionValuedAssociation',
                'getIdentifierFieldNames', 'getTypeOfField',
                'getAssociationTargetClass', 'isAssociationInverseSide', 'getAssociationMappedByTargetField',
                'getIdentifierValues'))
            ->disableOriginalConstructor()
            ->getMock();
        $metaData->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(array('csfDateTime', 'csfId', 'csfWcc'));
        $metaData->expects($this->once())
            ->method('getAssociationNames')
            ->willReturn(array());

        $metaData->expects($this->any())
            ->method('getFieldValue')
            ->with($siteLab, 'csfDateTime')
            ->willReturn($date);

        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entityMgr->expects($this->once())
            ->method('getRepository')
            ->with($class)
            ->willReturn(null);

        $entityMgr->expects($this->once())
            ->method('getClassMetadata')
            ->with($class)
            ->willReturn($metaData);

        $converter = new SiteLabConverter($entityMgr, $class);
        $converter->initialize();
        $converter->updateEntity(array('csfId' => 12223, 'csfDateTime' => $date), $siteLab);
        $this->assertEquals(12223, $siteLab->getCsfId());
        $this->assertEquals('2015-03-25', $siteLab->getCsfDateTime()->format('Y-m-d'));
        $this->assertNull($siteLab->getCsfWcc());
    }

    public function testUpdateEntityItemValueDateChanged()
    {
        $date     = new \DateTime('2015-03-25');
        $siteLab  = new SiteLab();
        $siteLab->setCsfId(12223);
        $siteLab->setCsfDateTime($date);
        $class    = 'NS\SentinelBundle\Entity\IBD\SiteLab';
        $metaData = $this->getMockBuilder('\Doctrine\Common\Persistence\Mapping\ClassMetadata')
            ->setMethods(array('getFieldNames', 'getAssociationNames', 'getFieldValue',
                'getName', 'getReflectionClass', 'getIdentifier', 'isIdentifier',
                'hasAssociation', 'hasField', 'isSingleValuedAssociation', 'isCollectionValuedAssociation',
                'getIdentifierFieldNames', 'getTypeOfField',
                'getAssociationTargetClass', 'isAssociationInverseSide', 'getAssociationMappedByTargetField',
                'getIdentifierValues'))
            ->disableOriginalConstructor()
            ->getMock();
        $metaData->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(array('csfDateTime', 'csfId', 'csfWcc'));
        $metaData->expects($this->once())
            ->method('getAssociationNames')
            ->willReturn(array());

        $metaData->expects($this->any())
            ->method('getFieldValue')
            ->with($siteLab, 'csfDateTime')
            ->willReturn($date);

        $entityMgr = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entityMgr->expects($this->once())
            ->method('getRepository')
            ->with($class)
            ->willReturn(null);

        $entityMgr->expects($this->once())
            ->method('getClassMetadata')
            ->with($class)
            ->willReturn($metaData);

        $converter = new SiteLabConverter($entityMgr, $class);
        $converter->initialize();
        $converter->updateEntity(array('csfId' => 12223, 'csfDateTime' => new \DateTime('2015-03-26')), $siteLab);
        $this->assertEquals(12223, $siteLab->getCsfId());
        $this->assertEquals('2015-03-26', $siteLab->getCsfDateTime()->format('Y-m-d'));
        $this->assertNull($siteLab->getCsfWcc());
    }

}
