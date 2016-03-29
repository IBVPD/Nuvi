<?php

namespace NS\ImportBundle\Tests\Converter;

use \Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Types\Type;
use \NS\ImportBundle\Converter\ColumnChooser;

class ColumnChooserTest extends \PHPUnit_Framework_TestCase
{
    public function testCacheHitDoesNotBuildChoices()
    {
        $cacheMock = $this->getMock('\Doctrine\Common\Cache\ArrayCache');
        $cacheMock->expects($this->once())
            ->method('contains')
            ->with('class')
            ->willReturn(true);

        $cacheMock->expects($this->once())
            ->method('fetch')
            ->with('class')
            ->willReturn(array('choices'=>true, 'complex'=>false));

        $chooser = $this->getChooser($cacheMock);
        $this->assertTrue($chooser->getChoices('class'));
        $this->assertFalse($chooser->getComplexChoices('class'));
    }

    public function testCacheHitDoesNotBuildComplex()
    {
        $cacheMock = $this->getMock('\Doctrine\Common\Cache\ArrayCache');
        $cacheMock->expects($this->once())
            ->method('contains')
            ->with('class')
            ->willReturn(true);

        $cacheMock->expects($this->once())
            ->method('fetch')
            ->with('class')
            ->willReturn(array('choices'=>true, 'complex'=>false));

        $chooser = $this->getChooser($cacheMock);
        $this->assertFalse($chooser->getComplexChoices('class'));
        $this->assertTrue($chooser->getChoices('class'));
    }

    public function testCacheMissBuilds()
    {
        $meta = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $mockEntityMgr
            ->expects($this->once())
            ->method('getClassMetadata')
            ->willReturn($meta);

        $cache = new ArrayCache();

        $chooser = $this->getMock('NS\ImportBundle\Converter\ColumnChooser', array('buildChoices', 'buildComplex'), array($mockEntityMgr, $cache));
        $chooser->expects($this->once())
            ->method('buildChoices')
            ->with($meta)
            ->willReturn(array('here'));

        $chooser->expects($this->once())
            ->method('buildComplex')
            ->with($meta)
            ->willReturn(array('here'=>false));

        $retValue = $chooser->getChoices('class');

        $this->assertEquals(array('here'), $retValue);
        $this->assertEquals(array('choices'=>array('here'), 'complex'=>array('here'=>false)), $cache->fetch('class'));
    }

    public function testMetaChoicesWithoutAssociationName()
    {
        $meta = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->setMethods(array('getFieldNames', 'getTypeOfField'))
            ->getMock();

        $meta->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(array('field1', 'field2', 'field3'));

        $map = array(
            array('field1','string'),
            array('field2','integer'),
            array('field3','TripleChoice'),
        );
        $meta->method('getTypeOfField')
            ->will($this->returnValueMap($map));

        $chooser = $this->getChooser();

        $choices = $chooser->getMetaChoices($meta);
        $this->assertTrue(is_array($choices));
        $this->assertCount(3, $choices);

        $this->assertArrayHasKey('field1', $choices);
        $this->assertEquals('field1 (string)', $choices['field1']);

        $this->assertArrayHasKey('field2', $choices);
        $this->assertEquals('field2 (integer)', $choices['field2']);

        $this->assertArrayHasKey('field3', $choices);
        $this->assertEquals('field3 (TripleChoice)', $choices['field3']);
    }

    public function testMetaChoicesWithoutAssociationNameSorted()
    {
        $meta = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->setMethods(array('getFieldNames', 'getTypeOfField'))
            ->getMock();

        $meta->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(array('field2', 'field1', 'field3'));

        $map = array(
            array('field1','string'),
            array('field2','integer'),
            array('field3','TripleChoice'),
        );
        $meta->method('getTypeOfField')
            ->will($this->returnValueMap($map));

        $chooser = $this->getChooser();

        $choices = $chooser->getMetaChoices($meta);
        $this->assertTrue(is_array($choices));
        $this->assertCount(3, $choices);

        $this->assertArrayHasKey('field1', $choices);
        $this->assertEquals('field1 (string)', $choices['field1']);

        $this->assertArrayHasKey('field2', $choices);
        $this->assertEquals('field2 (integer)', $choices['field2']);

        $this->assertArrayHasKey('field3', $choices);
        $this->assertEquals('field3 (TripleChoice)', $choices['field3']);
        $this->assertEquals(array('field1', 'field2', 'field3'), array_keys($choices));
    }

    public function testMetaChoicesWithAssociationName()
    {
        $meta = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()
            ->setMethods(array('getFieldNames', 'getTypeOfField'))
            ->getMock();

        $meta->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(array('field1', 'field2', 'field3'));

        $map = array(
            array('field1','string'),
            array('field2','integer'),
            array('field3','TripleChoice'),
        );
        $meta->method('getTypeOfField')
            ->will($this->returnValueMap($map));

        $chooser = $this->getChooser();

        $choices = $chooser->getMetaChoices($meta, 'assocName');
        $this->assertTrue(is_array($choices));
        $this->assertCount(3, $choices);

        $this->assertArrayHasKey('assocName.field1', $choices);
        $this->assertEquals('assocName.field1 (string)', $choices['assocName.field1']);

        $this->assertArrayHasKey('assocName.field2', $choices);
        $this->assertEquals('assocName.field2 (integer)', $choices['assocName.field2']);

        $this->assertArrayHasKey('assocName.field3', $choices);
        $this->assertEquals('assocName.field3 (TripleChoice)', $choices['assocName.field3']);
    }

    public function getChooser($cache = null)
    {
        $mockEntityMgr = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        return new ColumnChooser($mockEntityMgr, (!$cache)?new ArrayCache():$cache);
    }

    /**
     * @param $type
     * @param $isComplex
     *
     * @dataProvider getTypes
     */
    public function testIsComplex($type, $isComplex)
    {
        $chooser = $this->getChooser();
        $this->assertEquals($isComplex, $chooser->isComplex($type));
    }

    public function getTypes()
    {
        return array(
            array(Type::TARRAY,false),
            array(Type::SIMPLE_ARRAY,false),
            array(Type::JSON_ARRAY,false),
            array(Type::BIGINT,false),
            array(Type::BOOLEAN,false),
            array(Type::DATETIME,true),
            array(Type::DATETIMETZ,true),
            array(Type::DATE,true),
            array(Type::TIME,true),
            array(Type::DECIMAL,false),
            array(Type::INTEGER,false),
            array(Type::OBJECT,false),
            array(Type::SMALLINT,false),
            array(Type::STRING,false),
            array(Type::TEXT,false),
            array(Type::BLOB,false),
            array(Type::FLOAT,false),
            array(Type::GUID,false),
            array('SampleType',true),
        );
    }
}
