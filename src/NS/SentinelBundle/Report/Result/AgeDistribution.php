<?php

namespace NS\SentinelBundle\Report\Result;

use NS\SentinelBundle\Entity\BaseCase;

/**
 * Description of AgeDistribution
 *
 * @author gnat
 */
class AgeDistribution
{
    /**
     * @var array
     */
    private $results;

    /**
     * @param array $results
     */
    public function __construct(array $results = array())
    {
        $this->results = array();

        foreach ($results as $case) {
            $this->results[$case['theYear']][$case['ageDistribution']] = $case['theCount'];
            if (!isset($this->results[$case['theYear']]['total'])) {
                $this->results[$case['theYear']]['total'] = $case['theCount'];
            } else {
                $this->results[$case['theYear']]['total'] += $case['theCount'];
            }
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
     * @param $year
     * @param $ageDistribution
     * @return int
     */
    public function getResult($year, $ageDistribution)
    {
        return (isset($this->results[$year][$ageDistribution])) ? $this->results[$year][$ageDistribution]:0;
    }

    /**
     * @param $year
     * @return int
     */
    public function getTotal($year)
    {
        return $this->getResult($year, 'total');
    }

    /**
     * @param $ageDistribution
     * @return int
     */
    public function sumYears($ageDistribution)
    {
        $theSum = 0;
        foreach ($this->results as $values) {
            if (isset($values[$ageDistribution])) {
                $theSum+= $values[$ageDistribution];
            }
        }

        return $theSum;
    }

    /**
     * @param null $year
     * @return int
     */
    public function getZeroToFive($year = null)
    {
        return $year === null ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_00_TO_05):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_00_TO_05);
    }

    /**
     * @param null $year
     * @return int
     */
    public function getFiveToEleven($year = null)
    {
        return $year === null ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_05_TO_11):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_05_TO_11);
    }

    /**
     * @param null $year
     * @return int
     */
    public function getElevenToTwentyThree($year = null)
    {
        return $year === null ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_11_TO_23):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_11_TO_23);
    }

    /**
     * @param null $year
     * @return int
     */
    public function getTwentyThreeToFiftyNine($year = null)
    {
        return $year === null ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_23_TO_59):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_23_TO_59);
    }

    /**
     * @param null $year
     * @return int
     */
    public function getUnknown($year = null)
    {
        return $year === null ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_UNKNOWN):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_UNKNOWN);
    }

    /**
     * @param $year
     * @return float|int
     */
    public function getZeroToFivePercent($year)
    {
        if (isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0) {
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_00_TO_05)/$this->results[$year]['total'])*100;
        }

        return 0;
    }

    /**
     * @param $year
     * @return float|int
     */
    public function getFiveToElevenPercent($year)
    {
        if (isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0) {
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_05_TO_11)/$this->results[$year]['total'])*100;
        }

        return 0;
    }

    /**
     * @param $year
     * @return float|int
     */
    public function getElevenToTwentyThreePercent($year)
    {
        if (isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0) {
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_11_TO_23)/$this->results[$year]['total'])*100;
        }

        return 0;
    }

    /**
     * @param $year
     * @return float|int
     */
    public function getTwentyThreeToFiftyNinePercent($year)
    {
        if (isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0) {
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_23_TO_59)/$this->results[$year]['total'])*100;
        }

        return 0;
    }

    /**
     * @param $year
     * @return float|int
     */
    public function getUnknownPercent($year)
    {
        if (isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0) {
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_UNKNOWN)/$this->results[$year]['total'])*100;
        }

        return 0;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $res = array();
        foreach ($this->results as $year => $values) {
            $res[] = array( 'year'    => $year,
                            'total'   => (isset($values['total'])?$values['total']:0),
                            '0-5'     => $this->getZeroToFive($year),
                            '5-11'    => $this->getFiveToEleven($year),
                            '11-23'   => $this->getElevenToTwentyThree($year),
                            '23-59'   => $this->getTwentyThreeToFiftyNine($year),
                            'Unknown' => $this->getUnknown($year)
                          );
        }

        return $res;
    }
}
