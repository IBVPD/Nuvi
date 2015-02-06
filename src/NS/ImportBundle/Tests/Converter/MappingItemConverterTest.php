<?php

namespace NS\ImportBundle\Tests\Converter;

use \NS\ImportBundle\Entity\Column;
use \NS\ImportBundle\Entity\Map;

/**
 * Description of MappingItemConverterTest
 *
 * @author gnat
 */
class MappingItemConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testBlankMap()
    {
        $columns = array(
            array(
                'name'      => 'Col1',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
            array(
                'name'      => 'Col2',
                'converter' => null,
                'mapper'    => 'col2',
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col3',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => false,
            ),
            array(
                'name'      => 'Col4',
                'converter' => null,
                'mapper'    => null,
                'ignored'   => true,
            ),
        );

        $map = new Map();

        foreach ($columns as $index => $colArray)
        {
            $column = new Column();
            $column->setOrder($index);
            $column->setName($colArray['name']);
            $column->setConverter($colArray['converter']);
            $column->setMapper($colArray['mapper']);
            $column->setIgnored($colArray['ignored']);
            $map->addColumn($column);
        }

        $data = array(
            'Col1' => 1,
            'Col2' => 2,
            'Col3' => 3,
            'Col4' => 4,
        );

        $expected = array(
            'Col1' => 1,
            'Col3' => 3,
            'Col4' => 4,
            'col2' => 2,
        );

        $converter = $map->getMappings();
        $result    = $converter->convert($data);
        $this->assertEquals($expected, $result);
    }

    public function testDeepMapping()
    {
        $data = array(
            'Col1' => 1,
            'sub1' => 2,
            'sub2' => 3,
            'Col4' => 4,
        );

        $expected = array(
            'Col1' => 1,
            'Col4' => 4,
            'Col2' => array('sub1' => 2, 'sub2' => 3),
        );

        $converter = new \NS\ImportBundle\Converter\MappingItemConverter();
        $converter->addMapping('sub1', 'Col2.sub1');
        $converter->addMapping('sub2', 'Col2.sub2');
        $result    = $converter->convert($data);
        $this->assertEquals($expected, $result);
    }

    /**
     * @expectedException \Ddeboer\DataImport\Exception\InvalidArgumentException
     */
    public function testTooDeepMapping()
    {
        $data = array(
            'Col1' => 1,
            'sub1' => 2,
            'sub2' => 3,
            'Col4' => 4,
        );


        $converter = new \NS\ImportBundle\Converter\MappingItemConverter();
        $converter->addMapping('sub1', 'Col2.sub1.subthree');
        $converter->convert($data);
    }

    public function testUnsetMappingConverter()
    {
        $data = array(
            'Col1' => 1,
            'sub1' => 2,
            'sub2' => 3,
            'Col4' => 4,
        );

        $expected = array(
            'Col1' => 1,
            'Col4' => 4,
        );

        $converter = new \NS\ImportBundle\Converter\UnsetMappingItemConverter();
        $converter->addMapping('sub1', 'Col2.sub1');
        $converter->addMapping('sub2', 'Col2.sub2');
        $result    = $converter->convert($data);
        $this->assertEquals($expected, $result);
    }
}