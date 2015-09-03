<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 03/09/15
 * Time: 10:53 AM
 */

namespace NS\ImportBundle\Tests\Converter;

use NS\ImportBundle\Converter\WarningConverter;
use NS\ImportBundle\Tests\TestArrayChoice;

class WarningConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testNoWarning()
    {
        $data = array('var1'=>new TestArrayChoice(),'var2'=>'nothing','var3' => null);
        $converter = new WarningConverter();
        $converter->__invoke($data);
        $this->assertArrayNotHasKey('warning',$data);
    }

    public function testHasWarning()
    {
        $data = array('var1'=>new TestArrayChoice(\NS\UtilBundle\Form\Types\ArrayChoice::OUT_OF_RANGE),'var2'=>'nothing','var3' => null);
        $converter = new WarningConverter();
        $converter->__invoke($data);
        $this->assertArrayHasKey('warning',$data);
        $this->assertTrue($data['warning']);
    }
}