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
 */
class RotaVirus extends BaseCase
{
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
// LAB DATA

    //------------------------------
    // Site Lab Specific
    /**
     * @var \DateTime $siteReceivedDate
     * @ORM\Column(name="siteReceivedDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $siteReceivedDate;

    /**
     * @var string $siteLabId
     * @ORM\Column(name="siteLabId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $siteLabId;

    //------------------------------
    // National Lab Specific
    /**
     * @var TripleChoice $sentToNL
     * @ORM\Column(name="sentToNL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $sentToNL;

    /**
     * @var \DateTime $sentToNLDate
     * @ORM\Column(name="sentToNLDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $sentToNLDate;

    /**
     * @var \DateTime $nlReceivedDate
     * @ORM\Column(name="nlReceivedDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $nlReceivedDate;

    /**
     * @var string $nlLabId
     * @ORM\Column(name="nlLabId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $nlLabId;

    //------------------------------
    // Regional Reference Lab Specific
    /**
     * @var TripleChoice $sentToRRL
     * @ORM\Column(name="sentToRRL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $sentToRRL;

    /**
     * @var \DateTime $sentToRRLDate
     * @ORM\Column(name="sentToRRLDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $sentToRRLDate;

    /**
     * @var \DateTime $rrlReceivedDate
     * @ORM\Column(name="rrlReceivedDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $rrlReceivedDate;

    /**
     * @var string $rrlLabId
     * @ORM\Column(name="rrlLabId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $rrlLabId;

    //---------------------------------
    // Global Variables
    /**
     * stool_adequate
     * @var TripleChoice $adequate
     * @ORM\Column(name="adequate",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $adequate;

    /**
     * @var TripleChoice $stored
     * @ORM\Column(name="stored",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $stored;

    /**
     * @var TripleChoice $elisaDone
     * @ORM\Column(name="elisaDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $elisaDone;

    /**
     * @var ElisaKit $elisaKit
     * @ORM\Column(name="elisaKit",type="ElisaKit",nullable=true)
     * @Groups({"api"})
     */
    private $elisaKit;

    /**
     * @var string $elisaKitOther
     * @ORM\Column(name="elisaKitOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $elisaKitOther;

    /**
     * @var string $elisaLoadNumber
     * @ORM\Column(name="elisaLoadNumber",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $elisaLoadNumber;

    /**
     * @var \DateTime $elisaExpiryDate
     * @ORM\Column(name="elisaExpiryDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $elisaExpiryDate;

    /**
     * @var \DateTime $testDate
     * @ORM\Column(name="elisaTestDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $elisaTestDate;

    /**
     * @var ElisaResult $elisaResult
     * @ORM\Column(name="elisaResult",type="ElisaResult",nullable=true)
     * @Groups({"api"})
     */
    private $elisaResult;

    /**
     * @var TripleChoice $secondaryElisaDone
     * @ORM\Column(name="secondaryElisaDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $secondaryElisaDone;

    /**
     * @var ElisaKit $secondaryElisaKit
     * @ORM\Column(name="secondaryElisaKit",type="ElisaKit",nullable=true)
     * @Groups({"api"})
     */
    private $secondaryElisaKit;

    /**
     * @var string $secondaryElisaKitOther
     * @ORM\Column(name="secondaryElisaKitOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $secondaryElisaKitOther;

    /**
     * @var string $secondaryElisaLoadNumber
     * @ORM\Column(name="secondaryElisaLoadNumber",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $secondaryElisaLoadNumber;

    /**
     * @var \DateTime $secondaryElisaExpiryDate
     * @ORM\Column(name="secondaryElisaExpiryDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $secondaryElisaExpiryDate;

    /**
     * @var \DateTime $testDate
     * @ORM\Column(name="secondaryElisaTestDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $secondaryElisaTestDate;

    /**
     * @var ElisaResult $secondaryElisaResult
     * @ORM\Column(name="secondaryElisaResult",type="ElisaResult",nullable=true)
     * @Groups({"api"})
     */
    private $secondaryElisaResult;

    /**
     * @var \DateTime $genotypingDate
     * @ORM\Column(name="genotypingDate",type="date", nullable=true)
     * @Groups({"api"})
     */
    private $genotypingDate;

    /**
     * @var GenotypeResultG $genotypingResultg
     * @ORM\Column(name="genotypingResultg",type="GenotypeResultG", nullable=true)
     * @Groups({"api"})
     */
    private $genotypingResultg;

    /**
     * @var string $genotypingResultGSpecify
     * @ORM\Column(name="genotypingResultGSpecify",type="string", nullable=true)
     * @Groups({"api"})
     */
    private $genotypingResultGSpecify;

