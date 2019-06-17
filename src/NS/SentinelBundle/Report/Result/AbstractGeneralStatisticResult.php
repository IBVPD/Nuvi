<?php

namespace NS\SentinelBundle\Report\Result;

use NS\SentinelBundle\Form\Types\Gender;

abstract class AbstractGeneralStatisticResult
{
    /** @var array */
    protected $genderDistribution = [];

    /** @var array */
    protected $ageInMonthDistribution = [];

    /** @var array */
    protected $locationDistribution = [];

    /** @var array */
    protected $dischargeOutcomeDistribution = [];

    /** @var array */
    protected $monthlyDistribution = [-1=>0];

    public function getGenderDistribution(): array
    {
        return $this->genderDistribution;
    }

    public function setGenderDistribution(array $genderDistribution): void
    {
        $gender = new Gender();
        foreach ($genderDistribution as $result) {
            $gender->setValue($result['gender']);
            $this->genderDistribution[$gender->__toString()] = $result['caseCount'];
        }
    }

    public function getAgeInMonthDistribution(): array
    {
        return $this->ageInMonthDistribution;
    }

    public function setAgeInMonthDistribution(array $ageInMonthDistribution): void
    {
        foreach($ageInMonthDistribution as $result) {
            $this->ageInMonthDistribution[$result['age_months']] = $result['caseCount'];
        }
    }

    public function getLocationDistribution(): array
    {
        return $this->locationDistribution;
    }

    public function setLocationDistribution(array $locationDistribution): void
    {
        foreach($locationDistribution as $result) {
            $this->locationDistribution[$result['state']][$result['district']] = $result['caseCount'];
        }
    }

    public function getDischargeOutcomeDistribution(): array
    {
        return $this->dischargeOutcomeDistribution;
    }

    abstract public function setDischargeOutcomeDistribution(array $dischargeOutcomeDistribution): void;

    public function getMonthlyDistribution(): array
    {
        return $this->monthlyDistribution;
    }

    public function setMonthlyDistribution(array $monthlyDistribution): void
    {
        foreach($monthlyDistribution as $result) {

            if(empty($result['theMonth'])) {
                $this->monthlyDistribution[-1] += $result['caseCount'];
            } else {
                $this->monthlyDistribution[$result['theMonth']] = $result['caseCount'];
            }
        }
    }
}
