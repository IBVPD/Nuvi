<?php

namespace NS\SentinelBundle\Report\Result;

/**
 * Class FieldPopulationResult
 * @package NS\SentinelBundle\Report\Result
 */
class FieldPopulationResult extends AbstractSiteBasedResult
{
    /**
     * @var int
     */
    private $csfCollectedCount   = 0;
    /**
     * @var int
     */
    private $csfResultCount      = 0;
    /**
     * @var int
     */
    private $csfBinaxDoneCount   = 0;
    /**
     * @var int
     */
    private $csfBinaxResultCount = 0;
    /**
     * @var int
     */
    private $csfLatDoneCount     = 0;
    /**
     * @var int
     */
    private $csfLatResultCount   = 0;
    /**
     * @var int
     */
    private $csfPcrRecordedCount = 0;
    /**
     * @var int
     */
    private $csfSpnRecordedCount = 0;
    /**
     * @var int
     */
    private $csfHiRecordedCount  = 0;
    /**
     * @var int
     */
    private $bloodCollectedCount = 0;
    /**
     * @var int
     */
    private $bloodResultCount    = 0;
    /**
     * @var int
     */
    private $pcrPositiveCount    = 0;

    /**
     * @return int
     */
    public function getCsfCollectedCount()
    {
        return $this->csfCollectedCount;
    }

    /**
     * @return float|int
     */
    public function getCsfCollectedPercent()
    {
        return ($this->getTotalCases() > 0) ? ($this->csfCollectedCount/$this->getTotalCases())*100: 0;
    }

    /**
     * @return int
     */
    public function getBloodCollectedCount()
    {
        return $this->bloodCollectedCount;
    }

    /**
     * @return float|int
     */
    public function getBloodCollectedPercent()
    {
        return ($this->getTotalCases() > 0) ? ($this->bloodCollectedCount/$this->getTotalCases())*100: 0;
    }

    /**
     * @return int
     */
    public function getBloodResultCount()
    {
        return $this->bloodResultCount;
    }

    /**
     * @return float|int
     */
    public function getBloodResultPercent()
    {
        return ($this->bloodCollectedCount > 0) ? ($this->bloodResultCount/$this->bloodCollectedCount)*100: 0;
    }

//*next I checked concordance with my bloodresult variable and the blood collected variable
//gen bloodequal=1 if  blood_collected== bloodresult
//by site_code: egen totalbloodequal=total(bloodequal)
//by site_code: gen propbloodequal= (totalbloodequal/ totalcase)*100
    /**
     * @return float|int
     */
    public function getBloodEqual()
    {
        return ($this->getTotalCases() > 0 && ($this->bloodCollectedCount>0 || $this->bloodResultCount>0)) ? (min(array($this->bloodCollectedCount, $this->bloodResultCount))/$this->getTotalCases())*100:100;
    }

    /**
     * @return int
     */
    public function getCsfResultCount()
    {
        return $this->csfResultCount;
    }

    /**
     * @return float|int
     */
    public function getCsfResultPercent()
    {
        return ($this->csfCollectedCount > 0) ? ($this->csfResultCount/$this->csfCollectedCount)*100: 0;
    }

    /**
     * @return int
     */
    public function getCsfBinaxDoneCount()
    {
        return $this->csfBinaxDoneCount;
    }

