<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Report\Result\AbstractSiteBasedResult;


class DataCompletionResult extends AbstractSiteBasedResult
{
    /** @var int */
    private $year = 0;

    /** @var int */
    private $stoolCollectedCount = 0;

    /** @var int */
    private $elisaDoneCount = 0;

    /** @var int */
    private $outcomeCount = 0;

    /** @var int */
    private $classificationCount = 0;

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getStoolCollectedCount(): int
    {
        return $this->stoolCollectedCount;
    }

    public function getStoolCollectedPercent()
    {
        return $this->getTotalCases() > 0 ? $this->stoolCollectedCount/$this->getTotalCases()*100:0;
    }

    public function getOutcomeCount(): int
    {
        return $this->outcomeCount;
    }

    public function getOutcomePercent()
    {
        return $this->getTotalCases() > 0 ? $this->outcomeCount/$this->getTotalCases()*100:0;
    }

    public function setOutcomeCount(int $outcomeCount): void
    {
        $this->outcomeCount = $outcomeCount;
    }

    public function getClassificationCount(): int
    {
        return $this->classificationCount;
    }

    public function getClassificationPercent()
    {
        return $this->getTotalCases() > 0 ? $this->classificationCount/$this->getTotalCases()*100:0;
    }

    public function setClassificationCount(int $classificationCount): void
    {
        $this->classificationCount = $classificationCount;
    }

    public function setStoolCollectedCount($stoolCollectedCount): void
    {
        $this->stoolCollectedCount = $stoolCollectedCount;
    }

    public function getElisaDoneCount(): int
    {
        return $this->elisaDoneCount;
    }

    public function getElisaDonePercent()
    {
        return ($this->stoolCollectedCount>0) ? $this->elisaDoneCount/$this->stoolCollectedCount * 100 : 0;
    }

    public function setElisaDoneCount($elisaDoneCount): void
    {
        $this->elisaDoneCount = $elisaDoneCount;
    }
}
