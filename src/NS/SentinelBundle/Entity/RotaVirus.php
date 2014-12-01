<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationType;
use NS\SentinelBundle\Form\Types\Dehydration;
use NS\SentinelBundle\Form\Types\Rehydration;
use NS\SentinelBundle\Form\Types\ElisaResult;
use NS\SentinelBundle\Form\Types\ElisaKit;
use NS\SentinelBundle\Form\Types\GenotypeResultG;
use NS\SentinelBundle\Form\Types\GenotypeResultP;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome;

use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\AccessType;

/**
 * Description of RotaVirus
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus")
 * @ORM\Table(name="rotavirus_cases",uniqueConstraints={@ORM\UniqueConstraint(name="rotavirus_site_case_id_idx",columns={"site_id","caseId"})})
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @AccessType("public_method")
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class RotaVirus extends BaseCase
{
//i. Sentinel Site Information
    /**
     * @ORM\OneToMany(targetEntity="\NS\SentinelBundle\Entity\Rota\ExternalLab", mappedBy="case")
     */
    protected $externalLabs;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Rota\SiteLab", mappedBy="case",cascade={"persist"})
     */
    protected $siteLab;
    protected $siteLabClass   = '\NS\SentinelBundle\Entity\Rota\SiteLab';
    protected $referenceClass = '\NS\SentinelBundle\Entity\Rota\ReferenceLab';
    protected $nationalClass  = '\NS\SentinelBundle\Entity\Rota\NationalLab';

//ii. Case-based Demographic Data

    /**
     * case_district
     * @var string $district
     * @ORM\Column(name="district",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $district;

//iii. Case-based Clinical Data

    /**
     * symp_diarrhoea
     * @var TripleChoice $symptomDiarrhea
     * @ORM\Column(name="symptomDiarrhea",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $symptomDiarrhea;

    /**
     * symp_dia_onset_date
     * @var \DateTime $symptomDiarrheaOnset
     * @ORM\Column(name="symptomDiarrheaOnset",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $symptomDiarrheaOnset;

    /**
     * symp_dia_episodes
     * @var integer $symptomDiarrheaEpisodes
     * @ORM\Column(name="symptomDiarrheaEpisodes",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $symptomDiarrheaEpisodes;

    /**
     * symp_dia_duration
     * @var integer $symptomDiarrheaDuration
     * @ORM\Column(name="symptomDiarrheaDuration",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $symptomDiarrheaDuration;

    /**
     * symp_vomit
     * @var TripleChoice $symptomVomit
     * @ORM\Column(name="symptomVomit",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $symptomVomit;

    /**
     * symp_vomit_episodes
     * @var integer $symptomVomitEpisodes
     * @ORM\Column(name="symptomVomitEpisodes",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $symptomVomitEpisodes;

    /**
     * symp_vomit_duration
     * @var integer $symptomVomitDuration
     * @ORM\Column(name="symptomVomitDuration",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $symptomVomitDuration;

    /**
     * symp_dehydration
     * @var TripleChoice $symptomDehydration
     * @ORM\Column(name="symptomDehydration",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $symptomDehydration;

    /**
     * symp_dehydration
     * @var Dehydration $symptomDehydration
     * @ORM\Column(name="symptomDehydrationAmount",type="Dehydration",nullable=true)
     * @Groups({"api"})
     */
    private $symptomDehydrationAmount;

