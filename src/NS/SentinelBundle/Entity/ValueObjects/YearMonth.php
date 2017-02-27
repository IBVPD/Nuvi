<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 27/02/17
 * Time: 12:32 PM
 */

namespace NS\SentinelBundle\Entity\ValueObjects;

class YearMonth
{
    private $year;
    private $month;

    /**
     * YearMonth constructor.
     * @param $year
     * @param $month
     */
    public function __construct($year, $month)
    {
        $this->year = (int)$year;
        $this->month = $month;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    public function getMonths()
    {
        return ($this->year*12)+$this->month;
    }
}
