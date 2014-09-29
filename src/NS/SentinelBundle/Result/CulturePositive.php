<?php

namespace NS\SentinelBundle\Result;

/**
 * Description of CulturePositive
 *
 * @author gnat
 */
class CulturePositive
{
    const CULTURE_POSITIVE = 'cultPositive';
    const CULTURE_NEGATIVE = 'cultNegative';
    const PCR_POSITIVE     = 'pcrPositive';

    private $results;

    public function __construct($cultPos = array(), $cultNeg = array(), $pcrPos = array())
    {
        $this->results = array();
        $ar            = array('year' => 0, self::CULTURE_POSITIVE => 0, self::CULTURE_NEGATIVE => 0, self::PCR_POSITIVE => 0);

        foreach($cultPos as $r)
        {
            if(!isset($this->results[$r['theYear']]))
            {
                $this->results[$r['theYear']] = $ar;
                $this->results[$r['theYear']]['year'] = $r['theYear'];
            }

            $this->results[$r['theYear']][self::CULTURE_POSITIVE] = $r['caseCount'];
        }

        foreach($cultNeg as $r)
        {
            if(!isset($this->results[$r['theYear']]))
            {
                $this->results[$r['theYear']] = $ar;
                $this->results[$r['theYear']]['year'] = $r['theYear'];
            }

            $this->results[$r['theYear']]['cultNegative'] = $r['caseCount'];
        }

        foreach($pcrPos as $r)
        {
            if(!isset($this->results[$r['theYear']]))
            {
                $this->results[$r['theYear']] = $ar;
                $this->results[$r['theYear']]['year'] = $r['theYear'];
            }

            $this->results[$r['theYear']]['pcrPositive'] = $r['caseCount'];
        }
    }

    public function getYears()
    {
        return array_keys($this->results);
    }

    public function get($param, $year=null)
    {
        if(!is_null($year))
        {
            return (isset($this->results[$year][$param])) ? $this->results[$year][$param]:0;
        }
        else
        {
            $t = 0;
            foreach($this->results as $r)
                $t += $r[$param];

            return $t;
        }

    }

    public function getCulturePositive($year = null)
    {
        return $this->get(self::CULTURE_POSITIVE, $year);
    }

    public function getCultureNegative($year = null)
    {
        return $this->get(self::CULTURE_NEGATIVE, $year);
    }

    public function getPcrPositive($year = null)
    {
        return $this->get(self::PCR_POSITIVE, $year);
    }

    public function toArray()
    {
        return $this->results;
    }
}
