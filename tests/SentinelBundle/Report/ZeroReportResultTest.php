<?php

namespace NS\SentinelBundle\Tests\Report;

use DateTime;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Report\Result\ZeroReportResult;
use PHPUnit\Framework\TestCase;

class ZeroReportResultTest extends TestCase
{
    public function testInitialization(): void
    {
        $report = new ZeroReportResult('IBD',new DateTime(),new DateTime());
        $this->assertInstanceOf(ZeroReportResult::class, $report);
    }

    public function testGetDates(): void
    {
        $report = new ZeroReportResult('IBD',new DateTime('2016-01-01'),new DateTime('2016-05-31'));

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

    public function testAddCase(): void
    {
        $site = new Site();
        $site->setCode('SCODE');

        $report = new ZeroReportResult('IBD', new DateTime('2016-01-01'), new DateTime('2016-05-31'));
        $report->addCaseCount($site, 2016, 2, 7);

        $this->assertCount(1, $report->getZeroReportResults());
        $report->addCaseCount($site, 2016, 3, 10);
        $this->assertCount(1, $report->getZeroReportResults());
    }

    public function testAddZeroReport(): void
    {
        $site = new Site();
        $site->setCode('SCODE');

        $report = new ZeroReportResult('IBD', new DateTime('2016-01-01'), new DateTime('2016-05-31'));
        $report->addZeroReport($site, 2016, 2, 'zero');

        $this->assertCount(1, $report->getZeroReportResults());
        $report->addZeroReport($site, 2016, 3, 'non');
        $this->assertCount(1, $report->getZeroReportResults());
    }
}
