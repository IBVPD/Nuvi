<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 20/06/18
 * Time: 11:02 AM
 */

namespace NS\SentinelBundle\Report\Result;

use NS\SentinelBundle\Entity\Site;

class SiteMonthResult
{
    /** @var Site */
    private $site;

    /** @var array */
    private $results = [
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0,
        6 => 0,
        7 => 0,
        8 => 0,
        9 => 0,
        10 => 0,
        11 => 0,
        12 => 0,
    ];

    /** @var int */
    private $total;

    /**
     * SiteMonthResult constructor.
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    public function addMonth($month, $count)
    {
        $this->results[$month] = $count;
        $this->total += $count;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getMonths()
    {
        return $this->results;
    }
}
