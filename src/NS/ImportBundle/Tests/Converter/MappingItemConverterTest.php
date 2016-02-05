<?php

namespace NS\ImportBundle\Tests\Converter;

use Ddeboer\DataImport\Step\MappingStep;
use NS\ImportBundle\Converter\UnsetMappingItemConverter;
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

        foreach ($columns as $index => $colArray) {
            $column = new Column();
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

        $converter = new MappingStep($map->getMappedColumns());
        $converter->process($data);
        $this->assertEquals($expected, $data);
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

        $converter = new MappingStep();
        $converter->map('[sub1]', '[Col2][sub1]');
        $converter->map('[sub2]', '[Col2][sub2]');
        $converter->process($data);
        $this->assertEquals($expected, $data);
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

        $converter = new UnsetMappingItemConverter();
        $converter->map('sub1', 'Col2.sub1');
        $converter->map('sub2', 'Col2.sub2');
        $converter->process($data);
        $this->assertEquals($expected, $data);
    }
}
