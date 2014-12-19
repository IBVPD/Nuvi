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
            $column->setIsIgnored($colArray['ignored']);
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
}