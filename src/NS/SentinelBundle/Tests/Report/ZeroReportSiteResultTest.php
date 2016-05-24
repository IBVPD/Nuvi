<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 23/05/16
 * Time: 4:21 PM
 */

namespace NS\SentinelBundle\Tests\Report;


use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Entity\ZeroReport;
use NS\SentinelBundle\Report\Result\ZeroReportSiteResult;

class ZeroReportSiteResultTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialization()
    {
        $site = new Site();
        $site->setCode('SCODE');

        $result = new ZeroReportSiteResult($site);
        $this->assertInstanceOf('NS\SentinelBundle\Report\Result\ZeroReportSiteResult', $result);

        $this->assertEquals($site, $result->getSite());
    }

    public function testAddCaseCount()
    {
        $site = new Site();
        $site->setCode('SCODE');

        $result = new ZeroReportSiteResult($site);
        $result->addCaseCount(2016, 2, 7);

        $this->assertEquals(7, $result->getCountOrZeroReport(2016, 2));
        $this->assertNull($result->getCountOrZeroReport(2016, 3));
    }

    public function testAddZeroReport()
    {
        $site = new Site();
        $site->setCode('SCODE');

        $result = new ZeroReportSiteResult($site);
        $result->addZeroReport(2016, 2,'zero');
        $this->assertEquals('zero', $result->getCountOrZeroReport(2016, 2));
        $this->assertNull($result->getCountOrZeroReport(2016, 3));
    }
}
