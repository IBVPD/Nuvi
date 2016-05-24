<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 24/05/16
 * Time: 10:58 AM
 */

namespace NS\SentinelBundle\Tests\Entity;


use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Entity\ZeroReport;

class ZeroReportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $year
     * @param $month
     * @param $expectedYear
     * @param $expectedMonth
     *
     * @dataProvider getDates
     */
    public function testYYMM($year, $month, $expectedYear, $expectedMonth)
    {
        $report = new ZeroReport(new Site(), 'NSSentinelBundle:IBD', 'zero', $month, $year);
        $this->assertEquals($expectedMonth, $report->getMonth());
        $this->assertEquals($expectedYear, $report->getYear());
    }

    public function getDates()
    {
        return array(
            array('2016', '05', 2016, '05'),
            array('2016', 5, 2016, '05'),
            array('2016', 10, 2016, '10'),
            array('2016', 12, 2016, '12'),
        );
    }

    public function testInvalidYear()
    {
        $this->setExpectedException('\InvalidArgumentException','Expecting a year after 2000');
        new ZeroReport(new Site(), 'NSSentinelBundle:IBD', 'zero', 5, 1999);
    }

    /**
     * @param $month
     * @dataProvider getMonths
     */
    public function testInvalidMonth($month)
    {
        $this->setExpectedException('\InvalidArgumentException','Expecting a month between 1 and 12');
        new ZeroReport(new Site(), 'NSSentinelBundle:IBD', 'zero', $month, 2016);
    }

    public function getMonths()
    {
        return array(
            array('0'),
            array(0),
            array(-1),
            array('-1'),
            array('13'),
            array(13),
            array('some string'),
        );
    }
}
