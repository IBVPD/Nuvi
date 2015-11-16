<?php

namespace NS\SentinelBundle\Report\Result\IBD;

use NS\SentinelBundle\Entity\Country;

class DataLinkingResult
{
    /**
     * @var Country
     */
    private $country;
    /**
     * @var int
     */
    private $totalCases = 0;

    /**
     * @var int
     */
    private $linked = 0;
    /**
     * @var int
     */
    private $notLinked = 0;
    /**
     * @var int
     */
    private $noLab = 0;

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return DataLinkingResult
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
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
     * @return DataLinkingResult
     */
    public function setTotalCases($totalCases)
    {
        $this->totalCases = $totalCases;
        return $this;
    }

    /**
     * @return int
     */
    public function getLinked()
    {
        return $this->linked;
    }

    public function getLinkedPercent()
    {
        return ($this->totalCases>0) ? $this->linked / $this->totalCases * 100: 0;
    }

    /**
     * @param int $linked
     */
    public function setLinked($linked)
    {
        $this->linked = $linked;
    }

    /**
     * @return int
     */
    public function getNotLinked()
    {
        return $this->notLinked;
    }

    public function getNotLinkedPercent()
    {
        return ($this->totalCases>0) ? $this->notLinked / $this->totalCases * 100: 0;
    }

    /**
     * @param int $notLinked
     */
    public function setNotLinked($notLinked)
    {
        $this->notLinked = $notLinked;
    }

    /**
     * @return int
     */
    public function getNoLab()
    {
        return $this->noLab;
    }

    /**
     * @param int $noLab
     */
    public function setNoLab($noLab)
    {
        $this->noLab = $noLab;
    }
}
