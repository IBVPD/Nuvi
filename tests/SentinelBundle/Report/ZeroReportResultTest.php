<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 23/05/16
 * Time: 4:30 PM
 */

namespace NS\SentinelBundle\Tests\Report;


use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Report\Result\ZeroReportResult;

class ZeroReportResultTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialization()
    {
        $report = new ZeroReportResult('NSSentinelBundle:IBD',new \DateTime(),new \DateTime());
        $this->assertInstanceOf('NS\SentinelBundle\Report\Result\ZeroReportResult', $report);
    }

    public function testGetDates()
    {
        $report = new ZeroReportResult('NSSentinelBundle:IBD',new \DateTime('2016-01-01'),new \DateTime('2016-05-31'));

        $dates = $report->getDates();
        $this->assertCount(5, $dates);
        $this->assertArrayHasKey('2016-01', $dates);
        $jan = $dates['2016-01'];
        $this->assertCount(2, $jan);
        $this->assertArrayHasKey('month', $jan);
        $this->assertArrayHasKey('year', $jan);
        $this->assertEquals(1, $jan['month']);
        $this->assertEquals(2016, $jan['year']);
    }

    public function testAddCase()
    {
        $site = new Site();
        $site->setCode('SCODE');

        $report = new ZeroReportResult('NSSentinelBundle:IBD', new \DateTime('2016-01-01'), new \DateTime('2016-05-31'));
        $report->addCaseCount($site, 2016, 2, 7);

        $this->assertCount(1, $report->getZeroReportResults());
        $report->addCaseCount($site, 2016, 3, 10);
        $this->assertCount(1, $report->getZeroReportResults());
    }

    public function testAddZeroReport()
    {
        $site = new Site();
        $site->setCode('SCODE');

        $report = new ZeroReportResult('NSSentinelBundle:IBD', new \DateTime('2016-01-01'), new \DateTime('2016-05-31'));
        $report->addZeroReport($site, 2016, 2, 'zero');

        $this->assertCount(1, $report->getZeroReportResults());
        $report->addZeroReport($site, 2016, 3, 'non');
        $this->assertCount(1, $report->getZeroReportResults());
    }
}
