<?php

namespace NS\SentinelBundle\Report\Result\IBD;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\SentinelBundle\Report\Result\AbstractSitePerformanceResult;

class SitePerformanceResult extends AbstractSitePerformanceResult implements TranslationContainerInterface
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
        return '≥ 80%';
    }

    /**
     * @return string
     */
    public function hasMinimumLabConfirmed()
    {
        $percent = $this->getLabConfirmedPercent();

        if($percent>=80) {
            return self::GOOD;
        }

        if($percent>=50) {
            return self::WARN;
        }
    }

    /**
     * @inheritDoc
     */
    static function getTranslationMessages()
    {
        return array(
            new Message(self::TIER1_MIN_CASES_STR),
            new Message(self::TIER1_MIN_SPECIMEN_STR),
            new Message(self::CONSISTENT_REPORTING_STR),
            new Message(self::TIER2_MIN_CASES_STR),
            new Message(self::TIER2_MIN_SPECIMEN_STR),
        );
    }
}
