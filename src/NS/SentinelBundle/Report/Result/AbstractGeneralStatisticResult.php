<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 19/04/16
 * Time: 3:56 PM
 */

namespace NS\SentinelBundle\Report\Result;

use NS\SentinelBundle\Form\Types\Gender;

abstract class AbstractGeneralStatisticResult
{
    /**
     * @var array
     */
    protected $genderDistribution = [];

    /**
     * @var array
     */
    protected $ageInMonthDistribution = [];

    /**
     * @var array
     */
    protected $locationDistribution = [];

    /**
     * @var array
     */
    protected $dischargeOutcomeDistribution = [];

    /**
     * @var array
     */
    protected $monthlyDistribution = [-1=>0];

    /**
     * @return array
     */
    public function getGenderDistribution()
    {
        return $this->genderDistribution;
    }

    /**
     * @param array $genderDistribution
     * @return GeneralStatisticResult
     */
    public function setGenderDistribution($genderDistribution)
    {
        $gender = new Gender();
        foreach ($genderDistribution as $result) {
            $gender->setValue($result['gender']);
            $this->genderDistribution[$gender->__toString()] = $result['caseCount'];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAgeInMonthDistribution()
    {
        return $this->ageInMonthDistribution;
    }

    /**
     * @param array $ageInMonthDistribution
     * @return GeneralStatisticResult
     */
    public function setAgeInMonthDistribution($ageInMonthDistribution)
    {
        foreach($ageInMonthDistribution as $result) {
            $this->ageInMonthDistribution[$result['age_months']] = $result['caseCount'];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getLocationDistribution()
    {
        return $this->locationDistribution;
    }

    /**
     * @param array $locationDistribution
     * @return GeneralStatisticResult
     */
    public function setLocationDistribution($locationDistribution)
    {
        foreach($locationDistribution as $result) {
            $this->locationDistribution[$result['state']][$result['district']] = $result['caseCount'];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getDischargeOutcomeDistribution()
    {
        return $this->dischargeOutcomeDistribution;
    }

    /**
     * @param array $dischargeOutcomeDistribution
     * @return GeneralStatisticResult
     */
    abstract public function setDischargeOutcomeDistribution($dischargeOutcomeDistribution);

    /**
     * @return array
     */
    public function getMonthlyDistribution()
    {
        return $this->monthlyDistribution;
    }

    /**
     * @param array $monthlyDistribution
     * @return GeneralStatisticResult
     */
    public function setMonthlyDistribution($monthlyDistribution)
    {
        foreach($monthlyDistribution as $result) {

            if(empty($result['theMonth'])) {
                $this->monthlyDistribution[-1] += $result['caseCount'];
            } else {
                $this->monthlyDistribution[$result['theMonth']] = $result['caseCount'];
            }
        }

        return $this;
    }
}
