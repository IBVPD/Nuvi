<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Report\Result\AbstractSiteBasedResult;

/**
 * Class DataQualityResult
 * @package NS\SentinelBundle\Result\RotaVirus
 */
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
     * @return DataQualityResult
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
     * @return DataQualityResult
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
     * @return DataQualityResult
     */
    public function setMissingDischargeOutcomeCount($missingDischargeOutcomeCount)
    {
        $this->missingDischargeOutcomeCount = $missingDischargeOutcomeCount;
        return $this;
    }
}
