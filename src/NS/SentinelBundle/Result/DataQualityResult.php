<?php

namespace NS\SentinelBundle\Result;

/**
 * Class DataQualityResult
 * @package NS\SentinelBundle\Result
 */
class DataQualityResult
{
    /**
     * @var
     */
    private $site;

    /**
     * @var int
     */
    private $totalCases = 0;

    /**
     * @var int
     */
    private $dateOfBirthErrorCount = 0;

    /**
     * @var int
     */
    private $missingAdmissionDiagnosisCount = 0;

    /**
     * @var int
     */
    private $missingDischargeOutcomeCount = 0;

    /**
     * @var int
     */
    private $missingDischargeDiagnosisCount = 0;

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     * @return DataQualityResult
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCases()
    {
        return $this->totalCases;
    }

    /**
     * @param int $totalCases
     * @return DataQualityResult
     */
    public function setTotalCases($totalCases)
    {
        $this->totalCases = $totalCases;
        return $this;
    }

    /**
     * @return int
     */
    public function getDateOfBirthErrorCount()
    {
        return $this->dateOfBirthErrorCount;
    }

    /**
     * @return float|int
     */
    public function getDateOfBirthErrorPercent()
    {
        return ($this->totalCases > 0) ? $this->dateOfBirthErrorCount / $this->totalCases * 100 : 0;
    }

    /**
     * @param int $dateOfBirthErrorCount
     * @return DataQualityResult
     */
    public function setDateOfBirthErrorCount($dateOfBirthErrorCount)
    {
        $this->dateOfBirthErrorCount = $dateOfBirthErrorCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMissingAdmissionDiagnosisCount()
    {
        return $this->missingAdmissionDiagnosisCount;
    }

    /**
     * @return float|int
     */
    public function getMissingAdmissionDiagnosisPercent()
    {
        return ($this->totalCases > 0) ? $this->missingAdmissionDiagnosisCount / $this->totalCases * 100 : 0;
    }

    /**
     * @param int $missingAdmissionDiagnosisCount
     * @return DataQualityResult
     */
    public function setMissingAdmissionDiagnosisCount($missingAdmissionDiagnosisCount)
    {
        $this->missingAdmissionDiagnosisCount = $missingAdmissionDiagnosisCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMissingDischargeOutcomeCount()
    {
        return $this->missingDischargeOutcomeCount;
    }

    /**
     * @return float|int
     */
    public function getMissingDischargeOutcomePercent()
    {
        return ($this->totalCases > 0) ? $this->missingDischargeOutcomeCount / $this->totalCases * 100 : 0;
    }

    /**
     * @param int $missingDischargeOutcomeCount
     * @return DataQualityResult
     */
    public function setMissingDischargeOutcomeCount($missingDischargeOutcomeCount)
    {
        $this->missingDischargeOutcomeCount = $missingDischargeOutcomeCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getMissingDischargeDiagnosisCount()
    {
        return $this->missingDischargeDiagnosisCount;
    }

    /**
     * @return float|int
     */
    public function getMissingDischargeDiagnosisPercent()
    {
        return ($this->totalCases > 0) ? $this->missingAdmissionDiagnosisCount / $this->totalCases * 100 : 0;
    }

    /**
     * @param int $missingDischargeDiagnosisCount
     * @return DataQualityResult
     */
    public function setMissingDischargeDiagnosisCount($missingDischargeDiagnosisCount)
    {
        $this->missingDischargeDiagnosisCount = $missingDischargeDiagnosisCount;
        return $this;
    }
}
