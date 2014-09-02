<?php

namespace NS\SentinelBundle\Result;

/**
 * Description of FieldPopulationResult
 *
 * @author gnat
 */
class FieldPopulationResult
{
    private $site;
    private $totalCases          = 0;
    private $csfCollectedCount   = 0;
    private $bloodCollectedCount = 0;
    private $bloodResultCount    = 0;

    public function getSite()
    {
        return $this->site;
    }

    public function getCountry()
    {
        return $this->site->getCountry();
    }

    public function getRegion()
    {
        return $this->site->getCountry()->getRegion();
    }

    public function getTotalCases()
    {
        return $this->totalCases;
    }

    public function getCsfCollectedCount()
    {
        return $this->csfCollectedCount;
    }

    public function getCsfCollectedPercent()
    {
        return ($this->totalCases > 0) ? ($this->csfCollectedCount/$this->totalCases)*100: 0;
    }

    public function getBloodCollectedCount()
    {
        return $this->bloodCollectedCount;
    }

    public function getBloodCollectedPercent()
    {
        return ($this->totalCases > 0) ? ($this->bloodCollectedCount/$this->totalCases)*100: 0;
    }

    public function getBloodResultCount()
    {
        return $this->bloodResultCount;
    }

    public function getBloodResultPercent()
    {
        return ($this->bloodCollectedCount > 0) ? ($this->bloodResultCount/$this->bloodCollectedCount)*100: 0;
    }

    public function setBloodResultCount($bloodResultCount)
    {
        $this->bloodResultCount = $bloodResultCount;
        return $this;
    }

    public function setBloodCollectedCount($bloodCollectedCount)
    {
        $this->bloodCollectedCount = $bloodCollectedCount;
        return $this;
    }

    public function setCsfCollectedCount($csfCollectedCount)
    {
        $this->csfCollectedCount = $csfCollectedCount;
        return $this;
    }

    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    public function setTotalCases($totalCases)
    {
        $this->totalCases = $totalCases;
        return $this;
    }
}
