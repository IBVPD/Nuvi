<?php

namespace NS\ImportBundle\Tests\Converter;

use DateTime;
use Ddeboer\DataImport\Exception\UnexpectedValueException;
use NS\ImportBundle\Converter\DateTimeValueConverter;
use PHPUnit\Framework\TestCase;

/**
 * Description of DateTimeValueConverter
 *
 * @author gnat
 */
class DateTimeValueConverterTest extends TestCase
{
    public function testDateName(): void
    {
        $converter = new DateTimeValueConverter('Y-m-d');
        $this->assertEquals('Date: Y-m-d', $converter->getName());
    }

    public function testValidDate(): void
    {
        $converter = new DateTimeValueConverter('Y-m-d|');
        $dateObj   = new DateTime('2014-01-10');
        $this->assertEquals($dateObj, $converter->__invoke($dateObj->format('Y-m-d')));
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testInValidDate(): void
    {
        $converter = new DateTimeValueConverter('Y-m-d');
        $dateStr   = '2014/10/01';
        $this->assertEquals(new DateTime('2014-10-01'), $converter->__invoke($dateStr));
    }

    public function testExcelDate(): void
    {
        $converter = new DateTimeValueConverter('Y-m-d|');

        $this->assertEquals(new DateTime('2016-01-30'), $converter->__invoke(42399));
    }
}
