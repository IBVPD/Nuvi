<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 2017-03-18
 * Time: 9:09 PM
 */

namespace NS\ImportBundle\Tests\Formatter;

use NS\ImportBundle\Formatter\DateTimeFormatter;
use Symfony\Component\PropertyAccess\PropertyPath;

class DateTimeFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $formatter = new DateTimeFormatter();
        $date = new \DateTime('2017-03-18');
        $immutableDate = new \DateTimeImmutable('2017-03-18');

        $this->assertEquals(50,$formatter->getPriority());
        $this->assertTrue($formatter->supports($date));
        $this->assertTrue($formatter->supports($immutableDate));
        $this->assertEquals('2017-03-18',$formatter->format($date, new PropertyPath('nothing')));
        $this->assertEquals('2017-03-18',$formatter->format($immutableDate, new PropertyPath('nothing')));
    }

    public function testCustomDefaultDateFormat()
    {
        $formatter = new DateTimeFormatter('Ymd');
        $date = new \DateTime('2017-03-18');
        $immutableDate = new \DateTimeImmutable('2017-03-18');

        $this->assertTrue($formatter->supports($date));
        $this->assertTrue($formatter->supports($immutableDate));
        $this->assertEquals('20170318',$formatter->format($date, new PropertyPath('nothing')));
        $this->assertEquals('20170318',$formatter->format($immutableDate, new PropertyPath('nothing')));
    }

    /**
     * @param $field
     * @param $expected
     *
     * @dataProvider getFields
     */
    public function testDefaultFields($field, $expected)
    {
        $formatter = new DateTimeFormatter('Ymd');
        $date = new \DateTimeImmutable('2017-03-17 11:16:30');

        $this->assertEquals($expected,$formatter->format($date,new PropertyPath($field)));
    }

    public function getFields()
    {
        return [
            [
                'createdAt', '2017-03-17 11:16:30',
                'updatedAt', '2017-03-17 11:16:30',
                'adm_date', '20170318',
                'csf_collect_time', '11:16',
                'blood_collect_time', '11:16',
                'pleural_fluid_collect_time', '11:16',
                'siteLab.received', '2017-03-17 11:16',
                'siteLab.csf_lab_time', '11:16',
                'siteLab.blood_lab_time', '11:16',
                'siteLab.other_lab_time', '11:16',
                'siteLab.createdAt', '2017-03-17 11:16:30',
                'siteLab.updatedAt', '2017-03-17 11:16:30',
                'referenceLab.createdAt', '2017-03-17 11:16:30',
                'referenceLab.updatedAt', '2017-03-17 11:16:30',
                'nationalLab.createdAt', '2017-03-17 11:16:30',
                'nationalLab.updatedAt', '2017-03-17 11:16:30',
            ]
        ];
    }
}
