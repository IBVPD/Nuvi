<?php

namespace NS\SentinelBundle\Report\Result;

abstract class AbstractSitePerformanceResult extends AbstractSiteBasedResult
{
    const GOOD = 1;
    const WARN = 2;
    const BAD = 3;

    /**
     * @var int
     */
    private $consistentReporting = array();

    /**
     * @var integer
     */
    private $consistentReportingCount;

    /**
     * @var int
     */
    private $specimenCollection = 0;

    /**
     * @var int
     */
    private $specimenCollectionPercent;

    /**
     * @var int
     */
    private $labConfirmed = 0;

    /**
     * @var int
     */
    private $labConfirmedPercent;


    abstract public function getMinimumNumberOfCases();

    abstract public function hasMinimumSpecimenCollected();

    abstract public function hasMinimumLabConfirmed();

    abstract public function getMinimumNumberOfCasesString();

    abstract public function getMinimumSpecimenCollectedString();

    abstract public function getMinimumLabConfirmedString();

    const CONSISTENT_REPORTING_STR = '12 months or zero reporting';

    public function getConsistentReportingString()
    {
        return self::CONSISTENT_REPORTING_STR;
    }

    /**
     * @return int
     *
     * Green >=12
     * Yellow >=10
     * Red <10"
     */
    public function getConsistentReporting()
    {
        if (!$this->consistentReportingCount) {
            $this->consistentReportingCount = count($this->consistentReporting);
        }

        if ($this->consistentReportingCount == 12) {
            return self::GOOD;
        } elseif ($this->consistentReportingCount >= 10) {
            return self::WARN;
        }

        return self::BAD;
    }

    public function getConsistentReportingPercent()
    {
        return ($this->getConsistentReportingCount() / 12) * 100;
    }

    /**
     * @return int
     */
    public function getConsistentReportingCount()
    {
        return $this->consistentReportingCount;
    }

    /**
     * @param int $consistentReporting
     */
    public function addConsistentReporting($consistentReporting)
    {
        $this->consistentReporting[$consistentReporting['theMonth']] = $consistentReporting;
        $this->consistentReportingCount = count($this->consistentReporting);
    }

    /**
     * @return int
     */
    public function getSpecimenCollection()
    {
        return $this->specimenCollection;
    }

    /**
     * @param int $specimenCollection
     */
    public function setSpecimenCollection($specimenCollection)
    {
        $this->specimenCollection = $specimenCollection;
    }

    /**
     * @return float|int|null
     */
    public function getSpecimenCollectionPercent()
    {
        if ($this->specimenCollectionPercent === null && $this->getTotalCases() > 0) {
            $this->specimenCollectionPercent = ($this->getSpecimenCollection() / $this->getTotalCases()) * 100;
        }

        return $this->specimenCollectionPercent;
    }

    /**
     * @return int
     */
    public function getLabConfirmed()
    {
        return $this->labConfirmed;
    }

    /**
     * @param int $labConfirmed
     */
    public function setLabConfirmed($labConfirmed)
    {
        $this->labConfirmed = $labConfirmed;
    }

    /**
     * @return float|int|null
     */
    public function getLabConfirmedPercent()
    {
        if ($this->labConfirmedPercent === null && $this->getTotalCases() > 0) {
            $this->labConfirmedPercent = ($this->getLabConfirmed() / $this->getTotalCases()) * 100;
        }

        return $this->labConfirmedPercent;
    }

    public function getState($percent, $firstPercent = 90, $secondPercent = 80)
    {
        if ($percent >= $firstPercent) {
            return self::GOOD;
        } elseif ($percent >= $secondPercent) {
            return self::WARN;
        }

        return self::BAD;
    }
}