// Treatment
    /**
     * rehydration
     * @var TripleChoice $rehydration
     * @ORM\Column(name="rehydration",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $rehydration;

    /**
     * rehydration_type
     * @var Rehydration $rehydrationType
     * @ORM\Column(name="rehydrationType",type="Rehydration",nullable=true)
     * @Groups({"api"})
     */
    private $rehydrationType;

    /**
     * rehydration_type_other
     * @var string $rehydrationOther
     * @ORM\Column(name="rehydrationOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $rehydrationOther;

//iv. Case-based Vaccination History
    /**
     * @var RotavirusVaccinationReceived $vaccinationReceived
     * @ORM\Column(name="vaccinationReceived",type="RotavirusVaccinationReceived",nullable=true)
     * @Groups({"api"})
     */
    private $vaccinationReceived;

    /**
     * RV_type
     * @var RotavirusVaccinationType $vaccinationType
     * @ORM\Column(name="vaccinationType",type="RotavirusVaccinationType",nullable=true)
     * @Groups({"api"})
     */
    private $vaccinationType;

    /**
     * RV_doses
     * @var ThreeDoses $doses
     * @ORM\Column(name="doses",type="ThreeDoses",nullable=true)
     * @Groups({"api"})
     */
    private $doses;

    /**
     * RV_dose1_date
     * @var \DateTime $firstVaccinationDose
     * @ORM\Column(name="firstVaccinationDose",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $firstVaccinationDose;

    /**
     * RV_dose2_date
     * @var \DateTime $secondVaccinationDose
     * @ORM\Column(name="secondVaccinationDose",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $secondVaccinationDose;

    /**
     * RV_dose3_date
     * @var \DateTime $thirdVaccinationDose
     * @ORM\Column(name="thirdVaccinationDose",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $thirdVaccinationDose;

//v. Case-based Specimen Collection Data
    /**
     * stool_collected
     * @var TripleChoice $stoolCollected
     * @ORM\Column(name="stoolCollected",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $stoolCollected;

    /**
     * stool_ID
     * @var string $stoolId
     * @ORM\Column(name="stoolId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $stoolId;

    /**
     * stool_collect_date
     * @var \DateTime $stoolCollectionDate
     * @ORM\Column(name="stoolCollectionDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $stoolCollectionDate;

//vii. Case-based Outcome Data
    /**
     * disch_outcome
     * @var RotavirusDischargeOutcome $dischargeOutcome
     * @ORM\Column(name="dischargeOutcome",type="RotavirusDischargeOutcome",nullable=true)
     * @Groups({"api"})
     */
    private $dischargeOutcome;

    /**
     * @var \DateTime $dischargeDate
     * @ORM\Column(name="dischargeDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $dischargeDate;

    /**
     * @var string $dischargeClassOther
     * @ORM\Column(name="dischargeClassOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $dischargeClassOther;

    /**
     * comment
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Groups({"api"})
     */
    private $comment;

    public function getDistrict()
    {
        return $this->district;
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

    public function getSymptomVomit()
    {
        return $this->symptomVomit;
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

    public function getSymptomDehydrationAmount()
    {
        return $this->symptomDehydrationAmount;
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

    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    public function setSymptomDiarrhea(TripleChoice $symptomDiarrhea)
    {
        $this->symptomDiarrhea = $symptomDiarrhea;
        return $this;
    }

    public function setSymptomDiarrheaOnset($symptomDiarrheaOnset)
    {
        if ($symptomDiarrheaOnset instanceof \DateTime)
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

    public function setSymptomVomit(TripleChoice $symptomVomit)
    {
        $this->symptomVomit = $symptomVomit;
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

    public function setSymptomDehydration(TripleChoice $symptomDehydration)
    {
        $this->symptomDehydration = $symptomDehydration;
        return $this;
    }

    public function setSymptomDehydrationAmount(Dehydration $symptomDehydrationAmount)
    {
        $this->symptomDehydrationAmount = $symptomDehydrationAmount;
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

    public function setDoses(ThreeDoses $doses)
    {
        $this->doses = $doses;
        return $this;
    }

    public function setFirstVaccinationDose($firstVaccinationDose)
    {
        if ($firstVaccinationDose instanceof \DateTime)
            $this->firstVaccinationDose = $firstVaccinationDose;

        return $this;
    }

    public function setSecondVaccinationDose($secondVaccinationDose)
    {
        if ($secondVaccinationDose instanceof \DateTime)
            $this->secondVaccinationDose = $secondVaccinationDose;

        return $this;
    }

    public function setThirdVaccinationDose($thirdVaccinationDose)
    {
        if ($thirdVaccinationDose instanceof \DateTime)
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
        if ($stoolCollectionDate instanceof \DateTime)
            $this->stoolCollectionDate = $stoolCollectionDate;

        return $this;
    }

    public function setDischargeOutcome(RotavirusDischargeOutcome $dischargeOutcome)
    {
        $this->dischargeOutcome = $dischargeOutcome;
        return $this;
    }

    public function setDischargeDate($dischargeDate)
    {
        if ($dischargeDate instanceof \DateTime)
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

    public function calculateResult()
    {

    }

    public function getIncompleteField()
    {
        return null;
    }

    public function getMinimumRequiredFields()
    {
        return array(
            'admDate',
            'district',
            'symptomDiarrhea',
            'symptomDiarrheaOnset',
            'symptomDiarrheaEpisodes',
            'symptomDiarrheaDuration',
            'symptomVomit',
            'symptomVomitEpisodes',
            'symptomVomitDuration',
            'symptomDehydration',
            'symptomDehydrationAmount',
            'rehydration',
            'rehydrationType',
            'rehydrationOther',
            'vaccinationReceived',
            'vaccinationType',
            'doses',
            'firstVaccinationDose',
            'secondVaccinationDose',
            'thirdVaccinationDose',
            'stoolCollected',
            'stoolId',
            'stoolCollectionDate',
            'dischargeOutcome',
            'dischargeDate',
            'dischargeClassOther',
            'comment',
        );
    }
}
