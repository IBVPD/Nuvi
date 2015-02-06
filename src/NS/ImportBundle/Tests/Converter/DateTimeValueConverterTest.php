<?php

namespace NS\ImportBundle\Tests\Converter;

/**
 * Description of DateTimeValueConverter
 *
 * @author gnat
 */
class DateTimeValueConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testDateName()
    {
        $converter = new \NS\ImportBundle\Converter\DateTimeValueConverter('Y-m-d');
        $this->assertEquals('Date: Y-m-d', $converter->getName());
    }

    public function testValidDate()
    {
        $converter = new \NS\ImportBundle\Converter\DateTimeValueConverter('Y-m-d|');
        $dateObj   = new \DateTime('2014-01-10');
        $this->assertEquals($dateObj, $converter->convert($dateObj->format('Y-m-d')));
    }

    /**
     * @expectedException Ddeboer\DataImport\Exception\UnexpectedValueException
     */
    public function testInValidDate()
    {
        $converter = new \NS\ImportBundle\Converter\DateTimeValueConverter('Y-m-d');
        $dateStr   = '2014/10/01';
        $this->assertEquals(new \DateTime('2014-10-01'), $converter->convert($dateStr));
    }
}