<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Report\Result\AbstractSitePerformanceResult;

class SitePerformanceResult extends AbstractSitePerformanceResult
{
    //    /**
//     * @return string
//     */
//    public function hasMinimumNumberOfCases()
//    {
//        return $this->getTotalCases() >= 100 ? 'Yes':'No';
//    }
//
//    /**
//     * @return string
//     */
//    public function hasMinimumSpecimenCollected()
//    {
//        return ($this->getSpecimenCollectionPercent() >= 90 ? 'Yes':'No');
//    }
//
//    /**
//     * @return string
//     */
//    public function hasMinimumLabConfirmed()
//    {
//        return ($this->getLabConfirmedPercent() >= 90 ? 'Yes':'No');
//    }
//O
    public function getMinimumNumberOfCases()
    {
        $total = $this->getTotalCases();
        return $this->getState($total, 100, 80);
    }

    public const MIN_CASES_STR = '≥ 100 cases';
    public function getMinimumNumberOfCasesString()
    {
        return self::MIN_CASES_STR;
    }

    public function hasMinimumSpecimenCollected()
    {
        $percent = $this->getSpecimenCollectionPercent();
        return $this->getState($percent);
    }

    public const MIN_SPECIMEN_STR = '≥ 90% with specimen';
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
