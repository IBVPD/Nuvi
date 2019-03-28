<?php

namespace NS\ImportBundle\Tests\Entity;

use NS\ImportBundle\Entity\Map;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use NS\SentinelBundle\Entity\IBD;

/**
 * Description of MapTest
 *
 * @author gnat
 */
class MapTest extends TestCase
{
    public function testGetMappers(): void
    {
        $map = new Map();

        $this->assertEquals('[some.thing]', $map->adjustMappingName('some.thing'));
        $this->assertEquals('[some][thing]', $map->adjustMappingTarget('some.thing'));
    }

    public function testNameFields(): void
    {
        $ibdClass = IBD::class;
        $file     = new UploadedFile(__DIR__ . '/../Fixtures/IBD.csv', 'IBD.csv');

        $map  = new Map();
        $map->setClass($ibdClass);
        $map->setName('Test File');
        $map->setVersion('1.0');
        $map->setFile($file);

        $this->assertEquals('Test File', $map->getName());
        $this->assertEquals('1.0', $map->getVersion());
        $this->assertEquals($file, $map->getFile());
        $this->assertEquals(sprintf('%s (%s %s)', $map->getName(), $map->getSimpleClass(), $map->getVersion()), $map->__toString());

        $map->setName('Really Really Long Map Name');
        $this->assertEquals(sprintf('%s (%s: ...)', $map->getName(), $map->getSimpleClass()), $map->getSelectName());
    }
}
