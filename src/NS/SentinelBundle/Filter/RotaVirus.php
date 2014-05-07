<?php

namespace NS\SentinelBundle\Filter;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationType;
use NS\SentinelBundle\Form\Types\ElisaResult;

/**
 * Description of RotaVirus
 * @author gnat
 */
class RotaVirus
{
    /**
     * @var string id
     */
    private $id;

//i. Sentinel Site Information
    /**
     * @var Region $region
     */
    private $region;

    /**
     * @var Country $country
     */
    private $country;

    /**
     * @var Site $site
     */
    private $site;

    //ISO3_code
    //site_code

    /**
     * @var siteLab
     */
    private $siteLab;

//ii. Case-based Demographic Data
    /**
     * case_ID
     * @var string $caseId
     */
    private $caseId;

    /**
     * gender
     * @var Gender $gender
     */
    private $gender;

    /**
     * birthdate
     * @var DateTime $dob
     */
    private $dob;

    /**
     * age_months
     * @var integer $age
     */
    private $age;

    /**
     * case_district
     * @var string $district
     */
    private $district;

//iii. Case-based Clinical Data
    /**
     * adm_date
     * @var DateTime $admissionDate
     */
    private $admissionDate;

    /**
     * symp_diarrhoea
     * @var TripleChoice $symptomDiarrhea
     */
    private $symptomDiarrhea;

    /**
     * symp_dia_onset_date
     * @var DateTime $symptomDiarrheaOnset
     */
    private $symptomDiarrheaOnset;

    /**
     * symp_dia_episodes
     * @var integer $symptomDiarrheaEpisodes
     */
    private $symptomDiarrheaEpisodes;

    /**
     * symp_dia_duration
     * @var integer $symptomDiarrheaDuration
     */
    private $symptomDiarrheaDuration;

    /**
     * symp_vomit
     * @var TripleChoice $symptomVomit
     */
    private $symptomDiarrheaVomit;

    /**
     * symp_vomit_episodes
     * @var integer $symptomVomitEpisodes
     */
    private $symptomVomitEpisodes;

    /**
     * symp_vomit_duration
     * @var integer $symptomVomitDuration
     */
    private $symptomVomitDuration;

    /**
     * symp_dehydration
     * @var Dehydration $symptomDehydration
     */
    private $symptomDehydration;


// Treatment
    /**
     * rehydration
     * @var TripleChoice $rehydration
     */
    private $rehydration;

    /**
     * rehydration_type
     * @var Rehydration $rehydrationType
     */
    private $rehydrationType;

    /**
     * rehydration_type_other
     * @var string $rehydrationOther
     */
    private $rehydrationOther;

//iv. Case-based Vaccination History
    /**
     * @var RotavirusVaccinationReceived $vaccinationReceived
     * RV_received
     */
    private $vaccinationReceived;

    /**
     * RV_type
     * @var RotavirusVaccinationType $vaccinationType
     */
    private $vaccinationType;

    /**
     * RV_doses
     * @var Doses $doses
     */
    private $doses;

    /**
     * RV_dose1_date
     * @var DateTime $firstVaccinationDose
     */
    private $firstVaccinationDose;

    /**
     * RV_dose2_date
     * @var DateTime $secondVaccinationDose
     */
    private $secondVaccinationDose;

    /**
     * RV_dose3_date
     * @var DateTime $thirdVaccinationDose
     */
    private $thirdVaccinationDose;

//v. Case-based Specimen Collection Data
    /**
     * stool_collected
     * @var TripleChoice $stoolCollected
     */
    private $stoolCollected;

    /**
     * stool_ID
     * @var string $stoolId
     */
    private $stoolId;

    /**
     * stool_collect_date
     * @var DateTime $stoolCollectionDate
     */
    private $stoolCollectionDate;


//vii. Case-based Outcome Data
    /**
     * disch_outcome
     * @var DischargeOutcome $dischargeOutcome
     */
    private $dischargeOutcome;

    /**
     * @var DateTime $dischargeDate
     */
    private $dischargeDate;

    /**
     * @var string $dischargeClassOther
     */
    private $dischargeClassOther;

