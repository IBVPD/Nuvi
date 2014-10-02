<?php

namespace NS\SentinelBundle\Result;

use NS\SentinelBundle\Entity\BaseCase;

/**
 * Description of AgeDistribution
 *
 * @author gnat
 */
class AgeDistribution
{
    private $results;

    public function __construct(array $results = array())
    {
        $this->results = array();

        foreach($results as $case)
        {
            $this->results[$case['theYear']][$case['ageDistribution']] = $case['theCount'];
            if(!isset($this->results[$case['theYear']]['total']))
                $this->results[$case['theYear']]['total'] = $case['theCount'];
            else
                $this->results[$case['theYear']]['total'] += $case['theCount'];
        }
    }

    public function getYears()
    {
        return array_keys($this->results);
    }

    public function getResult($year,$ageDistribution)
    {
        return (isset($this->results[$year][$ageDistribution])) ? $this->results[$year][$ageDistribution]:0;
    }

    public function getTotal($year)
    {
        return $this->getResult($year,'total');
    }

    public function sumYears($ageDistribution)
    {
        $s = 0;
        foreach($this->results as $values)
        {
            if(isset($values[$ageDistribution]))
                $s+= $values[$ageDistribution];
        }

        return $s;
    }

    public function getZeroToFive($year = null)
    {
        return is_null($year) ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_00_TO_05):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_00_TO_05);
    }

    public function getFiveToEleven($year = null)
    {
        return is_null($year) ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_05_TO_11):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_05_TO_11);
    }

    public function getElevenToTwentyThree($year = null)
    {
        return is_null($year) ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_11_TO_23):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_11_TO_23);
    }

    public function getTwentyThreeToFiftyNine($year = null)
    {
        return is_null($year) ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_23_TO_59):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_23_TO_59);
    }

    public function getUnknown($year = null)
    {
        return is_null($year) ? $this->sumYears(BaseCase::AGE_DISTRIBUTION_UNKNOWN):$this->getResult($year, BaseCase::AGE_DISTRIBUTION_UNKNOWN);
    }

    public function getZeroToFivePercent($year)
    {
        if(isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0)
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_00_TO_05)/$this->results[$year]['total'])*100;

        return 0;
    }

    public function getFiveToElevenPercent($year)
    {
        if(isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0)
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_05_TO_11)/$this->results[$year]['total'])*100;

        return 0;
    }

    public function getElevenToTwentyThreePercent($year)
    {
        if(isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0)
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_11_TO_23)/$this->results[$year]['total'])*100;

        return 0;
    }

    public function getTwentyThreeToFiftyNinePercent($year)
    {
        if(isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0)
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_23_TO_59)/$this->results[$year]['total'])*100;

        return 0;
    }

    public function getUnknownPercent($year)
    {
        if(isset($this->results[$year]['total']) && $this->results[$year]['total'] > 0)
            return ($this->getResult($year, BaseCase::AGE_DISTRIBUTION_UNKNOWN)/$this->results[$year]['total'])*100;

        return 0;
    }

    public function toArray()
    {
        $res = array();
        foreach($this->results as $year => $values)
        {
            $res[] = array( 'year'    => $year,
                            'total'   => (isset($values['total'])?$values['total']:0),
                            '0-5'     => (isset($values[BaseCase::AGE_DISTRIBUTION_00_TO_05])?$values[BaseCase::AGE_DISTRIBUTION_00_TO_05]:0),
                            '5-11'    => (isset($values[BaseCase::AGE_DISTRIBUTION_05_TO_11])?$values[BaseCase::AGE_DISTRIBUTION_05_TO_11]:0),
                            '11-23'   => (isset($values[BaseCase::AGE_DISTRIBUTION_11_TO_23])?$values[BaseCase::AGE_DISTRIBUTION_11_TO_23]:0),
                            '23-59'   => (isset($values[BaseCase::AGE_DISTRIBUTION_23_TO_59])?$values[BaseCase::AGE_DISTRIBUTION_23_TO_59]:0),
                            'Unknown' => (isset($values[BaseCase::AGE_DISTRIBUTION_UNKNOWN])?$values[BaseCase::AGE_DISTRIBUTION_UNKNOWN]:0));
        }

        return $res;
    }
}
