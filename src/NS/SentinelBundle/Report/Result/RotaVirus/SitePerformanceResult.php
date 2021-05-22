<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Report\Result\AbstractSitePerformanceResult;

class SitePerformanceResult extends AbstractSitePerformanceResult
{
    public function getMinimumNumberOfCases()
    {
        $total = $this->getTotalCases();
        return $this->getState($total, 50, 40);
    }

    public const MIN_CASES_STR = '≥ 50 cases';
    public function getMinimumNumberOfCasesString()
    {
        return self::MIN_CASES_STR;
    }

    public function hasMinimumSpecimenCollected()
    {
        $specimens = $this->getSpecimenCollection();
        return $this->getState($specimens, 50, 40);
    }

    public const MIN_SPECIMEN_STR = '≥ 50 samples';
    public function getMinimumSpecimenCollectedString()
    {
        return self::MIN_SPECIMEN_STR;
    }

    public function hasMinimumLabConfirmed()
    {
        $percent = $this->getLabConfirmedPercent();
        return $this->getState($percent);
    }

    public const MIN_SPECIMEN__TESTED_STR = '≥ 90% with specimen';

    public function getMinimumLabConfirmedString()
    {
        return self::MIN_SPECIMEN__TESTED_STR;
    }
}
