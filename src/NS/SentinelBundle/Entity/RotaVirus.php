<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationType;
use NS\SentinelBundle\Form\Types\ElisaResult;

use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

/**
 * Description of RotaVirus
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus")
 * @ORM\Table(name="rotavirus_cases")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 */
class RotaVirus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\NS\SentinelBundle\Generator\Custom")
     * @var string $id
     * @ORM\Column(name="id",type="string")
     */
    private $id;

//i. Sentinel Site Information
    /**
     * @var Region $region
     * @ORM\ManyToOne(targetEntity="Region",inversedBy="rotavirusCases")
     */
    private $region;

    /**
     * @var Country $country
     * @ORM\ManyToOne(targetEntity="Country",inversedBy="rotavirusCases")
     */
    private $country;

    /**
     * @var Site $site
     * @ORM\ManyToOne(targetEntity="Site",inversedBy="rotavirusCases")
     */
    private $site;

    //ISO3_code
    //site_code

    /**
     * @ORM\OneToOne(targetEntity="RotaVirusSiteLab", mappedBy="case")
     */
    private $lab;

//ii. Case-based Demographic Data
    /**
     * case_ID
     * @var string $caseId
     * @ORM\Column(name="caseId",type="string")
     */
    private $caseId;

    /**
     * gender
     * @var Gender $gender
     * @ORM\Column(name="gender",type="Gender")
     */
    private $gender;

    /**
     * birthdate
     * @var DateTime $dob
     * @ORM\Column(name="dob",type="date")
     */
    private $dob;

    /**
     * age_months
     * @var integer $age
     * @ORM\Column(name="age",type="integer")
     */
    private $age;

    /**
     * case_district
     * @var string $district
     * @ORM\Column(name="district",type="string")
     */
    private $district;

//iii. Case-based Clinical Data
    /**
     * adm_date
     * @var DateTime $admissionDate
     * @ORM\Column(name="admissionDate",type="date")
     */
    private $admissionDate;

    /**
     * symp_diarrhoea
     * @var TripleChoice $symptomDiarrhea
     * @ORM\Column(name="symptomDiarrhea",type="TripleChoice")
     */
    private $symptomDiarrhea;

    /**
     * symp_dia_onset_date
     * @var DateTime $symptomDiarrheaOnset
     * @ORM\Column(name="symptomDiarrheaOnset",type="date")
     */
    private $symptomDiarrheaOnset;

    /**
     * symp_dia_episodes
     * @var integer $symptomDiarrheaEpisodes
     * @ORM\Column(name="symptomDiarrheaEpisodes",type="integer")
     */
    private $symptomDiarrheaEpisodes;

    /**
     * symp_dia_duration
     * @var integer $symptomDiarrheaDuration
     * @ORM\Column(name="symptomDiarrheaDuration",type="integer")
     */
    private $symptomDiarrheaDuration;

    /**
     * symp_vomit
     * @var TripleChoice $symptomVomit
     * @ORM\Column(name="symptomVomit",type="TripleChoice")
     */
    private $symptomDiarrheaVomit;

    /**
     * symp_vomit_episodes
     * @var integer $symptomVomitEpisodes
     * @ORM\Column(name="symptomVomitEpisodes",type="integer")
     */
    private $symptomVomitEpisodes;

    /**
     * symp_vomit_duration
     * @var integer $symptomVomitDuration
     * @ORM\Column(name="symptomVomitDuration",type="integer")
     */
    private $symptomVomitDuration;

    /**
     * symp_dehydration
     * @var Dehydration $symptomDehydration
     * @ORM\Column(name="symptomDehydration",type="Dehydration")
     */
    private $symptomDehydration;


// Treatment
    /**
     * rehydration
     * @var TripleChoice $rehydration
     * @ORM\Column(name="rehydration",type="TripleChoice")
     */
    private $rehydration;

    /**
     * rehydration_type
     * @var Rehydration $rehydrationType
     * @ORM\Column(name="rehydrationType",type="Rehydration")
     */
    private $rehydrationType;

    /**
     * rehydration_type_other
     * @var string $rehydrationOther
     * @ORM\Column(name="rehydrationOther",type="string")
     */
    private $rehydrationOther;

//iv. Case-based Vaccination History
    /**
     * @var RotavirusVaccinationReceived $vaccinationReceived
     * @ORM\Column(name="vaccinationReceived",type="RotavirusVaccinationReceived")
     * RV_received
     */
    private $vaccinationReceived;

    /**
     * RV_type
     * @var RotavirusVaccinationType $vaccinationType
     * @ORM\Column(name="vaccinationType",type="RotavirusVaccinationType")
     */
    private $vaccinationType;

    /**
     * RV_doses
     * @var Doses $doses
     * @ORM\Column(name="doses",type="Doses")
     */
    private $doses;

    /**
     * RV_dose1_date
     * @var DateTime $firstVaccinationDose
     * @ORM\Column(name="firstVaccinationDose",type="date")
     */
    private $firstVaccinationDose;

    /**
     * RV_dose2_date
     * @var DateTime $secondVaccinationDose
     * @ORM\Column(name="secondVaccinationDose",type="date")
     */
    private $secondVaccinationDose;

    /**
     * RV_dose3_date
     * @var DateTime $thirdVaccinationDose
     * @ORM\Column(name="thirdVaccinationDose",type="date")
     */
    private $thirdVaccinationDose;

