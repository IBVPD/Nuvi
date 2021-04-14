<?php

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use NS\SentinelBundle\Report\Result\AbstractSiteBasedResult;

class DischargeClassificationResult extends AbstractSiteBasedResult
{
    /** @var int */
    private $year = 0;

    /** @var array */
    private $dischargeClassifications = [
        DischargeClassification::CONFIRMED  => 0,
        DischargeClassification::DISCARDED  => 0,
        DischargeClassification::INADEQUATE => 0,
        DischargeClassification::UNKNOWN    => 0,
    ];

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function set(int $classification, int $count): void
    {
        $this->dischargeClassifications[$classification] = $count;
    }

    public function get(int $classification): int
    {
        return $this->dischargeClassifications[$classification] ?? 0;
    }

    public function getConfirmed(): int
    {
        return $this->get(DischargeClassification::CONFIRMED);
    }

    public function getDiscarded(): int
    {
        return $this->get(DischargeClassification::DISCARDED);
    }

    public function getInadequate(): int
    {
        return $this->get(DischargeClassification::INADEQUATE);
    }

    public function getUnknown(): int
    {
        return $this->get(DischargeClassification::UNKNOWN);
    }

    public function getConfirmedPercent(): string
    {
        if ($this->totalCases > 0) {
            return (string)($this->getConfirmed() / $this->totalCases * 100);
        }

        return '0';
    }

    public function getDiscardedPercent(): string
    {
        if ($this->totalCases > 0) {
            return (string)($this->getDiscarded() / $this->totalCases * 100);
        }

        return '0';
    }

    public function getInadequatePercent(): string
    {
        if ($this->totalCases > 0) {
            return (string)($this->getInadequate() / $this->totalCases * 100);
        }

        return '0';
    }

    public function getUnknownPercent(): string
    {
        if ($this->totalCases > 0) {
            return (string)($this->getUnknown() / $this->totalCases * 100);
        }

        return '0';
    }
}
