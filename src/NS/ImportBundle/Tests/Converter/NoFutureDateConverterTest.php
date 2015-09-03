<?php

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\NoFutureDateConverter;

class NoFutureDateConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testNoFutureDate()
    {
        $data = array(
            'field1'=>null,
            'field2'=>new \DateTime(),
            'field3'=> new \DateTime('yesterday'),
            'field4'=>'a string',
            'field5'=>true,
        );
        $converter = new NoFutureDateConverter();
        $retData = $converter->__invoke($data);
        $this->assertEquals($data,$retData);
    }

    public function testFutureDate()
    {
        $data = array(
            'field1'=>null,
            'field2'=>new \DateTime('tomorrow'),
            'field3'=> new \DateTime('yesterday'),
            'field4'=>'a string',
            'field5'=>true,
        );
        $converter = new NoFutureDateConverter();
        $retData = $converter->__invoke($data);
        $this->assertNotEquals($data,$retData);
        $this->assertNull($retData['field2']);
        $this->assertArrayHasKey('warning',$retData);
        $this->assertTrue($retData['warning']);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[field2] has a date in the future ('.$data['field2']->format('Y-m-d').'). ',$converter->getMessage());
    }
}