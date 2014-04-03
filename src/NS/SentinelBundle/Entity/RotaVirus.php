<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationType;
use NS\SentinelBundle\Form\Types\Dehydration;
use NS\SentinelBundle\Form\Types\Rehydration;
use NS\SentinelBundle\Form\Types\Doses;

use \NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;

use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

/**
 * Description of RotaVirus
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus")
 * @ORM\Table(name="rotavirus_cases",uniqueConstraints={@ORM\UniqueConstraint(name="site_case_id_idx",columns={"site_id","caseId"})})
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 */
class RotaVirus implements IdentityAssignmentInterface
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
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @var Country $country
     * @ORM\ManyToOne(targetEntity="Country",inversedBy="rotavirusCases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @var Site $site
     * @ORM\ManyToOne(targetEntity="Site",inversedBy="rotavirusCases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\OneToOne(targetEntity="RotaVirusSiteLab", mappedBy="case")
     */
    private $lab;

//ii. Case-based Demographic Data
    /**
     * case_ID
     * @var string $caseId
     * @ORM\Column(name="caseId",type="string",nullable=false)
     */
    private $caseId;

    /**
     * gender
     * @var Gender $gender
     * @ORM\Column(name="gender",type="Gender",nullable=true)
     */
    private $gender;

    /**
     * birthdate
     * @var \DateTime $dob
     * @ORM\Column(name="dob",type="date",nullable=true)
     */
    private $dob;

    /**
     * age_months
     * @var integer $age
     * @ORM\Column(name="age",type="integer",nullable=true)
     */
    private $age;

    /**
     * case_district
     * @var string $district
     * @ORM\Column(name="district",type="string",nullable=true)
     */
    private $district;

//iii. Case-based Clinical Data
    /**
     * adm_date
     * @var \DateTime $admissionDate
     * @ORM\Column(name="admissionDate",type="date",nullable=true)
     */
    private $admissionDate;

    /**
     * symp_diarrhoea
     * @var TripleChoice $symptomDiarrhea
     * @ORM\Column(name="symptomDiarrhea",type="TripleChoice",nullable=true)
     */
    private $symptomDiarrhea;

    /**
     * symp_dia_onset_date
     * @var \DateTime $symptomDiarrheaOnset
     * @ORM\Column(name="symptomDiarrheaOnset",type="date",nullable=true)
     */
    private $symptomDiarrheaOnset;

    /**
     * symp_dia_episodes
     * @var integer $symptomDiarrheaEpisodes
     * @ORM\Column(name="symptomDiarrheaEpisodes",type="integer",nullable=true)
     */
    private $symptomDiarrheaEpisodes;

    /**
     * symp_dia_duration
     * @var integer $symptomDiarrheaDuration
     * @ORM\Column(name="symptomDiarrheaDuration",type="integer",nullable=true)
     */
    private $symptomDiarrheaDuration;

    /**
     * symp_vomit
     * @var TripleChoice $symptomVomit
     * @ORM\Column(name="symptomVomit",type="TripleChoice",nullable=true)
     */
    private $symptomDiarrheaVomit;

    /**
     * symp_vomit_episodes
     * @var integer $symptomVomitEpisodes
     * @ORM\Column(name="symptomVomitEpisodes",type="integer",nullable=true)
     */
    private $symptomVomitEpisodes;

    /**
     * symp_vomit_duration
     * @var integer $symptomVomitDuration
     * @ORM\Column(name="symptomVomitDuration",type="integer",nullable=true)
     */
    private $symptomVomitDuration;

    /**
     * symp_dehydration
     * @var Dehydration $symptomDehydration
     * @ORM\Column(name="symptomDehydration",type="Dehydration",nullable=true)
     */
    private $symptomDehydration;

// Treatment
    /**
     * rehydration
     * @var TripleChoice $rehydration
     * @ORM\Column(name="rehydration",type="TripleChoice",nullable=true)
     */
    private $rehydration;

    /**
     * rehydration_type
     * @var Rehydration $rehydrationType
     * @ORM\Column(name="rehydrationType",type="Rehydration",nullable=true)
     */
    private $rehydrationType;

    /**
     * rehydration_type_other
     * @var string $rehydrationOther
     * @ORM\Column(name="rehydrationOther",type="string",nullable=true)
     */
    private $rehydrationOther;

//iv. Case-based Vaccination History
    /**
     * @var RotavirusVaccinationReceived $vaccinationReceived
     * @ORM\Column(name="vaccinationReceived",type="RotavirusVaccinationReceived",nullable=true)
     * RV_received
     */
    private $vaccinationReceived;

    /**
     * RV_type
     * @var RotavirusVaccinationType $vaccinationType
     * @ORM\Column(name="vaccinationType",type="RotavirusVaccinationType",nullable=true)
     */
    private $vaccinationType;

    /**
     * RV_doses
     * @var Doses $doses
     * @ORM\Column(name="doses",type="Doses",nullable=true)
     */
    private $doses;

    /**
     * RV_dose1_date
     * @var \DateTime $firstVaccinationDose
     * @ORM\Column(name="firstVaccinationDose",type="date",nullable=true)
     */
    private $firstVaccinationDose;

    /**
     * RV_dose2_date
     * @var \DateTime $secondVaccinationDose
     * @ORM\Column(name="secondVaccinationDose",type="date",nullable=true)
     */
    private $secondVaccinationDose;

    /**
     * RV_dose3_date
     * @var \DateTime $thirdVaccinationDose
     * @ORM\Column(name="thirdVaccinationDose",type="date",nullable=true)
     */
    private $thirdVaccinationDose;

//v. Case-based Specimen Collection Data
    /**
     * stool_collected
     * @var TripleChoice $stoolCollected
     * @ORM\Column(name="stoolCollected",type="TripleChoice",nullable=true)
     */
    private $stoolCollected;

    /**
     * stool_ID
     * @var string $stoolId
     * @ORM\Column(name="stoolId",type="string",nullable=true)
     */
    private $stoolId;

    /**
     * stool_collect_date
     * @var \DateTime $stoolCollectionDate
     * @ORM\Column(name="stoolCollectionDate",type="date",nullable=true)
     */
    private $stoolCollectionDate;


//vii. Case-based Outcome Data
    /**
     * disch_outcome
     * @var DischargeOutcome $dischargeOutcome
     * @ORM\Column(name="dischargeOutcome",type="DischargeOutcome",nullable=true)
     */
    private $dischargeOutcome;

    /**
     * @var \DateTime $dischargeDate
     * @ORM\Column(name="dischargeDate",type="date",nullable=true)
 */
    private $dischargeDate;

    /**
     * @var string $dischargeClassOther
     * @ORM\Column(name="dischargeClassOther",type="string",nullable=true)
     */
    private $dischargeClassOther;

    /**
     * comment
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     */
    private $comment;

    public function hasId()
    {
        return !is_null($this->id);
    }

    public function __toString()
    {
        return $this->id;
    }

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

    public function getLab()
    {
        return $this->lab;
    }

    public function setLab($lab)
    {
        $this->lab = $lab;
        return $this;
    }

    public function hasSiteLab()
    {
        return ($this->lab instanceof RotaVirusSiteLab);
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

        $this->setRegion($country->getRegion());

        return $this;
    }

    public function setSite(Site $site)
    {
        $this->site = $site;

        $this->setCountry($site->getCountry());

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

    public function setDob($dob)
    {
        if(!$dob instanceOf \DateTime)
            return;

        $this->dob = $dob;

        $interval = ($this->admissionDate) ? $dob->diff($this->admissionDate) : $dob->diff(new \DateTime());
        $this->setAge(($interval->format('%a') / 30));
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

        if (($this->admissionDate && $this->dob))
        {
            $interval = $this->dob->diff($this->admissionDate);
            $this->setAge(($interval->format('%a') / 30));
        }

        return $this;
    }

    public function setSymptomDiarrhea(TripleChoice $symptomDiarrhea)
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

    public function setStoolCollectionDate($stoolCollectionDate)
    {
        $this->stoolCollectionDate = $stoolCollectionDate;
        return $this;
    }

    public function setDischargeOutcome(DischargeOutcome $dischargeOutcome)
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

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function getFullIdentifier($id)
    {
        return sprintf("%s-%s-%s-%06d",
                $this->getRegion()->getCode(),
                $this->country->getCode(),
                $this->site->getCode(),$id);
    }
}