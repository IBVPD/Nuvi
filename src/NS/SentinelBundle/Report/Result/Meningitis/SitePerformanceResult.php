<?php

namespace NS\SentinelBundle\Report\Result\Meningitis;

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
        return $this->getState($total, 50, 40);
    }

    /**
     * @return string
     */
    public function hasMinimumSpecimenCollected()
    {
        $percent = $this->getSpecimenCollectionPercent();
        return $this->getState($percent, 90, 80);
    }

    public const
        MIN_CASES_STR = '≥ 50 cases',
        TIER1_MIN_CASES_STR = '≥ 100 cases',
        TIER2_MIN_CASES_STR = '≥ 500 cases';

    /**
     * @return string
     */
    public function getMinimumNumberOfCasesString()
    {
        return self::MIN_CASES_STR;
    }

    public const
        MIN_SPECIMEN_STR = '≥ 90% with specimen',
        TIER1_MIN_SPECIMEN_STR = '≥ 90% with specimen',
        TIER2_MIN_SPECIMEN_STR = '≥ 75% with specimen';

    /**
     * @return string
     */
    public function getMinimumSpecimenCollectedString()
    {
        return self::MIN_SPECIMEN_STR;
    }

    public function getMinimumLabConfirmedString()
    {
        return '≥ 10%';
    }

    /**
     * @return string
     */
    public function hasMinimumLabConfirmed()
    {
        $percent = $this->getLabConfirmedPercent();
        return $this->getState($percent, 10, 8);
    }

    /**
     * @inheritDoc
     */
    public static function getTranslationMessages()
    {
        return [
            new Message(self::TIER1_MIN_CASES_STR),
            new Message(self::TIER1_MIN_SPECIMEN_STR),
            new Message(self::CONSISTENT_REPORTING_STR),
            new Message(self::TIER2_MIN_CASES_STR),
            new Message(self::TIER2_MIN_SPECIMEN_STR),
        ];
    }
}
