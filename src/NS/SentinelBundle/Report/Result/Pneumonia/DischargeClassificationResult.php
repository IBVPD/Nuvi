<?php

namespace NS\SentinelBundle\Report\Result\Pneumonia;

use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Report\Result\AbstractSiteBasedResult;

class DischargeClassificationResult extends AbstractSiteBasedResult
{
    /** @var int */
    private $year = 0;

    /** @var array */
    private $dischargeClassifications = [
        DischargeClassification::CONFIRMED_HI    => 0,
        DischargeClassification::CONFIRMED_SPN   => 0,
        DischargeClassification::CONFIRMED_NM    => 0,
        DischargeClassification::CONFIRMED_OTHER => 0,
        DischargeClassification::SEPSIS          => 0,
        DischargeClassification::PROBABLE        => 0,
        DischargeClassification::SUSPECT         => 0,
        DischargeClassification::INCOMPLETE      => 0,
        DischargeClassification::DISCARDED       => 0,
        DischargeClassification::UNKNOWN         => 0,
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

    public function getHi(): int
    {
        return $this->get(DischargeClassification::CONFIRMED_HI);
    }

    public function getSpn(): int
    {
        return $this->get(DischargeClassification::CONFIRMED_SPN);
    }

    public function getNm(): int
    {
        return $this->get(DischargeClassification::CONFIRMED_NM);
    }

    public function getOther(): int
    {
        return $this->get(DischargeClassification::CONFIRMED_OTHER);
    }

    public function getSepsis(): int
    {
        return $this->get(DischargeClassification::SEPSIS);
    }

    public function getProbable(): int
    {
        return $this->get(DischargeClassification::PROBABLE);
    }

    public function getSuspect(): int
    {
        return $this->get(DischargeClassification::SUSPECT);
    }

    public function getIncomplete(): int
    {
        return $this->get(DischargeClassification::INCOMPLETE);
    }

    public function getDiscarded(): int
    {
        return $this->get(DischargeClassification::DISCARDED);
    }

    public function getUnknown(): int
    {
        return $this->get(DischargeClassification::UNKNOWN);
    }

    public function getHiPercent(): string
    {
        return $this->getPercent(DischargeClassification::CONFIRMED_HI);
    }

    public function getSpnPercent(): string
    {
        return $this->getPercent(DischargeClassification::CONFIRMED_SPN);
    }

    public function getNmPercent(): string
    {
        return $this->getPercent(DischargeClassification::CONFIRMED_NM);
    }

    public function getOtherPercent(): string
    {
        return $this->getPercent(DischargeClassification::CONFIRMED_OTHER);
    }

    public function getSepsisPercent(): string
    {
        return $this->getPercent(DischargeClassification::SEPSIS);
    }

    public function getProbablePercent(): string
    {
        return $this->getPercent(DischargeClassification::PROBABLE);
    }

    public function getSuspectPercent(): string
    {
        return $this->getPercent(DischargeClassification::SUSPECT);
    }

    public function getIncompletePercent(): string
    {
        return $this->getPercent(DischargeClassification::INCOMPLETE);
    }

    public function getDiscardedPercent(): string
    {
        return $this->getPercent(DischargeClassification::DISCARDED);
    }

    public function getUnknownPercent(): string
    {
        return $this->getPercent(DischargeClassification::UNKNOWN);
    }

    private function getPercent(int $key): string
    {
        if ($this->totalCases > 0 && isset($this->dischargeClassifications[$key])) {
            return (string)($this->dischargeClassifications[$key] / $this->totalCases * 100);
        }

        return '0';
    }
}
