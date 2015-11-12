<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\UnsetMappingItemConverter;

class UnsetMappingItemConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testUnsetMappingProperlyUnsetsValue()
    {
        $data = array('field1'=>1,'field2'=>2,'field3'=>3);
        $converter = new UnsetMappingItemConverter(array('field1','field2'));
        $converter->process($data);

        $this->assertEquals(array('field3'=>3),$data);
    }
}