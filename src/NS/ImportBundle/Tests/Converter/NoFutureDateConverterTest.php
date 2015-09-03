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

    public function testGetKey()
    {
        $converter = new NoFutureDateConverter();
        $retOne = $converter->getKey('key');
        $this->assertEquals('key',$retOne);

        $retTwo = $converter->getKey('child','parent');
        $this->assertEquals('parent.child',$retTwo);

        $retThree = $converter->getKey('subChild','parent.child');
        $this->assertEquals('parent.child.subChild',$retThree);
    }

    public function testRecursionNoFutureDate()
    {
        $today = new \DateTime();
        $yester = new \DateTime('yesterday');

        $data = array(
            'field1'=>null,
            'field2'=> $today,
            'field3'=> $yester,
            'field4'=>'a string',
            'field5'=>true,
        );
        $sub1 = $data;
        $sub2 = $data;
        $sub1['sub2'] = $sub2;
        $data['sub']  = $sub1;

        $converter = new NoFutureDateConverter();
        $retData = $converter->__invoke($data);
        $this->assertEquals($data,$retData);
    }

    public function testRecursionFutureDate()
    {
        $today = new \DateTime();
        $yester = new \DateTime('yesterday');
        $tomorrow = new \DateTime('2999-01-02');

        $data = array(
            'field1'=>null,
            'field2'=> $today,
            'field3'=> $yester,
            'field4'=>'a string',
            'field5'=>true,
        );
        $sub1 = $data;
        $sub2 = $data;
        $sub2['tom'] = $tomorrow;

        $sub1['sub2'] = $sub2;
        $data['sub']  = $sub1;

        $converter = new NoFutureDateConverter();
        $retData = $converter->__invoke($data);
        $this->assertTrue($converter->hasMessage());
        $this->assertEquals('[sub.sub2.tom] has a date in the future (2999-01-02). ',$converter->getMessage());
    }
}