    /**
     * @param $csfBinaxDoneCount
     * @return $this
     */
    public function setCsfBinaxDoneCount($csfBinaxDoneCount)
    {
        $this->csfBinaxDoneCount = $csfBinaxDoneCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getCsfBinaxResultCount()
    {
        return $this->csfBinaxResultCount;
    }

    /**
     * @return float|int
     */
    public function getCsfBinaxResultPercent()
    {
        return ($this->csfBinaxDoneCount > 0) ? ($this->csfBinaxResultCount/$this->csfBinaxDoneCount)*100: 0;
    }

    /**
     * @param $csfBinaxResultCount
     * @return $this
     */
    public function setCsfBinaxResultCount($csfBinaxResultCount)
    {
        $this->csfBinaxResultCount = $csfBinaxResultCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getCsfLatDoneCount()
    {
        return $this->csfLatDoneCount;
    }

    /**
     * @param $csfLatDoneCount
     * @return $this
     */
    public function setCsfLatDoneCount($csfLatDoneCount)
    {
        $this->csfLatDoneCount = $csfLatDoneCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getCsfLatResultCount()
    {
        return $this->csfLatResultCount;
    }

    /**
     * @return float|int
     */
    public function getCsfLatResultPercent()
    {
        return ($this->csfLatDoneCount > 0) ? ($this->csfLatResultCount/$this->csfLatDoneCount)*100: 0;
    }

    /**
     * @param $csfLatResultCount
     * @return $this
     */
    public function setCsfLatResultCount($csfLatResultCount)
    {
        $this->csfLatResultCount = $csfLatResultCount;
        return $this;
    }

    /**
     * @param $csfResultCount
     * @return $this
     */
    public function setCsfResultCount($csfResultCount)
    {
        $this->csfResultCount = $csfResultCount;
        return $this;
    }

    /**
     * @param $bloodResultCount
     * @return $this
     */
    public function setBloodResultCount($bloodResultCount)
    {
        $this->bloodResultCount = $bloodResultCount;
        return $this;
    }

    /**
     * @param $bloodCollectedCount
     * @return $this
     */
    public function setBloodCollectedCount($bloodCollectedCount)
    {
        $this->bloodCollectedCount = $bloodCollectedCount;
        return $this;
    }

    /**
     * @param $csfCollectedCount
     * @return $this
     */
    public function setCsfCollectedCount($csfCollectedCount)
    {
        $this->csfCollectedCount = $csfCollectedCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getCsfPcrRecordedCount()
    {
        return $this->csfPcrRecordedCount;
    }

    /**
     * @return float|int
     */
    public function getCsfPcrRecordedPercent()
    {
        return ($this->getTotalCases() > 0) ? ($this->csfPcrRecordedCount/$this->getTotalCases())*100: 0;
    }

    /**
     * @param $csfPcrRecorded
     * @return $this
     */
    public function setCsfPcrRecordedCount($csfPcrRecorded)
    {
        $this->csfPcrRecordedCount = $csfPcrRecorded;
        return $this;
    }

    /**
     * @return int
     */
    public function getCsfSpnRecordedCount()
    {
        return $this->csfSpnRecordedCount;
    }

    /**
     * @return float|int
     */
    public function getCsfSpnRecordedPercent()
    {
        return ($this->getTotalCases() > 0) ? ($this->csfSpnRecordedCount/$this->getTotalCases())*100: 0;
    }

    /**
     * @param $csfSpnRecorded
     * @return $this
     */
    public function setCsfSpnRecordedCount($csfSpnRecorded)
    {
        $this->csfSpnRecordedCount = $csfSpnRecorded;
        return $this;
    }

    /**
     * @return int
     */
    public function getCsfHiRecordedCount()
    {
        return $this->csfHiRecordedCount;
    }

    /**
     * @return float|int
     */
    public function getCsfHiRecordedPercent()
    {
        return ($this->getTotalCases() > 0) ? ($this->csfHiRecordedCount/$this->getTotalCases())*100: 0;
    }

    /**
     * @param $csfHiRecorded
     * @return $this
     */
    public function setCsfHiRecordedCount($csfHiRecorded)
    {
        $this->csfHiRecordedCount = $csfHiRecorded;
        return $this;
    }

    /**
     * @return int
     */
    public function getPcrPositiveCount()
    {
        return $this->pcrPositiveCount;
    }

    /**
     * @param $pcrPositiveCount
     * @return $this
     */
    public function setPcrPositiveCount($pcrPositiveCount)
    {
        $this->pcrPositiveCount = $pcrPositiveCount;
        return $this;
    }
}
