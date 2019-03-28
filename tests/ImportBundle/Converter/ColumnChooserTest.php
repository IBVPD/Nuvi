<?php

namespace NS\ImportBundle\Tests\Converter;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Types\Type;
use NS\ImportBundle\Converter\ColumnChooser;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class ColumnChooserTest extends TestCase
{
    public function testCacheHitDoesNotBuildChoices(): void
    {
        $cacheMock = $this->createMock(ArrayCache::class);
        $cacheMock->expects($this->once())
            ->method('contains')
            ->with('class')
            ->willReturn(true);

        $cacheMock->expects($this->once())
            ->method('fetch')
            ->with('class')
            ->willReturn(['choices'=>true, 'complex'=>false]);

        $chooser = $this->getChooser($cacheMock);
        $this->assertTrue($chooser->getChoices('class'));
        $this->assertFalse($chooser->getComplexChoices('class'));
    }

    public function testCacheHitDoesNotBuildComplex(): void
    {
        $cacheMock = $this->createMock(ArrayCache::class);
        $cacheMock->expects($this->once())
            ->method('contains')
            ->with('class')
            ->willReturn(true);

        $cacheMock->expects($this->once())
            ->method('fetch')
            ->with('class')
            ->willReturn(['choices'=>true, 'complex'=>false]);

        $chooser = $this->getChooser($cacheMock);
        $this->assertFalse($chooser->getComplexChoices('class'));
        $this->assertTrue($chooser->getChoices('class'));
    }

    public function testCacheMissBuilds()
    {
        $meta = $this->createMock(ClassMetadata::class);

        $mockEntityMgr = $this->createMock(EntityManager::class);

        $mockEntityMgr
            ->expects($this->once())
            ->method('getClassMetadata')
            ->willReturn($meta);

        $cache = new ArrayCache();

        $chooser = $this->getMockBuilder(ColumnChooser::class)
            ->setMethods(['buildChoices', 'buildComplex'])
            ->setConstructorArgs([$mockEntityMgr, $cache])
            ->getMock();

        $chooser->expects($this->once())
            ->method('buildChoices')
            ->with($meta)
            ->willReturn(['here']);

        $chooser->expects($this->once())
            ->method('buildComplex')
            ->with($meta)
            ->willReturn(['here'=>false]);

        $retValue = $chooser->getChoices('class');

        $this->assertEquals(['here'], $retValue);
        $this->assertEquals(['choices'=> ['here'], 'complex'=> ['here'=>false]], $cache->fetch('class'));
    }

    public function testMetaChoicesWithoutAssociationName()
    {
        $meta = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFieldNames', 'getTypeOfField'])
            ->getMock();

        $meta->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(['field1', 'field2', 'field3']);

        $map = [
            ['field1','string'],
            ['field2','integer'],
            ['field3','TripleChoice'],
        ];
        $meta->method('getTypeOfField')
            ->will($this->returnValueMap($map));

        $chooser = $this->getChooser();

        $choices = $chooser->getMetaChoices($meta);
        $this->assertInternalType('array', $choices);
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
        $meta = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFieldNames', 'getTypeOfField'])
            ->getMock();

        $meta->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(['field2', 'field1', 'field3']);

        $map = [
            ['field1','string'],
            ['field2','integer'],
            ['field3','TripleChoice'],
        ];
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
        $this->assertEquals(['field1', 'field2', 'field3'], array_keys($choices));
    }

    public function testMetaChoicesWithAssociationName()
    {
        $meta = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->setMethods(['getFieldNames', 'getTypeOfField'])
            ->getMock();

        $meta->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(['field1', 'field2', 'field3']);

        $map = [
            ['field1','string'],
            ['field2','integer'],
            ['field3','TripleChoice'],
        ];
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

    public function getChooser($cache = null): ColumnChooser
    {
        $mockEntityMgr = $this->createMock(EntityManager::class);

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
        return [
            [Type::TARRAY,false],
            [Type::SIMPLE_ARRAY,false],
            [Type::JSON_ARRAY,false],
            [Type::BIGINT,false],
            [Type::BOOLEAN,false],
            [Type::DATETIME,true],
            [Type::DATETIMETZ,true],
            [Type::DATE,true],
            [Type::TIME,true],
            [Type::DECIMAL,false],
            [Type::INTEGER,false],
            [Type::OBJECT,false],
            [Type::SMALLINT,false],
            [Type::STRING,false],
            [Type::TEXT,false],
            [Type::BLOB,false],
            [Type::FLOAT,false],
            [Type::GUID,false],
            ['SampleType',true],
        ];
    }
}
