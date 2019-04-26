<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 23/05/16
 * Time: 11:03 AM
 */

namespace NS\SentinelBundle\Report\Result;


use DateInterval;
use DatePeriod;
use DateTime;
use NS\SentinelBundle\Entity\Site;

class ZeroReportResult
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var DateTime
     */
    private $from;

    /**
     * @var DateTime
     */
    private $to;

    /**
     * @var ZeroReportSiteResult[]
     */
    private $zeroReportResults;

    public function __construct($type, DateTime $from, DateTime $to)
    {
        $this->type = $type;
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * @param Site $site
     * @param int $year
     * @param int $month
     * @param int $caseCount
     */
    public function addCaseCount(Site $site, $year, $month, $caseCount)
    {
        $code = $site->getCode();
        if (!isset($this->zeroReportResults[$code])) {
            $this->zeroReportResults[$code] = new ZeroReportSiteResult($site);
        }

        $this->zeroReportResults[$code]->addCaseCount($year, $month, $caseCount);
    }

    /**
     * @param Site $site
     * @param int $year
     * @param int $month
     * @param string $result
     */
    public function addZeroReport(Site $site, $year, $month, $result)
    {
        if (!isset($this->zeroReportResults[$site->getCode()])) {
            $this->zeroReportResults[$site->getCode()] = new ZeroReportSiteResult($site);
        }

        $this->zeroReportResults[$site->getCode()]->addZeroReport($year, $month, $result);
    }

    /**
     * @var array
     */
    private $dates;

    /**
     * @return mixed
     */
    public function getDates()
    {
        if (!$this->dates) {
            $from = clone $this->from;
            $to = clone $this->to;

            $start = $from->modify('first day of this month');
            $end = $to->modify('first day of next month');

            $interval = DateInterval::createFromDateString('1 month');
            $period = new DatePeriod($start, $interval, $end);

            foreach ($period as $dt) {
                $this->dates[$dt->format('Y-m')] = ['month' => $dt->format('n'), 'year' => $dt->format('Y')];
            }
        }

        return $this->dates;
    }

    /**
     * @return array
     */
    public function getZeroReportResults()
    {
        return $this->zeroReportResults;
    }
}
