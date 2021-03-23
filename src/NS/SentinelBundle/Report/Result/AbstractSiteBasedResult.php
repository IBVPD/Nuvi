<?php

namespace NS\SentinelBundle\Report\Result;

use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Site;

abstract class AbstractSiteBasedResult
{
    /** @var Site */
    private $site;

    /** @var int */
    private $totalCases = 0;

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

    /**
     * @param Site $site
     * @return AbstractSiteBasedResult
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->site->getCountry();
    }

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->site->getCountry()->getRegion();
    }

    /**
     * @return int
     */
    public function getTotalCases()
    {
        return $this->totalCases;
    }

    /**
     * @param int $totalCases
     * @return AbstractSiteBasedResult
     */
    public function setTotalCases($totalCases)
    {
        $this->totalCases = $totalCases;
        return $this;
    }
}