    public function getRegion()
    {
        return $this->region;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getCaseId()
    {
        return $this->caseId;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getDistrict()
    {
        return $this->district;
    }

    public function getAdmissionDate()
    {
        return $this->admissionDate;
    }

    public function getSymptomDiarrhea()
    {
        return $this->symptomDiarrhea;
    }

    public function getSymptomDiarrheaOnset()
    {
        return $this->symptomDiarrheaOnset;
    }

    public function getSymptomDiarrheaEpisodes()
    {
        return $this->symptomDiarrheaEpisodes;
    }

    public function getSymptomDiarrheaDuration()
    {
        return $this->symptomDiarrheaDuration;
    }

    public function getSymptomDiarrheaVomit()
    {
        return $this->symptomDiarrheaVomit;
    }

    public function getSymptomVomitEpisodes()
    {
        return $this->symptomVomitEpisodes;
    }

    public function getSymptomVomitDuration()
    {
        return $this->symptomVomitDuration;
    }

    public function getSymptomDehydration()
    {
        return $this->symptomDehydration;
    }

    public function getRehydration()
    {
        return $this->rehydration;
    }

    public function getRehydrationType()
    {
        return $this->rehydrationType;
    }

    public function getRehydrationOther()
    {
        return $this->rehydrationOther;
    }

    public function getVaccinationReceived()
    {
        return $this->vaccinationReceived;
    }

    public function getVaccinationType()
    {
        return $this->vaccinationType;
    }

    public function getDoses()
    {
        return $this->doses;
    }

    public function getFirstVaccinationDose()
    {
        return $this->firstVaccinationDose;
    }

    public function getSecondVaccinationDose()
    {
        return $this->secondVaccinationDose;
    }

    public function getThirdVaccinationDose()
    {
        return $this->thirdVaccinationDose;
    }

    public function getStoolCollected()
    {
        return $this->stoolCollected;
    }

    public function getStoolId()
    {
        return $this->stoolId;
    }

    public function getStoolCollectionDate()
    {
        return $this->stoolCollectionDate;
    }

    public function getDischargeOutcome()
    {
        return $this->dischargeOutcome;
    }

    public function getDischargeDate()
    {
        return $this->dischargeDate;
    }

    public function getDischargeClassOther()
    {
        return $this->dischargeClassOther;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    public function setCountry( $country)
    {
        $this->country = $country;
        return $this;
    }

    public function setSite( $site)
    {
        $this->site = $site;
        return $this;
    }

    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;
        return $this;
    }

    public function setGender( $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public function setDob( $dob)
    {
        $this->dob = $dob;
        return $this;
    }

    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    public function setAdmissionDate($admissionDate)
    {
        $this->admissionDate = $admissionDate;
        return $this;
    }

    public function setSymptomDiarrhea($symptomDiarrhea)
    {
        $this->symptomDiarrhea = $symptomDiarrhea;
        return $this;
    }

    public function setSymptomDiarrheaOnset($symptomDiarrheaOnset)
    {
        $this->symptomDiarrheaOnset = $symptomDiarrheaOnset;
        return $this;
    }

    public function setSymptomDiarrheaEpisodes($symptomDiarrheaEpisodes)
    {
        $this->symptomDiarrheaEpisodes = $symptomDiarrheaEpisodes;
        return $this;
    }

    public function setSymptomDiarrheaDuration($symptomDiarrheaDuration)
    {
        $this->symptomDiarrheaDuration = $symptomDiarrheaDuration;
        return $this;
    }

    public function setSymptomDiarrheaVomit($symptomDiarrheaVomit)
    {
        $this->symptomDiarrheaVomit = $symptomDiarrheaVomit;
        return $this;
    }

    public function setSymptomVomitEpisodes($symptomVomitEpisodes)
    {
        $this->symptomVomitEpisodes = $symptomVomitEpisodes;
        return $this;
    }

    public function setSymptomVomitDuration($symptomVomitDuration)
    {
        $this->symptomVomitDuration = $symptomVomitDuration;
        return $this;
    }

    public function setSymptomDehydration($symptomDehydration)
    {
        $this->symptomDehydration = $symptomDehydration;
        return $this;
    }

    public function setRehydration($rehydration)
    {
        $this->rehydration = $rehydration;
        return $this;
    }

    public function setRehydrationType($rehydrationType)
    {
        $this->rehydrationType = $rehydrationType;
        return $this;
    }

    public function setRehydrationOther($rehydrationOther)
    {
        $this->rehydrationOther = $rehydrationOther;
        return $this;
    }

    public function setVaccinationReceived($vaccinationReceived)
    {
        $this->vaccinationReceived = $vaccinationReceived;
        return $this;
    }

    public function setVaccinationType($vaccinationType)
    {
        $this->vaccinationType = $vaccinationType;
        return $this;
    }

    public function setDoses($doses)
    {
        $this->doses = $doses;
        return $this;
    }

    public function setFirstVaccinationDose($firstVaccinationDose)
    {
        $this->firstVaccinationDose = $firstVaccinationDose;
        return $this;
    }

    public function setSecondVaccinationDose($secondVaccinationDose)
    {
        $this->secondVaccinationDose = $secondVaccinationDose;
        return $this;
    }

    public function setThirdVaccinationDose($thirdVaccinationDose)
    {
        $this->thirdVaccinationDose = $thirdVaccinationDose;
        return $this;
    }

    public function setStoolCollected($stoolCollected)
    {
        $this->stoolCollected = $stoolCollected;
        return $this;
    }

    public function setStoolId($stoolId)
    {
        $this->stoolId = $stoolId;
        return $this;
    }

    public function setStoolCollectionDate($stoolCollectionDate)
    {
        $this->stoolCollectionDate = $stoolCollectionDate;
        return $this;
    }

    public function setDischargeOutcome($dischargeOutcome)
    {
        $this->dischargeOutcome = $dischargeOutcome;
        return $this;
    }

    public function setDischargeDate($dischargeDate)
    {
        $this->dischargeDate = $dischargeDate;
        return $this;
    }

    public function setDischargeClassOther($dischargeClassOther)
    {
        $this->dischargeClassOther = $dischargeClassOther;
        return $this;
    }

    /**
     * Set RotaVirusSiteLab
     *
     * @param \NS\SentinelBundle\Entity\RotaVirusSiteLab $lab
     * @return Meningitis
     */
    public function setSiteLab($lab = null)
    {
        $this->siteLab = $lab;

        return $this;
    }

    /**
     * Get RotaVirusSiteLab
     *
     * @return \NS\SentinelBundle\Entity\RotaVirusSiteLab
     */
    public function getSiteLab()
    {
        return $this->siteLab;
    }

    /**
     * Set ReferenceLab
     *
     * @param \NS\SentinelBundle\Entity\ReferenceLab $lab
     * @return Meningitis
     */
    public function setReferenceLab($lab = null)
    {
        $this->referenceLab = $lab;

        return $this;
    }

    /**
     * Get ReferenceLab
     *
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function getReferenceLab()
    {
        return $this->referenceLab;
    }

    public function hasReferenceLab()
    {
        return ($this->referenceLab instanceof ReferenceLab);
    }

    public function serialize()
    {
        return serialize(array($this->id,$this->region,$this->country,$this->site));
    }

    public function unserialize($serialized)
    {
        list($this->id,$this->region,$this->country,$this->site) = unserialize($serialized);
    }
}
