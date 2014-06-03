<?php

namespace NS\ImportBundle\Tests\Entity;

use NS\ImportBundle\Entity\Column;
use NS\ImportBundle\Entity\Converter;

/**
 * Description of ColumnTest
 *
 * @author gnat
 */
class ColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleIntegerConversion()
    {
        $column = new Column();
        $column->setType('integer');
        $column->setValueMap(array(1=>'One',2=>'Two',3=>'Three'));

        $this->assertEquals('One', $column->convert(1), "One is converted");
        $this->assertEquals('Two', $column->convert(2), "Two is converted");
        $this->assertEquals('Three', $column->convert(3), "Three is converted");
    }

    public function testSimpleStringConversion()
    {
        $column = new Column();
        $column->setType('string');
        $column->setValueMap(array("1"=>'One',"2"=>'Two',"3"=>'Three'));

        $this->assertEquals('One', $column->convert("1"), "One is converted");
        $this->assertEquals('Two', $column->convert("2"), "Two is converted");
        $this->assertEquals('Three', $column->convert("3"), "Three is converted");
    }
}