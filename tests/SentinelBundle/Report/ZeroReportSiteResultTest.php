<?php

namespace NS\SentinelBundle\Tests\Report;

use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Report\Result\ZeroReportSiteResult;
use PHPUnit\Framework\TestCase;

class ZeroReportSiteResultTest extends TestCase
{
    public function testInitialization(): void
    {
        $site = new Site();
        $site->setCode('SCODE');

        $result = new ZeroReportSiteResult($site);
        $this->assertInstanceOf(ZeroReportSiteResult::class, $result);

        $this->assertEquals($site, $result->getSite());
    }

    public function testAddCaseCount(): void
    {
        $site = new Site();
        $site->setCode('SCODE');

        $result = new ZeroReportSiteResult($site);
        $result->addCaseCount(2016, 2, 7);

        $this->assertEquals(7, $result->getCountOrZeroReport(2016, 2));
        $this->assertNull($result->getCountOrZeroReport(2016, 3));
    }

    public function testAddZeroReport(): void
    {
        $site = new Site();
        $site->setCode('SCODE');

        $result = new ZeroReportSiteResult($site);
        $result->addZeroReport(2016, 2,'zero');
        $this->assertEquals('zero', $result->getCountOrZeroReport(2016, 2));
        $this->assertNull($result->getCountOrZeroReport(2016, 3));
    }
}
