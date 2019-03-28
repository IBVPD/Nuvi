<?php

namespace NS\SentinelBundle\Tests\Entity;

use InvalidArgumentException;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Entity\ZeroReport;
use PHPUnit\Framework\TestCase;

class ZeroReportTest extends TestCase
{
    /**
     * @param $year
     * @param $month
     * @param $expectedYear
     * @param $expectedMonth
     *
     * @dataProvider getDates
     */
    public function testYYMM($year, $month, $expectedYear, $expectedMonth): void
    {
        $report = new ZeroReport(new Site(), 'NSSentinelBundle:IBD', 'zero', $month, $year);
        $this->assertEquals($expectedMonth, $report->getMonth());
        $this->assertEquals($expectedYear, $report->getYear());
    }

    public function getDates(): array
    {
        return [
            ['2016', '05', 2016, '05'],
            ['2016', 5, 2016, '05'],
            ['2016', 10, 2016, '10'],
            ['2016', 12, 2016, '12'],
        ];
    }

    public function testInvalidYear(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expecting a year after 2000');
        new ZeroReport(new Site(), 'NSSentinelBundle:IBD', 'zero', 5, 1999);
    }

    /**
     * @param $month
     * @dataProvider getMonths
     */
    public function testInvalidMonth($month): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expecting a month between 1 and 12');
        new ZeroReport(new Site(), 'NSSentinelBundle:IBD', 'zero', $month, 2016);
    }

    public function getMonths(): array
    {
        return [
            ['0'],
            [0],
            [-1],
            ['-1'],
            ['13'],
            [13],
            ['some string'],
        ];
    }
}
