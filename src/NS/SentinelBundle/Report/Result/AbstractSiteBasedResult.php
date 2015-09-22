<?php

namespace NS\SentinelBundle\Report\Result;


use NS\SentinelBundle\Entity\Site;

/**
 * Class AbstractSiteBasedResult
 * @package NS\SentinelBundle\Result
 */
class AbstractSiteBasedResult
{
    /**
     * @var Site
     */
    private $site;
    /**
     * @var int
     */
    private $totalCases = 0;

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
     * @return \NS\SentinelBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->site->getCountry();
    }

    /**
     * @return \NS\SentinelBundle\Entity\Region
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
