<?php

namespace NS\SentinelBundle\Report\Result;

use NS\SentinelBundle\Entity\Country;

class DataLinkingResult
{
    /** @var Country|null */
    private $country;

    /** @var int */
    private $totalCases = 0;

    /** @var int */
    private $linked = 0;

    /** @var int */
    private $notLinked = 0;

    /** @var int */
    private $noLab = 0;

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function getTotalCases(): int
    {
        return $this->totalCases;
    }

    public function setTotalCases(int $totalCases): void
    {
        $this->totalCases = $totalCases;
    }

    public function getLinked(): int
    {
        return $this->linked;
    }

    public function getLinkedPercent()
    {
        return ($this->totalCases>0) ? $this->linked / $this->totalCases * 100: 0;
    }

    public function setLinked(int $linked): void
    {
        $this->linked = $linked;
    }

    public function getNotLinked(): int
    {
        return $this->notLinked;
    }

    public function getNotLinkedPercent()
    {
        return ($this->totalCases>0) ? $this->notLinked / $this->totalCases * 100: 0;
    }

    public function setNotLinked(int $notLinked): void
    {
        $this->notLinked = $notLinked;
    }

    public function getNoLab(): int
    {
        return $this->noLab;
    }

    public function setNoLab(int $noLab): void
    {
        $this->noLab = $noLab;
    }
}
