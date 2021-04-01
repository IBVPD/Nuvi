<?php

namespace NS\SentinelBundle\Report\Result\IBD;

use NS\SentinelBundle\Report\Result\AbstractSiteBasedResult;

class DataCompletionResult extends AbstractSiteBasedResult
{
    /** @var int */
    private $suspected = 0;

    /** @var int */
    private $suspectedXray = 0;

    /** @var int */
    private $probable = 0;

    /** @var int */
    private $probableWithBlood = 0;

    /** @var int */
    private $outcomeAtDischarge = 0;

    /** @var int */
    private $classificationAtDischarge = 0;

    public function getSuspected(): int
    {
        return $this->suspected;
    }

    public function setSuspected(int $suspected): void
    {
        $this->suspected = $suspected;
    }

    public function getSuspectedXray(): int
    {
        return $this->suspectedXray;
    }

    public function getSuspectedXrayPercent(): string
    {
        return ($this->getTotalCases() > 0) ? (string)($this->suspectedXray / $this->getTotalCases() * 100) : '0';
    }

    public function setSuspectedXray(int $suspectedXray): void
    {
        $this->suspectedXray = $suspectedXray;
    }

    public function getProbable(): int
    {
        return $this->probable;
    }

    public function getProbablePercent(): string
    {
        return ($this->getTotalCases() > 0) ? (string)($this->probable / $this->getTotalCases() * 100) : '0';
    }

    public function setProbable(int $probable): void
    {
        $this->probable = $probable;
    }

    public function getProbableWithBlood(): int
    {
        return $this->probableWithBlood;
    }

    public function getProbableWithBloodPercent(): string
    {
        return ($this->getTotalCases() > 0) ? (string)($this->probableWithBlood / $this->getTotalCases() * 100) : '0';
    }

    public function setProbableWithBlood(int $probableWithBlood): void
    {
        $this->probableWithBlood = $probableWithBlood;
    }

    public function getOutcomeAtDischarge(): int
    {
        return $this->outcomeAtDischarge;
    }

    public function getOutcomeAtDischargePercent(): string
    {
        return ($this->getTotalCases() > 0) ? (string)($this->outcomeAtDischarge / $this->getTotalCases() * 100) : '0';
    }

    public function setOutcomeAtDischarge(int $outcomeAtDischarge): void
    {
        $this->outcomeAtDischarge = $outcomeAtDischarge;
    }

    public function getClassificationAtDischarge(): int
    {
        return $this->classificationAtDischarge;
    }

    public function getClassificationAtDischargePercent(): string
    {
        return ($this->getTotalCases() > 0) ? (string)($this->classificationAtDischarge / $this->getTotalCases() * 100) : '0';
    }

    public function setClassificationAtDischarge(int $classificationAtDischarge): void
    {
        $this->classificationAtDischarge = $classificationAtDischarge;
    }
}
