<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Report\Result\AbstractSiteBasedResult;


class DataCompletion extends AbstractSiteBasedResult
{
    /** @var int */
    private $stoolCollectedCount = 0;

    /** @var int */
    private $elisaDoneCount = 0;

    /** @var int */
    private $outcomeCount = 0;

    /** @var int */
    private $classificationCount = 0;

    public function getStoolCollectedCount(): int
    {
        return $this->stoolCollectedCount;
    }

    public function getStoolCollectedPercent()
    {
        return $this->getTotalCases() > 0 ? $this->stoolCollectedCount/$this->getTotalCases()*100:0;
    }

    /**
     * @return int
     */
    public function getOutcomeCount(): int
    {
        return $this->outcomeCount;
    }

    public function getOutcomePercent()
    {
        return $this->getTotalCases() > 0 ? $this->outcomeCount/$this->getTotalCases()*100:0;
    }

    /**
     * @param int $outcomeCount
     */
    public function setOutcomeCount(int $outcomeCount): void
    {
        $this->outcomeCount = $outcomeCount;
    }

    /**
     * @return int
     */
    public function getClassificationCount(): int
    {
        return $this->classificationCount;
    }

    public function getClassificationPercent()
    {
        return $this->getTotalCases() > 0 ? $this->classificationCount/$this->getTotalCases()*100:0;
    }

    /**
     * @param int $classificationCount
     */
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