    /**
     * @var GenotypeResultP $genotypeResultP
     * @ORM\Column(name="genotypeResultP",type="GenotypeResultP", nullable=true)
     * @Groups({"api"})
     */
    private $genotypeResultP;

    /**
     * @var string $genotypeResultPSpecify
     * @ORM\Column(name="genotypeResultPSpecify",type="string", nullable=true)
     * @Groups({"api"})
     */
    private $genotypeResultPSpecify;

// LAB DATA END
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

    public function getCode()
    {
        return $this->code;
    }

    public function getCaseId()
    {
        return $this->caseId;
    }

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

    public function getSymptomDehydrationAmount()
    {
        return $this->symptomDehydrationAmount;
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
    public function getSiteReceivedDate()
    {
        return $this->siteReceivedDate;
    }

    public function getSiteLabId()
    {
        return $this->siteLabId;
    }

    public function getSentToNL()
    {
        return $this->sentToNL;
    }

    public function getSentToNLDate()
    {
        return $this->sentToNLDate;
    }

    public function getNlReceivedDate()
    {
        return $this->nlReceivedDate;
    }

    public function getNlLabId()
    {
        return $this->nlLabId;
    }

    public function getSentToRRL()
    {
        return $this->sentToRRL;
    }

    public function getSentToRRLDate()
    {
        return $this->sentToRRLDate;
    }

    public function getRrlReceivedDate()
    {
        return $this->rrlReceivedDate;
    }

    public function getRrlLabId()
    {
        return $this->rrlLabId;
    }

    public function getAdequate()
    {
        return $this->adequate;
    }

    public function getStored()
    {
        return $this->stored;
    }

    public function getElisaDone()
    {
        return $this->elisaDone;
    }

    public function getElisaKit()
    {
        return $this->elisaKit;
    }

    public function getElisaKitOther()
    {
        return $this->elisaKitOther;
    }

    public function getElisaLoadNumber()
    {
        return $this->elisaLoadNumber;
    }

    public function getElisaExpiryDate()
    {
        return $this->elisaExpiryDate;
    }

    public function getElisaTestDate()
    {
        return $this->elisaTestDate;
    }

    public function getElisaResult()
    {
        return $this->elisaResult;
    }

    public function getSecondaryElisaDone()
    {
        return $this->secondaryElisaDone;
    }

    public function getSecondaryElisaKit()
    {
        return $this->secondaryElisaKit;
    }

    public function getSecondaryElisaKitOther()
    {
        return $this->secondaryElisaKitOther;
    }

    public function getSecondaryElisaLoadNumber()
    {
        return $this->secondaryElisaLoadNumber;
    }

    public function getSecondaryElisaExpiryDate()
    {
        return $this->secondaryElisaExpiryDate;
    }

    public function getSecondaryElisaTestDate()
    {
        return $this->secondaryElisaTestDate;
    }

    public function getSecondaryElisaResult()
    {
        return $this->secondaryElisaResult;
    }

    public function getGenotypingDate()
    {
        return $this->genotypingDate;
    }

    public function getGenotypingResultg()
    {
        return $this->genotypingResultg;
    }

    public function getGenotypingResultGSpecify()
    {
        return $this->genotypingResultGSpecify;
    }

    public function getGenotypeResultP()
    {
        return $this->genotypeResultP;
    }

    public function getGenotypeResultPSpecify()
    {
        return $this->genotypeResultPSpecify;
    }

    public function setSiteReceivedDate($siteReceivedDate)
    {
        $this->siteReceivedDate = $siteReceivedDate;
        return $this;
    }

    public function setSiteLabId($siteLabId)
    {
        $this->siteLabId = $siteLabId;
        return $this;
    }

    public function setSentToNL(TripleChoice $sentToNL)
    {
        $this->sentToNL = $sentToNL;
        return $this;
    }

    public function setSentToNLDate($sentToNLDate)
    {
        $this->sentToNLDate = $sentToNLDate;
        return $this;
    }

    public function setNlReceivedDate($nlReceivedDate)
    {
        $this->nlReceivedDate = $nlReceivedDate;
        return $this;
    }

    public function setNlLabId($nlLabId)
    {
        $this->nlLabId = $nlLabId;
        return $this;
    }

    public function setSentToRRL(TripleChoice $sentToRRL)
    {
        $this->sentToRRL = $sentToRRL;
        return $this;
    }

    public function setSentToRRLDate($sentToRRLDate)
    {
        $this->sentToRRLDate = $sentToRRLDate;
        return $this;
    }

    public function setRrlReceivedDate($rrlReceivedDate)
    {
        $this->rrlReceivedDate = $rrlReceivedDate;
        return $this;
    }

    public function setRrlLabId($rrlLabId)
    {
        $this->rrlLabId = $rrlLabId;
        return $this;
    }

    public function setAdequate(TripleChoice $adequate)
    {
        $this->adequate = $adequate;
        return $this;
    }

    public function setStored(TripleChoice $stored)
    {
        $this->stored = $stored;
        return $this;
    }

    public function setElisaDone(TripleChoice $elisaDone)
    {
        $this->elisaDone = $elisaDone;
        return $this;
    }

    public function setElisaKit(ElisaKit $elisaKit)
    {
        $this->elisaKit = $elisaKit;
        return $this;
    }

    public function setElisaKitOther($elisaKitOther)
    {
        $this->elisaKitOther = $elisaKitOther;
        return $this;
    }

    public function setElisaLoadNumber($elisaLoadNumber)
    {
        $this->elisaLoadNumber = $elisaLoadNumber;
        return $this;
    }

    public function setElisaExpiryDate($elisaExpiryDate)
    {
        $this->elisaExpiryDate = $elisaExpiryDate;
        return $this;
    }

    public function setElisaTestDate($elisaTestDate)
    {
        $this->elisaTestDate = $elisaTestDate;
        return $this;
    }

    public function setElisaResult(ElisaResult $elisaResult)
    {
        $this->elisaResult = $elisaResult;
        return $this;
    }

    public function setSecondaryElisaDone(TripleChoice $secondaryElisaDone)
    {
        $this->secondaryElisaDone = $secondaryElisaDone;
        return $this;
    }

    public function setSecondaryElisaKit(ElisaKit $secondaryElisaKit)
    {
        $this->secondaryElisaKit = $secondaryElisaKit;
        return $this;
    }

    public function setSecondaryElisaKitOther($secondaryElisaKitOther)
    {
        $this->secondaryElisaKitOther = $secondaryElisaKitOther;
        return $this;
    }

    public function setSecondaryElisaLoadNumber($secondaryElisaLoadNumber)
    {
        $this->secondaryElisaLoadNumber = $secondaryElisaLoadNumber;
        return $this;
    }

    public function setSecondaryElisaExpiryDate($secondaryElisaExpiryDate)
    {
        $this->secondaryElisaExpiryDate = $secondaryElisaExpiryDate;
        return $this;
    }

    public function setSecondaryElisaTestDate($secondaryElisaTestDate)
    {
        $this->secondaryElisaTestDate = $secondaryElisaTestDate;
        return $this;
    }

    public function setSecondaryElisaResult(ElisaResult $secondaryElisaResult)
    {
        $this->secondaryElisaResult = $secondaryElisaResult;
        return $this;
    }

    public function setGenotypingDate($genotypingDate)
    {
        $this->genotypingDate = $genotypingDate;
        return $this;
    }

    public function setGenotypingResultg(GenotypeResultG $genotypingResultg)
    {
        $this->genotypingResultg = $genotypingResultg;
        return $this;
    }

    public function setGenotypingResultGSpecify($genotypingResultGSpecify)
    {
        $this->genotypingResultGSpecify = $genotypingResultGSpecify;
        return $this;
    }

    public function setGenotypeResultP(GenotypeResultP $genotypeResultP)
    {
        $this->genotypeResultP = $genotypeResultP;
        return $this;
    }

    public function setGenotypeResultPSpecify($genotypeResultPSpecify)
    {
        $this->genotypeResultPSpecify = $genotypeResultPSpecify;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;
        return $this;
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
            'siteReceivedDate',
            'siteLabId',
            'sentToNL',
            'sentToNLDate',
            'nlReceivedDate',
            'nlLabId',
            'sentToRRL',
            'sentToRRLDate',
            'rrlReceivedDate',
            'rrlLabId',
            'adequate',
            'stored',
            'elisaDone',
            'elisaKit',
            'elisaKitOther',
            'elisaLoadNumber',
            'elisaExpiryDate',
            'elisaTestDate',
            'elisaResult',
            'secondaryElisaDone',
            'secondaryElisaKit',
            'secondaryElisaKitOther',
            'secondaryElisaLoadNumber',
            'secondaryElisaExpiryDate',
            'secondaryElisaTestDate',
            'secondaryElisaResult',
            'genotypingDate',
            'genotypingResultg',
            'genotypingResultGSpecify',
            'genotypeResultP',
            'genotypeResultPSpecify',
            'dischargeOutcome',
            'dischargeDate',
            'dischargeClassOther',
            'comment',
        );
    }

    public function hasLab()
    {
        return ($this->siteLabId || $this->siteReceivedDate);
    }
}