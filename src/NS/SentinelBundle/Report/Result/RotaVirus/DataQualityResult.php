<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Report\Result\AbstractSiteBasedResult;


class DataQualityResult extends AbstractSiteBasedResult
{
    /**
     * @var int
     */
    private $stoolCollectionDateErrorCount = 0;

    /**
     * @var int
     */
    private $missingDischargeDateCount = 0;

    /**
     * @var int
     */
    private $missingDischargeOutcomeCount = 0;

    /**
     * @var int
     */
    private $stoolCollectedCount = 0;

    /**
     * @var int
     */
    private $elisaDoneCount = 0;

    /**
     * @var int
     */
    private $elisaPositiveCount = 0;

    /**
     * @return int
     */
    public function getStoolCollectionDateErrorCount()
    {
        return $this->stoolCollectionDateErrorCount;
    }

    /**
     * @return float|int
     */
    public function getStoolCollectionDateErrorPercent()
    {
        return ($this->getTotalCases()>0) ? $this->stoolCollectionDateErrorCount/$this->getTotalCases()*100:0;
    }

    /**
     * @param int $stoolCollectionDateErrorCount
     *
     * @return DataCompletion
     */
    public function setStoolCollectionDateErrorCount($stoolCollectionDateErrorCount)
    {
        $this->stoolCollectionDateErrorCount = $stoolCollectionDateErrorCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMissingDischargeDateCount()
    {
        return $this->missingDischargeDateCount;
    }

    /**
     * @return float|int
     */
    public function getMissingDischargeDatePercent()
    {
        return ($this->getTotalCases()>0) ? $this->missingDischargeDateCount/$this->getTotalCases()*100:0;
    }

    /**
     * @param int $missingDischargeDateCount
     *
     * @return DataCompletion
     */
    public function setMissingDischargeDateCount($missingDischargeDateCount)
    {
        $this->missingDischargeDateCount = $missingDischargeDateCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMissingDischargeOutcomeCount()
    {
        return $this->missingDischargeOutcomeCount;
    }

    /**
     * @return float|int
     */
    public function getMissingDischargeOutcomePercent()
    {
        return ($this->getTotalCases()>0) ? $this->missingDischargeOutcomeCount/$this->getTotalCases()*100:0;
    }

    /**
     * @param int $missingDischargeOutcomeCount
     *
     * @return DataCompletion
     */
    public function setMissingDischargeOutcomeCount($missingDischargeOutcomeCount)
    {
        $this->missingDischargeOutcomeCount = $missingDischargeOutcomeCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getStoolCollectedCount()
    {
        return $this->stoolCollectedCount;
    }

    /**
     * @return float|int
     */
    public function getStoolCollectedPercent()
    {
        return $this->getTotalCases() > 0 ? $this->stoolCollectedCount/$this->getTotalCases()*100:0;
    }

    /**
     * @param int $stoolCollectedCount
     *
     * @return DataCompletion
     */
    public function setStoolCollectedCount($stoolCollectedCount)
    {
        $this->stoolCollectedCount = $stoolCollectedCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getElisaDoneCount()
    {
        return $this->elisaDoneCount;
    }

    /**
     * @return float|int
     */
    public function getElisaDonePercent()
    {
        return ($this->stoolCollectedCount>0) ? $this->elisaDoneCount/$this->stoolCollectedCount * 100 : 0;
    }

    /**
     * @param int $elisaDoneCount
     *
     * @return DataCompletion
     */
    public function setElisaDoneCount($elisaDoneCount)
    {
        $this->elisaDoneCount = $elisaDoneCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getElisaPositiveCount()
    {
        return $this->elisaPositiveCount;
    }

    /**
     * @return float|int
     */
    public function getElisaPositivePercent()
    {
        return ($this->elisaDoneCount > 0) ? $this->elisaPositiveCount/$this->elisaDoneCount * 100: 0;
    }

    /**
     * @param int $elisaPositiveCount
     *
     * @return DataCompletion
     */
    public function setElisaPositiveCount($elisaPositiveCount)
    {
        $this->elisaPositiveCount = $elisaPositiveCount;
        return $this;
    }
}
