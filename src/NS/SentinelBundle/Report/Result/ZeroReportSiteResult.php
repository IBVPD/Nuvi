<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/05/16
 * Time: 4:43 PM
 */

namespace NS\SentinelBundle\Report\Result;


class ZeroReportSiteResult extends AbstractSiteBasedResult
{
    /**
     * @var array
     */
    private $caseCounts = array();

    /**
     * @var array
     */
    private $zeroReports = array();

    /**
     * @param int $year
     * @param int $month
     * @param int $count
     */
    public function addCaseCount($year, $month, $count)
    {
        $this->caseCounts[$year][$month] = $count;
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $type
     */
    public function addZeroReport($year, $month, $type)
    {
        $this->zeroReports[$year][(int)$month] = $type;
    }

    /**
     * @param int $year
     * @param int $month
     * @return mixed|null
     */
    public function getCountOrZeroReport($year, $month)
    {
        if (isset($this->zeroReports[$year][(int)$month])) {
            return $this->zeroReports[$year][(int)$month];
        }

        if (isset($this->caseCounts[$year][$month])) {
            return $this->caseCounts[$year][$month];
        }

        return null;
    }
}

