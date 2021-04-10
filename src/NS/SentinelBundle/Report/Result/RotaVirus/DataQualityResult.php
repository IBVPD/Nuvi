<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Report\Result\AbstractSiteBasedResult;

class DataQualityResult extends AbstractSiteBasedResult
{
    /** @var int */
    private $stoolCollectionDateErrorCount = 0;

    /** @var int */
    private $missingDischargeDateCount = 0;

    /** @var int */
    private $missingDischargeOutcomeCount = 0;

    /** @var int */
    private $stoolCollectedCount = 0;

    /** @var int */
    private $elisaDoneCount = 0;

    /** @var int */
    private $elisaPositiveCount = 0;

    public function getStoolCollectionDateErrorCount(): int
    {
        return $this->stoolCollectionDateErrorCount;
    }

    public function getStoolCollectionDateErrorPercent(): string
    {
        return ($this->getTotalCases() > 0) ? (string)($this->stoolCollectionDateErrorCount / $this->getTotalCases() * 100) : '0';
    }

    public function setStoolCollectionDateErrorCount(int $stoolCollectionDateErrorCount): void
    {
        $this->stoolCollectionDateErrorCount = $stoolCollectionDateErrorCount;
    }

    public function getMissingDischargeDateCount(): string
    {
        return $this->missingDischargeDateCount;
    }

    public function getMissingDischargeDatePercent(): string
    {
        return ($this->getTotalCases() > 0) ? (string)($this->missingDischargeDateCount / $this->getTotalCases() * 100) : '0';
    }

    public function setMissingDischargeDateCount(int $missingDischargeDateCount): void
    {
        $this->missingDischargeDateCount = $missingDischargeDateCount;
    }

    public function getMissingDischargeOutcomeCount(): int
    {
        return $this->missingDischargeOutcomeCount;
    }

    public function getMissingDischargeOutcomePercent(): string
    {
        return ($this->getTotalCases() > 0) ? (string)($this->missingDischargeOutcomeCount / $this->getTotalCases() * 100) : '0';
    }

    public function setMissingDischargeOutcomeCount(int $missingDischargeOutcomeCount): void
    {
        $this->missingDischargeOutcomeCount = $missingDischargeOutcomeCount;
    }

    public function getStoolCollectedCount(): int
    {
        return $this->stoolCollectedCount;
    }

    public function getStoolCollectedPercent(): string
    {
        return $this->getTotalCases() > 0 ? (string)($this->stoolCollectedCount / $this->getTotalCases() * 100) : '0';
    }

    public function setStoolCollectedCount($stoolCollectedCount): void
    {
        $this->stoolCollectedCount = $stoolCollectedCount;
    }

    public function getElisaDoneCount(): int
    {
        return $this->elisaDoneCount;
    }

    public function getElisaDonePercent(): string
    {
        return ($this->stoolCollectedCount > 0) ? (string)($this->elisaDoneCount / $this->stoolCollectedCount * 100) : '0';
    }

    public function setElisaDoneCount(int $elisaDoneCount): void
    {
        $this->elisaDoneCount = $elisaDoneCount;
    }

    public function getElisaPositiveCount(): int
    {
        return $this->elisaPositiveCount;
    }

    public function getElisaPositivePercent(): string
    {
        return ($this->elisaDoneCount > 0) ? (string)($this->elisaPositiveCount / $this->elisaDoneCount * 100) : '0';
    }

    public function setElisaPositiveCount(int $elisaPositiveCount): void
    {
        $this->elisaPositiveCount = $elisaPositiveCount;
    }
}
