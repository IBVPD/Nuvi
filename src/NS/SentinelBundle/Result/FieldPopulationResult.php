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
    private $csfResultCount      = 0;
    private $csfBinaxDoneCount   = 0;
    private $csfBinaxResultCount = 0;
    private $csfLatDoneCount     = 0;
    private $csfLatResultCount   = 0;
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

//*next I checked concordance with my bloodresult variable and the blood collected variable
//gen bloodequal=1 if  blood_collected== bloodresult
//by site_code: egen totalbloodequal=total(bloodequal)
//by site_code: gen propbloodequal= (totalbloodequal/ totalcase)*100
    public function getBloodEqual()
    {
        return ($this->totalCases > 0 && ($this->bloodCollectedCount>0 || $this->bloodResultCount>0) ) ? (min(array($this->bloodCollectedCount,$this->bloodResultCount))/$this->totalCases)*100:100;
    }

    public function getCsfResultCount()
    {
        return $this->csfResultCount;
    }

    public function getCsfResultPercent()
    {
        return ($this->csfCollectedCount > 0) ? ($this->csfResultCount/$this->csfCollectedCount)*100: 0;
    }

    public function getCsfBinaxDoneCount()
    {
        return $this->csfBinaxDoneCount;
    }

    public function setCsfBinaxDoneCount($csfBinaxDoneCount)
    {
        $this->csfBinaxDoneCount = $csfBinaxDoneCount;
        return $this;
    }

    public function getCsfBinaxResultCount()
    {
        return $this->csfBinaxResultCount;
    }

    public function getCsfBinaxResultPercent()
    {
        return ($this->csfBinaxDoneCount > 0) ? ($this->csfBinaxResultCount/$this->csfBinaxDoneCount)*100: 0;
    }

    public function setCsfBinaxResultCount($csfBinaxResultCount)
    {
        $this->csfBinaxResultCount = $csfBinaxResultCount;
        return $this;
    }

    public function getCsfLatDoneCount()
    {
        return $this->csfLatDoneCount;
    }

    public function setCsfLatDoneCount($csfLatDoneCount)
    {
        $this->csfLatDoneCount = $csfLatDoneCount;
        return $this;
    }

    public function getCsfLatResultCount()
    {
        return $this->csfLatResultCount;
    }

    public function getCsfLatResultPercent()
    {
        return ($this->csfLatDoneCount > 0) ? ($this->csfLatResultCount/$this->csfLatDoneCount)*100: 0;
    }

    public function setCsfLatResultCount($csfLatResultCount)
    {
        $this->csfLatResultCount = $csfLatResultCount;
        return $this;
    }

    public function setCsfResultCount($csfResultCount)
    {
        $this->csfResultCount = $csfResultCount;
        return $this;
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
