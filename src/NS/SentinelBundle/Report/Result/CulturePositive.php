<?php

namespace NS\SentinelBundle\Report\Result;

/**
 * Description of CulturePositive
 *
 * @author gnat
 */
/**
 * Class CulturePositive
 * @package NS\SentinelBundle\Result
 */
class CulturePositive
{
    public const CULTURE_POSITIVE = 'cultPositive';
    public const CULTURE_NEGATIVE = 'cultNegative';
    public const PCR_POSITIVE = 'pcrPositive';

    /**
     * @var array
     */
    private $results;

    /**
     * @param array $cultPos
     * @param array $cultNeg
     * @param array $pcrPos
     */
    public function __construct($cultPos = [], $cultNeg = [], $pcrPos = [])
    {
        $this->results = [];
        $tmp = ['year' => 0, self::CULTURE_POSITIVE => 0, self::CULTURE_NEGATIVE => 0, self::PCR_POSITIVE => 0];

        foreach ($cultPos as $r) {
            if (!isset($this->results[$r['theYear']])) {
                $this->results[$r['theYear']] = $tmp;
                $this->results[$r['theYear']]['year'] = $r['theYear'];
            }

            $this->results[$r['theYear']][self::CULTURE_POSITIVE] = $r['caseCount'];
        }

        foreach ($cultNeg as $r) {
            if (!isset($this->results[$r['theYear']])) {
                $this->results[$r['theYear']] = $tmp;
                $this->results[$r['theYear']]['year'] = $r['theYear'];
            }

            $this->results[$r['theYear']]['cultNegative'] = $r['caseCount'];
        }

        foreach ($pcrPos as $r) {
            if (!isset($this->results[$r['theYear']])) {
                $this->results[$r['theYear']] = $tmp;
                $this->results[$r['theYear']]['year'] = $r['theYear'];
            }

            $this->results[$r['theYear']]['pcrPositive'] = $r['caseCount'];
        }
    }

    /**
     * @return array
     */
    public function getYears()
    {
        return array_keys($this->results);
    }

    /**
     * @param $param
     * @param null $year
     * @return int
     */
    public function get($param, $year = null)
    {
        if ($year !== null) {
            return isset($this->results[$year][$param]) ? $this->results[$year][$param] : 0;
        } else {
            $total = 0;
            foreach ($this->results as $r) {
                $total += $r[$param];
            }

            return $total;
        }
    }

    /**
     * @param null $year
     * @return int
     */
    public function getCulturePositive($year = null)
    {
        return $this->get(self::CULTURE_POSITIVE, $year);
    }

    /**
     * @param null $year
     * @return int
     */
    public function getCultureNegative($year = null)
    {
        return $this->get(self::CULTURE_NEGATIVE, $year);
    }

    /**
     * @param null $year
     * @return int
     */
    public function getPcrPositive($year = null)
    {
        return $this->get(self::PCR_POSITIVE, $year);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->results;
    }
}