//v. Case-based Specimen Collection Data
    /**
     * stool_collected
     * @var TripleChoice $stoolCollected
     * @ORM\Column(name="stoolCollected",type="TripleChoice")
     */
    private $stoolCollected;

    /**
     * stool_ID
     * @var string $stoolId
     * @ORM\Column(name="stoolId",type="string")
     */
    private $stoolId;

    /**
     * stool_collect_date
     * @var DateTime $stoolCollectionDate
     * @ORM\Column(name="stoolCollectionDate",type="date")
     */
    private $stoolCollectionDate;


//vii. Case-based Outcome Data
    /**
     * disch_outcome
     * @var DischargeOutcome $dischargeOutcome
     * @ORM\Column(name="dischargeOutcome",type="DischargeOutcome")
     */
    private $dischargeOutcome;

    /**
     * @var DateTime $dischargeDate
     * @ORM\Column(name="dischargeDate",type="date")
 */
    private $dischargeDate;

    /**
     * @var string $dischargeClassOther
     * @ORM\Column(name="dischargeClassOther",type="string")
     */
    private $dischargeClassOther;

    /**
     * comment
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     */
    private $comment;

    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

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

    public function getComment()
    {
        return $this->comment;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setRegion(Region $region)
    {
        $this->region = $region;
        return $this;
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    public function setSite(Site $site)
    {
        $this->site = $site;
        return $this;
    }

    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;
        return $this;
    }

    public function setGender(Gender $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public function setDob(DateTime $dob)
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

    public function setAdmissionDate(DateTime $admissionDate)
    {
        $this->admissionDate = $admissionDate;
        return $this;
    }

    public function setSymptomDiarrhea(TripleChoice $symptomDiarrhea)
    {
        $this->symptomDiarrhea = $symptomDiarrhea;
        return $this;
    }

    public function setSymptomDiarrheaOnset(DateTime $symptomDiarrheaOnset)
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

    public function setSymptomDiarrheaVomit(TripleChoice $symptomDiarrheaVomit)
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

    public function setSymptomDehydration(Dehydration $symptomDehydration)
    {
        $this->symptomDehydration = $symptomDehydration;
        return $this;
    }

    public function setRehydration(TripleChoice $rehydration)
    {
        $this->rehydration = $rehydration;
        return $this;
    }

    public function setRehydrationType(Rehydration $rehydrationType)
    {
        $this->rehydrationType = $rehydrationType;
        return $this;
    }

    public function setRehydrationOther($rehydrationOther)
    {
        $this->rehydrationOther = $rehydrationOther;
        return $this;
    }

    public function setVaccinationReceived(RotavirusVaccinationReceived $vaccinationReceived)
    {
        $this->vaccinationReceived = $vaccinationReceived;
        return $this;
    }

    public function setVaccinationType(RotavirusVaccinationType $vaccinationType)
    {
        $this->vaccinationType = $vaccinationType;
        return $this;
    }

    public function setDoses(Doses $doses)
    {
        $this->doses = $doses;
        return $this;
    }

    public function setFirstVaccinationDose(DateTime $firstVaccinationDose)
    {
        $this->firstVaccinationDose = $firstVaccinationDose;
        return $this;
    }

    public function setSecondVaccinationDose(DateTime $secondVaccinationDose)
    {
        $this->secondVaccinationDose = $secondVaccinationDose;
        return $this;
    }

    public function setThirdVaccinationDose(DateTime $thirdVaccinationDose)
    {
        $this->thirdVaccinationDose = $thirdVaccinationDose;
        return $this;
    }

    public function setStoolCollected(TripleChoice $stoolCollected)
    {
        $this->stoolCollected = $stoolCollected;
        return $this;
    }

    public function setStoolId($stoolId)
    {
        $this->stoolId = $stoolId;
        return $this;
    }

    public function setStoolCollectionDate(DateTime $stoolCollectionDate)
    {
        $this->stoolCollectionDate = $stoolCollectionDate;
        return $this;
    }

    public function setDischargeOutcome(DischargeOutcome $dischargeOutcome)
    {
        $this->dischargeOutcome = $dischargeOutcome;
        return $this;
    }

    public function setDischargeDate(DateTime $dischargeDate)
    {
        $this->dischargeDate = $dischargeDate;
        return $this;
    }

    public function setDischargeClassOther($dischargeClassOther)
    {
        $this->dischargeClassOther = $dischargeClassOther;
        return $this;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }


}
