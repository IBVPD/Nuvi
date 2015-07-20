<?php

namespace NS\ImportBundle\Tests\Converter;

use \Ddeboer\DataImport\Exception\UnexpectedValueException;
use \NS\ImportBundle\Converter\DateTimeValueConverter;

/**
 * Description of DateTimeValueConverter
 *
 * @author gnat
 */
class DateTimeValueConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testDateName()
    {
        $converter = new DateTimeValueConverter('Y-m-d');
        $this->assertEquals('Date: Y-m-d', $converter->getName());
    }

    public function testValidDate()
    {
        $converter = new DateTimeValueConverter('Y-m-d|');
        $dateObj   = new \DateTime('2014-01-10');
        $this->assertEquals($dateObj, $converter->__invoke($dateObj->format('Y-m-d')));
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testInValidDate()
    {
        $converter = new DateTimeValueConverter('Y-m-d');
        $dateStr   = '2014/10/01';
        $this->assertEquals(new \DateTime('2014-10-01'), $converter->__invoke($dateStr));
    }
}