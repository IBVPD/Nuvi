<?php

namespace NS\SentinelBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Gedmo\Mapping\Annotation as Gedmo;
use \JMS\Serializer\Annotation\Groups;
use \JMS\Serializer\Annotation\Exclude;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
use \NS\SentinelBundle\Form\Types\CaseStatus;
use \NS\SentinelBundle\Form\Types\CSFAppearance;
use \NS\SentinelBundle\Form\Types\CXRAdditionalResult;
use \NS\SentinelBundle\Form\Types\CXRResult;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\DischargeClassification;
use \NS\SentinelBundle\Form\Types\DischargeOutcome;
use \NS\SentinelBundle\Form\Types\FourDoses;
use \NS\SentinelBundle\Form\Types\ThreeDoses;
use \NS\SentinelBundle\Form\Types\IBDCaseResult;
use \NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use \NS\SentinelBundle\Form\Types\MeningitisVaccinationType;
use \NS\SentinelBundle\Form\Types\OtherSpecimen;
use \NS\SentinelBundle\Form\Types\PCVType;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\VaccinationReceived;
use \NS\UtilBundle\Form\Types\ArrayChoice;
use \Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Description of IBD
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD")
 * @ORM\Table(name="ibd_cases",uniqueConstraints={@ORM\UniqueConstraint(name="ibd_site_case_id_idx",columns={"site_id","caseId"})})
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @Assert\Callback(methods={"validate"})
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class IBD extends BaseCase
{
    /**
     * @ORM\OneToMany(targetEntity="\NS\SentinelBundle\Entity\IBD\ExternalLab", mappedBy="case")
     */
    protected $externalLabs;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD\SiteLab", mappedBy="case",cascade={"persist"})
     */
    protected $siteLab;

    /**
     * @Exclude()
     */
    protected $siteLabClass = '\NS\SentinelBundle\Entity\IBD\SiteLab';

    /**
     * @Exclude()
     */
    protected $referenceClass = '\NS\SentinelBundle\Entity\IBD\ReferenceLab';

    /**
     * @Exclude()
     */
    protected $nationalClass  = '\NS\SentinelBundle\Entity\IBD\NationalLab';
// Case based demographic
    /**
     * @var string $district
     * @ORM\Column(name="district",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $district;

//Case-based Clinical Data
    /**
     * @var \DateTime $onsetDate
     * @ORM\Column(name="onsetDate",type="date",nullable=true)
     * @Groups({"api"})
     * @Assert\DateTime
     */
    protected $onsetDate;

    /**
     * @var Diagnosis $admDx
     * @ORM\Column(name="admDx",type="Diagnosis",nullable=true)
     * @Groups({"api"})
     */
    private $admDx;

    /**
     * @var string $admDxOther
     * @ORM\Column(name="admDxOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $admDxOther;

    /**
     * @var TripleChoice $antibiotics
     * @ORM\Column(name="antibiotics",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $antibiotics;

//MENINGITIS
    /**
     * @var TripleChoice $menSeizures
     * @ORM\Column(name="menSeizures",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $menSeizures;

    /**
     * @var TripleChoice $menFever
     * @ORM\Column(name="menFever",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $menFever;

    /**
     * @var TripleChoice $menAltConscious
     * @ORM\Column(name="menAltConscious",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $menAltConscious;

    /**
     * @var TripleChoice $menInabilityFeed
     * @ORM\Column(name="menInabilityFeed",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $menInabilityFeed;

    /**
     * @var TripleChoice $menNeckStiff
     * @ORM\Column(name="menNeckStiff",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $menNeckStiff;

    /**
     * @var TripleChoice $menRash
     * @ORM\Column(name="menRash",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $menRash;

    /**
     * @var TripleChoice $menFontanelleBulge
     * @ORM\Column(name="menFontanelleBulge",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $menFontanelleBulge;

    /**
     * @var TripleChoice $menLethargy
     * @ORM\Column(name="menLethargy",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $menLethargy;

//PNEUMONIA / SEPSIS
    /**
     * @var TripleChoice $pneuDiffBreathe
     * @ORM\Column(name="pneuDiffBreathe",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $pneuDiffBreathe;

    /**
     * @var TripleChoice $pneuChestIndraw
     * @ORM\Column(name="pneuChestIndraw",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $pneuChestIndraw;

    /**
     * @var TripleChoice $pneuCough
     * @ORM\Column(name="pneuCough",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $pneuCough;

    /**
     * @var TripleChoice $pneuCyanosis
     * @ORM\Column(name="pneuCyanosis",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $pneuCyanosis;

    /**
     * @var TripleChoice $pneuStridor
     * @ORM\Column(name="pneuStridor",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $pneuStridor;

    /**
     * @var integer $pneuRespRate
     * @ORM\Column(name="pneuRespRate",type="integer",nullable=true)
     * @Assert\Range(min=10,max=100,minMessage="Please provide a valid respiratory rate",maxMessage="Please provide a valid respiratory rate")
     * @Groups({"api"})
     */
    private $pneuRespRate;

    /**
     * @var TripleChoice $pneuVomit
     * @ORM\Column(name="pneuVomit",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $pneuVomit;

    /**
     * @var TripleChoice $pneuHypothermia
     * @ORM\Column(name="pneuHypothermia",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $pneuHypothermia;

    /**
     * @var TripleChoice $pneuMalnutrition
     * @ORM\Column(name="pneuMalnutrition",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $pneuMalnutrition;

    /**
     * @var TripleChoice $cxrDone
     * @ORM\Column(name="cxrDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $cxrDone;

    /**
     * @var CXRResult $cxrResult
     * @ORM\Column(name="cxrResult",type="CXRResult",nullable=true)
     * @Groups({"api"})
     */
    private $cxrResult;

    /**
     * @var CXRAdditionalResult $cxrResult
     * @ORM\Column(name="cxrAdditionalResult",type="CXRAdditionalResult",nullable=true)
     * @Groups({"api"})
     */
    private $cxrAdditionalResult;

//Case-based Vaccination History
    /**
     * @var VaccinationReceived $hibReceived
     * @ORM\Column(name="hibReceived",type="VaccinationReceived",nullable=true)
     * @Groups({"api"})
     */
    private $hibReceived;

    /**
     * @var FourDoses $hibDoses
     * @ORM\Column(name="hibDoses",type="FourDoses",nullable=true)
     * @Groups({"api"})
     */
    private $hibDoses;

    /**
     * @var \DateTime $hibMostRecentDose
     * @ORM\Column(name="hibMostRecentDose",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $hibMostRecentDose;

    /**
     * @var VaccinationReceived $pcvReceived
     * @ORM\Column(name="pcvReceived",type="VaccinationReceived",nullable=true)
     * @Groups({"api"})
     */
    private $pcvReceived;

    /**
     * @var ThreeDoses $pcvDoses
     * @ORM\Column(name="pcvDoses",type="ThreeDoses",nullable=true)
     * @Groups({"api"})
     */
    private $pcvDoses;

    /**
     * @var PCVType $pcvType
     * @ORM\Column(name="pcvType",type="PCVType",nullable=true)
     * @Groups({"api"})
     */
    private $pcvType;

    /**
     * @var \DateTime $pcvMostRecentDose
     * @ORM\Column(name="pcvMostRecentDose",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $pcvMostRecentDose;

    /**
     * @var VaccinationReceived $meningReceived
     * @ORM\Column(name="meningReceived",type="VaccinationReceived",nullable=true)
     * @Groups({"api"})
     */
    private $meningReceived;

    /**
     * @var MeningitisVaccinationType $meningType
     * @ORM\Column(name="meningType",type="MeningitisVaccinationType",nullable=true)
     * @Groups({"api"})
     */
    private $meningType;

    /**
     * @var \DateTime $meningMostRecentDose
     * @ORM\Column(name="meningMostRecentDose",type="date",nullable=true)
     * @Assert\Date
     * @Groups({"api"})
     */
    private $meningMostRecentDose;

//Case-based Specimen Collection Data

    /**
     * @var TripleChoice $csfCollected
     * @ORM\Column(name="csfCollected",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfCollected;

    /**
     * @var \DateTime $csfCollectDateTime
     * @ORM\Column(name="csfCollectDateTime",type="datetime",nullable=true)
     * @Groups({"api"})
     */
    private $csfCollectDateTime;

    /**
     * @var CSFAppearance $csfAppearance
     * @ORM\Column(name="csfAppearance",type="CSFAppearance",nullable=true)
     * @Groups({"api"})
     */
    private $csfAppearance;

    /**
     * @var TripleChoice $bloodCollected
     * @ORM\Column(name="bloodCollected", type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodCollected;

    /**
     * @var OtherSpecimen $otherSpecimenCollected
     * @ORM\Column(name="otherSpecimenCollected",type="OtherSpecimen",nullable=true)
     * @Groups({"api"})
     */
    private $otherSpecimenCollected;

    /**
     * @var string $otherSpecimenOther
     * @ORM\Column(name="otherSpecimenOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $otherSpecimenOther;

//Case-based Outcome Data
    /**
     * @var DischargeOutcome $dischOutcome
     * @ORM\Column(name="dischOutcome",type="DischargeOutcome",nullable=true)
     * @Groups({"api"})
     */
    private $dischOutcome;

    /**
     * @var Diagnosis $dischDx
     * @ORM\Column(name="dischDx",type="Diagnosis",nullable=true)
     * @Groups({"api"})
     */
    private $dischDx;

    /**
     * @var $dischDxOther
     * @ORM\Column(name="dischDxOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $dischDxOther;

    /**
     * @var DischargeClassification $dischClass
     * @ORM\Column(name="dischClass",type="DischargeClassification",nullable=true)
     * @Groups({"api"})
     */
    private $dischClass;

    /**
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Groups({"api"})
     */
    private $comment;

    /**
     * @var IBDCaseResult $result
     * @ORM\Column(name="result",type="IBDCaseResult")
     * @Groups({"api"})
     */
    private $result;

    public function __construct()
    {
        parent::__construct();
        $this->result = new IBDCaseResult(IBDCaseResult::UNKNOWN);
    }

    public function getDistrict()
    {
        return $this->district;
    }

    public function getOnsetDate()
    {
        return $this->onsetDate;
    }

    public function getAdmDx()
    {
        return $this->admDx;
    }

    public function getAdmDxOther()
    {
        return $this->admDxOther;
    }

    public function getAntibiotics()
    {
        return $this->antibiotics;
    }

    public function getMenSeizures()
    {
        return $this->menSeizures;
    }

    public function getMenFever()
    {
        return $this->menFever;
    }

    public function getMenAltConscious()
    {
        return $this->menAltConscious;
    }

    public function getMenInabilityFeed()
    {
        return $this->menInabilityFeed;
    }

    public function getMenNeckStiff()
    {
        return $this->menNeckStiff;
    }

    public function getMenRash()
    {
        return $this->menRash;
    }

    public function getMenFontanelleBulge()
    {
        return $this->menFontanelleBulge;
    }

    public function getMenLethargy()
    {
        return $this->menLethargy;
    }

    public function getPneuDiffBreathe()
    {
        return $this->pneuDiffBreathe;
    }

    public function getPneuChestIndraw()
    {
        return $this->pneuChestIndraw;
    }

    public function getPneuCough()
    {
        return $this->pneuCough;
    }

    public function getPneuCyanosis()
    {
        return $this->pneuCyanosis;
    }

    public function getPneuStridor()
    {
        return $this->pneuStridor;
    }

    public function getPneuRespRate()
    {
        return $this->pneuRespRate;
    }

    public function getPneuVomit()
    {
        return $this->pneuVomit;
    }

    public function getPneuHypothermia()
    {
        return $this->pneuHypothermia;
    }

    public function getPneuMalnutrition()
    {
        return $this->pneuMalnutrition;
    }

    public function getCxrDone()
    {
        return $this->cxrDone;
    }

    public function getCxrResult()
    {
        return $this->cxrResult;
    }

    public function getCxrAdditionalResult()
    {
        return $this->cxrAdditionalResult;
    }

    public function getHibReceived()
    {
        return $this->hibReceived;
    }

    public function getHibDoses()
    {
        return $this->hibDoses;
    }

    public function getHibMostRecentDose()
    {
        return $this->hibMostRecentDose;
    }

    public function getPcvReceived()
    {
        return $this->pcvReceived;
    }

    public function getPcvDoses()
    {
        return $this->pcvDoses;
    }

    public function getPcvType()
    {
        return $this->pcvType;
    }

    public function getPcvMostRecentDose()
    {
        return $this->pcvMostRecentDose;
    }

    public function getMeningReceived()
    {
        return $this->meningReceived;
    }

    public function getMeningType()
    {
        return $this->meningType;
    }

    public function getMeningMostRecentDose()
    {
        return $this->meningMostRecentDose;
    }

    public function getCsfCollected()
    {
        return $this->csfCollected;
    }

    public function getCsfCollectDateTime()
    {
        return $this->csfCollectDateTime;
    }

    public function getCsfAppearance()
    {
        return $this->csfAppearance;
    }

    public function getBloodCollected()
    {
        return $this->bloodCollected;
    }

    public function getOtherSpecimenCollected()
    {
        return $this->otherSpecimenCollected;
    }

    public function getOtherSpecimenOther()
    {
        return $this->otherSpecimenOther;
    }

    public function getDischOutcome()
    {
        return $this->dischOutcome;
    }

    public function getDischDx()
    {
        return $this->dischDx;
    }

    public function getDischDxOther()
    {
        return $this->dischDxOther;
    }

    public function getDischClass()
    {
        return $this->dischClass;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    public function setOnsetDate($onsetDate)
    {
        if ($onsetDate instanceof \DateTime)
            $this->onsetDate = $onsetDate;

        return $this;
    }

    public function setAdmDx(Diagnosis $admDx)
    {
        $this->admDx = $admDx;
        return $this;
    }

    public function setAdmDxOther($admDxOther)
    {
        $this->admDxOther = $admDxOther;
        return $this;
    }

    public function setAntibiotics(TripleChoice $antibiotics)
    {
        $this->antibiotics = $antibiotics;
        return $this;
    }

    public function setMenSeizures(TripleChoice $menSeizures)
    {
        $this->menSeizures = $menSeizures;
        return $this;
    }

    public function setMenFever(TripleChoice $menFever)
    {
        $this->menFever = $menFever;
        return $this;
    }

    public function setMenAltConscious(TripleChoice $menAltConscious)
    {
        $this->menAltConscious = $menAltConscious;
        return $this;
    }

    public function setMenInabilityFeed(TripleChoice $menInabilityFeed)
    {
        $this->menInabilityFeed = $menInabilityFeed;
        return $this;
    }

    public function setMenNeckStiff(TripleChoice $menNeckStiff)
    {
        $this->menNeckStiff = $menNeckStiff;
        return $this;
    }

    public function setMenRash(TripleChoice $menRash)
    {
        $this->menRash = $menRash;
        return $this;
    }

    public function setMenFontanelleBulge(TripleChoice $menFontanelleBulge)
    {
        $this->menFontanelleBulge = $menFontanelleBulge;
        return $this;
    }

    public function setMenLethargy(TripleChoice $menLethargy)
    {
        $this->menLethargy = $menLethargy;
        return $this;
    }

    public function setPneuDiffBreathe(TripleChoice $pneuDiffBreathe)
    {
        $this->pneuDiffBreathe = $pneuDiffBreathe;
        return $this;
    }

    public function setPneuChestIndraw(TripleChoice $pneuChestIndraw)
    {
        $this->pneuChestIndraw = $pneuChestIndraw;
        return $this;
    }

    public function setPneuCough(TripleChoice $pneuCough)
    {
        $this->pneuCough = $pneuCough;
        return $this;
    }

    public function setPneuCyanosis(TripleChoice $pneuCyanosis)
    {
        $this->pneuCyanosis = $pneuCyanosis;
        return $this;
    }

    public function setPneuStridor(TripleChoice $pneuStridor)
    {
        $this->pneuStridor = $pneuStridor;
        return $this;
    }

    public function setPneuRespRate($pneuRespRate)
    {
        $this->pneuRespRate = $pneuRespRate;
        return $this;
    }

    public function setPneuVomit(TripleChoice $pneuVomit)
    {
        $this->pneuVomit = $pneuVomit;
        return $this;
    }

    public function setPneuHypothermia(TripleChoice $pneuHypothermia)
    {
        $this->pneuHypothermia = $pneuHypothermia;
        return $this;
    }

    public function setPneuMalnutrition(TripleChoice $pneuMalnutrition)
    {
        $this->pneuMalnutrition = $pneuMalnutrition;
        return $this;
    }

    public function setCxrDone(TripleChoice $cxrDone)
    {
        $this->cxrDone = $cxrDone;
        return $this;
    }

    public function setCxrResult(CXRResult $cxrResult)
    {
        $this->cxrResult = $cxrResult;
        return $this;
    }

    public function setCxrAdditionalResult(CXRAdditionalResult $cxrAdditionalResult)
    {
        $this->cxrAdditionalResult = $cxrAdditionalResult;
        return $this;
    }

    public function setHibReceived(VaccinationReceived $hibReceived)
    {
        $this->hibReceived = $hibReceived;
        return $this;
    }

    public function setHibDoses(FourDoses $hibDoses)
    {
        $this->hibDoses = $hibDoses;
        return $this;
    }

    public function setHibMostRecentDose($hibMostRecentDose)
    {
        if ($hibMostRecentDose instanceof \DateTime)
            $this->hibMostRecentDose = $hibMostRecentDose;

        return $this;
    }

    public function setPcvReceived(VaccinationReceived $pcvReceived)
    {
        $this->pcvReceived = $pcvReceived;
        return $this;
    }

    public function setPcvDoses(ThreeDoses $pcvDoses)
    {
        $this->pcvDoses = $pcvDoses;
        return $this;
    }

    public function setPcvType(PCVType $pcvType)
    {
        $this->pcvType = $pcvType;
        return $this;
    }

    public function setPcvMostRecentDose($pcvMostRecentDose)
    {
        if ($pcvMostRecentDose instanceof \DateTime)
            $this->pcvMostRecentDose = $pcvMostRecentDose;

        return $this;
    }

    public function setMeningReceived(VaccinationReceived $meningReceived)
    {
        $this->meningReceived = $meningReceived;
        return $this;
    }

    public function setMeningType(MeningitisVaccinationType $meningType)
    {
        $this->meningType = $meningType;
        return $this;
    }

    public function setMeningMostRecentDose($meningMostRecentDose)
    {
        if ($meningMostRecentDose instanceof \DateTime)
            $this->meningMostRecentDose = $meningMostRecentDose;

        return $this;
    }

    public function setCsfCollected(TripleChoice $csfCollected)
    {
        $this->csfCollected = $csfCollected;
        return $this;
    }

    public function setCsfCollectDateTime($csfCollectDateTime)
    {
        if ($csfCollectDateTime instanceof \DateTime)
            $this->csfCollectDateTime = $csfCollectDateTime;

        return $this;
    }

    public function setCsfAppearance(CSFAppearance $csfAppearance)
    {
        $this->csfAppearance = $csfAppearance;
        return $this;
    }

    public function setBloodCollected(TripleChoice $bloodCollected)
    {
        $this->bloodCollected = $bloodCollected;
        return $this;
    }

    public function setOtherSpecimenCollected(OtherSpecimen $otherSpecimenCollected)
    {
        $this->otherSpecimenCollected = $otherSpecimenCollected;
        return $this;
    }

    public function setOtherSpecimenOther($otherSpecimenOther)
    {
        $this->otherSpecimenOther = $otherSpecimenOther;
        return $this;
    }

    public function setDischOutcome(DischargeOutcome $dischOutcome)
    {
        $this->dischOutcome = $dischOutcome;
        return $this;
    }

    public function setDischDx(Diagnosis $dischDx)
    {
        $this->dischDx = $dischDx;
        return $this;
    }

    public function setDischDxOther($dischDxOther)
    {
        $this->dischDxOther = $dischDxOther;
        return $this;
    }

    public function setDischClass(DischargeClassification $dischClass)
    {
        $this->dischClass = $dischClass;
        return $this;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function setResult(IBDCaseResult $result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Suspected: 0-59 months, with fever, one of the following: stiff neck, altered conciousness and no other sign
     *              OR
     *            Every patient 0-59 months hospitalized with clinical diagnosis of meningitis
     *
     * Probable: Suspected + CSF examination as one of the following
     *              - Turbid appearance
     *              - Leukocytosis ( > 100 cells/mm3)
     *              - Leukocytosis ( 10-100 cells/mm3) AND either elevated protein (> 100mg/dl) or decreased glucose (< 400 mg/dl)
     * Confirmed: Suspected + culture or (Gram stain, antigen detection, immunochromotagraphy, PCR or other methods)
     *            a bacterial pathogen (Hib, pneumococcus or meningococcus) in the CSF or from the blood in a child with a clinical
     *            syndrome consisten with bacterial meningitis
     *
     */
    public function calculateResult()
    {
        if($this->status->getValue() >= CaseStatus::CANCELLED)
            return;

        // Test Suspected
        if($this->age < 60 && $this->menFever && $this->menFever->equal(TripleChoice::YES))
        {
            if(($this->menAltConscious && $this->menAltConscious->equal(TripleChoice::YES)) || ($this->menNeckStiff && $this->menNeckStiff->equal(TripleChoice::YES)) )
                $this->result->setValue (IBDCaseResult::SUSPECTED);
        }
        else if($this->age < 60 && $this->admDx && $this->admDx->equal(Diagnosis::SUSPECTED_MENINGITIS))
            $this->result->setValue (IBDCaseResult::SUSPECTED);

        if($this->result && $this->result->equal(IBDCaseResult::SUSPECTED))
        {
            // Probable
            if($this->csfAppearance && $this->csfAppearance->equal(CSFAppearance::TURBID))
                $this->result->setValue (IBDCaseResult::PROBABLE);
            else
            {
                if ($this->getSiteLab())
                {
                    $lab = $this->getSiteLab();
                    if (($lab->getCsfWcc() > 10 && $lab->getCsfWcc() <= 100) && ( ($lab->getCsfGlucose() >= 0 && $lab->getCsfGlucose() < 40) || ($lab->getCsfProtein() > 100)))
                        $this->result->setValue(IBDCaseResult::PROBABLE);
                    else
                        $this->result->setValue(IBDCaseResult::CONFIRMED);
                }
            }
            // Confirmed
        }
    }

    public function getIncompleteField()
    {
        foreach($this->getMinimumRequiredFields() as $field)
        {
            if(is_null($this->$field) || empty($this->$field) || ($this->$field instanceof ArrayChoice && $this->$field->equal(-1)) )
                return $field;
        }

        // this isn't covered by the above loop because its valid for age == 0 but 0 == empty
        if(is_null($this->age))
            return 'age';

        if($this->admDx && $this->admDx->equal(Diagnosis::OTHER) && empty($this->admDxOther))
            return 'admDx';

        if($this->dischDx && $this->dischDx->equal(Diagnosis::OTHER) && empty($this->dischDxOther))
            return 'dischDx';

        if ($this->hibReceived && ($this->hibReceived->equal(VaccinationReceived::YES_HISTORY) || $this->hibReceived->equal(VaccinationReceived::YES_CARD)) && (is_null($this->hibDoses) || $this->hibDoses->equal(ArrayChoice::NO_SELECTION)))
            return 'hibReceived';

        if ($this->pcvReceived && ($this->pcvReceived->equal(VaccinationReceived::YES_HISTORY) || $this->pcvReceived->equal(VaccinationReceived::YES_CARD)) && (is_null($this->pcvDoses) || $this->pcvDoses->equal(ArrayChoice::NO_SELECTION)))
            return 'pcvReceived';

        if($this->cxrDone && $this->cxrDone->equal(TripleChoice::YES) && (is_null($this->cxrResult) || $this->cxrResult->equal(ArrayChoice::NO_SELECTION)))
            return 'cxrDone';

        if($this->meningReceived && ($this->meningReceived->equal(MeningitisVaccinationReceived::YES_CARD ) || $this->meningReceived->equal(MeningitisVaccinationReceived::YES_HISTORY)))
        {
            if(is_null($this->meningType))
                return 'meningType1';

            if($this->meningType->equal(ArrayChoice::NO_SELECTION))
                return 'meningType2';

            if(is_null($this->meningMostRecentDose))
                return 'meningMostRecentDose';
        }

        if($this->csfCollected && $this->csfCollected->equal(TripleChoice::YES))
        {
//            if(is_null($this->csfId))
//                return 'csfCollected1';
//            if(empty($this->csfId))
//                return 'csfCollected2';
            if(is_null($this->csfCollectDateTime))
                return 'csfCollectDateTime';
            if(is_null($this->csfAppearance))
                return 'csfAppearance1';
            if($this->csfAppearance->equal(ArrayChoice::NO_SELECTION))
                return 'csfAppearance2';
        }

        if($this->otherSpecimenCollected && $this->otherSpecimenCollected->equal(OtherSpecimen::OTHER) && empty($this->otherSpecimenOther))
            return 'otherSpecimenOther';

        return null;
    }

    public function getMinimumRequiredFields()
    {
        $fields = array(
                    'caseId',
                    'dob',
                    'gender',
                    'admDate',
                    'onsetDate',
                    'admDx',
                    'antibiotics',
                    'menSeizures',
                    'menFever',
                    'menAltConscious',
                    'menInabilityFeed',
                    'menNeckStiff',
                    'menRash',
                    'menFontanelleBulge',
                    'menLethargy',
                    'hibReceived',
                    'pcvReceived',
                    'meningReceived',
                    'csfCollected',
                    'bloodCollected',
                    'otherSpecimenCollected',
                    'dischOutcome',
                    'dischDx',
                    'dischClass',
                    'cxrDone',
                    );

        return (!$this->country || ($this->country && $this->country->getTracksPneumonia())) ? array_merge($fields,$this->getPneumiaRequiredFields()) : $fields;
    }

    public function getPneumiaRequiredFields()
    {
        return array('pneuDiffBreathe',
                     'pneuChestIndraw',
                     'pneuCough',
                     'pneuCyanosis',
                     'pneuStridor',
                     'pneuRespRate',
                     'pneuVomit',
                     'pneuHypothermia',
                     'pneuMalnutrition',);
    }

    public function validate(ExecutionContextInterface $context)
    {
        // with both an admission date and onset date, ensure the admission happened after onset
        if($this->admDate && $this->onsetDate && $this->admDate < $this->onsetDate)
            $context->addViolationAt('admDate', "form.validation.admission-after-onset");

        // with both an dob and onset date, ensure the onset is after dob
        if($this->dob && $this->onsetDate && $this->onsetDate < $this->dob)
            $context->addViolationAt ('dob', "form.validation.onset-after-dob");

// The following validations need to store errors in the object or force form validation prior to form submission
//        // if admission diagnosis is other, enforce value in 'admission diagnosis other' field
//        if($this->admDx && $this->admDx->equal(Diagnosis::OTHER) && empty($this->admDxOther))
//            $context->addViolationAt('admDx',"form.validation.admissionDx-other-without-other-text");
//
//        // if discharge diagnosis is other, enforce value in 'discharge diagnosis other' field
//        if($this->dischDx && $this->dischDx->equal(Diagnosis::OTHER) && empty($this->dischDxOther))
//            $context->addViolationAt('dischDx',"form.validation.dischargeDx-other-without-other-text");
//
//        if($this->hibReceived && $this->hibReceived->equal(TripleChoice::YES) && (is_null($this->hibDoses) || $this->hibDoses->equal(ArrayChoice::NO_SELECTION)))
//            $context->addViolationAt('hibDoses', "form.validation.hibReceived-other-hibDoses-unselected");
//
//        if($this->pcvReceived && $this->pcvReceived->equal(TripleChoice::YES) && (is_null($this->pcvDoses) || $this->pcvDoses->equal(ArrayChoice::NO_SELECTION)))
//            $context->addViolationAt('pcvDoses', "form.validation.pcvReceived-other-pcvDoses-unselected '".$this->pcvReceived."'" );
//
//        if($this->meningReceived && ($this->meningReceived->equal(MeningitisVaccinationReceived::YES_CARD ) || $this->meningReceived->equal(MeningitisVaccinationReceived::YES_HISTORY)))
//        {
//            if(is_null($this->meningType))
//                $context->addViolationAt('meningType', "form.validation.meningReceived-meningType-empty");
//
//            if($this->meningType->equal(ArrayChoice::NO_SELECTION))
//                $context->addViolationAt('meningType', "form.validation.meningReceived-meningType-empty");
//
//            if(is_null($this->meningMostRecentDose))
//                $context->addViolationAt('meningType', "form.validation.meningReceived-meningMostRecentDose-empty");
//        }
//
//        if($this->csfCollected && $this->csfCollected->equal(TripleChoice::YES))
//        {
//            if(is_null($this->csfId) || empty($this->csfId))
//                $context->addViolationAt('csfId', "form.validation.csfCollected-csfId-empty");
//
//            if(is_null($this->csfCollectDateTime))
//                $context->addViolationAt('csfId', "form.validation.csfCollected-csfCollectDateTime-empty");
//
//            if(is_null($this->csfAppearance) || $this->csfAppearance->equal(ArrayChoice::NO_SELECTION))
//                $context->addViolationAt('csfId', "form.validation.csfCollected-csfAppearance-empty");
//        }
    }
}
