<?php

namespace NS\ImportBundle\Tests\Entity;

use NS\ImportBundle\Entity\Map;

/**
 * Description of MapTest
 *
 * @author gnat
 */
class MapTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMappers()
    {
        $map = new Map();

        $this->assertEquals('[some.thing]',$map->adjustMappingName('some.thing'));
        $this->assertEquals('[some][thing]',$map->adjustMappingTarget('some.thing'));
    }
}