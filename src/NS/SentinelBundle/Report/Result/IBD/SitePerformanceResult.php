<?php

namespace NS\SentinelBundle\Report\Result\IBD;

use NS\SentinelBundle\Report\Result\AbstractSitePerformanceResult;

class SitePerformanceResult extends AbstractSitePerformanceResult
{
    /**
     * @return bool
     */
    public function getMinimumNumberOfCases()
    {
        $total = $this->getTotalCases();
        switch ($this->getSite()->getIbdTier()) {
            case 1:
                return $this->getState($total, 100, 80);
            case 3:
            case 2:
                return $this->getState($total, 500, 400);
            default:
                return self::BAD;
        }
    }

    /**
     * @return string
     */
    public function hasMinimumSpecimenCollected()
    {
        $percent = $this->getSpecimenCollectionPercent();
        switch ($this->getSite()->getIbdTier()) {
            case 1:
                return $this->getState($percent);
            case 2:
                return $this->getState($percent, 75, 70);
        }

        return self::BAD;
    }

    const TIER1_MIN_CASES_STR = '≥ 100 cases';
    const TIER2_MIN_CASES_STR = '≥ 500 cases';

    /**
     * @return string
     */
    public function getMinimumNumberOfCasesString()
    {
        switch ($this->getSite()->getIbdTier()) {
            case 1:
                return self::TIER1_MIN_CASES_STR;
            case 2:
                return self::TIER2_MIN_CASES_STR;
        }

        return 'Unknown';
    }

    const TIER1_MIN_SPECIMEN_STR = '≥ 90% with specimen';
    const TIER2_MIN_SPECIMEN_STR = '≥ 75% with specimen';

    /**
     * @return string
     */
    public function getMinimumSpecimenCollectedString()
    {
        switch ($this->getSite()->getIbdTier()) {
            case 1:
                return self::TIER1_MIN_SPECIMEN_STR;
            case 2:
                return self::TIER2_MIN_SPECIMEN_STR;
        }
    }

    /**
     *
     */
    public function getMinimumLabConfirmedString()
    {
    }

    /**
     * @return string
     */
    public function hasMinimumLabConfirmed()
    {
        return 'N/A';
    }
}
