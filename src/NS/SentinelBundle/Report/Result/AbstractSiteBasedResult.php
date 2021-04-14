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
    protected $totalCases = 0;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    public function setSite(Site $site): void
    {
        $this->site = $site;
    }

    public function getCountry(): Country
    {
        return $this->site->getCountry();
    }

    public function getRegion(): Region
    {
        return $this->site->getCountry()->getRegion();
    }

    public function getTotalCases(): int
    {
        return $this->totalCases;
    }

    public function setTotalCases(int $totalCases): void
    {
        $this->totalCases = $totalCases;
    }
}
