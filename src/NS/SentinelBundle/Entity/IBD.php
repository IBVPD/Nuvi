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
use \NS\SentinelBundle\Form\Types\DischargeDiagnosis;
use \NS\SentinelBundle\Form\Types\DischargeOutcome;
use \NS\SentinelBundle\Form\Types\FourDoses;
use \NS\SentinelBundle\Form\Types\IBDCaseResult;
use \NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use \NS\SentinelBundle\Form\Types\MeningitisVaccinationType;
use \NS\SentinelBundle\Form\Types\OtherSpecimen;
use \NS\SentinelBundle\Form\Types\PCVType;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\VaccinationReceived;
use \Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Validator\ExecutionContextInterface;
use \NS\SentinelBundle\Validators as LocalAssert;

/**
 * Description of IBD
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBDRepository")
 * @ORM\Table(name="ibd_cases",uniqueConstraints={@ORM\UniqueConstraint(name="ibd_site_case_id_idx",columns={"site_id","caseId"})})
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\IBDListener"})
 *
 * @LocalAssert\GreaterThanDate(lessThanField="onsetDate",greaterThanField="admDate",message="form.validation.admission-after-onset")
 * @LocalAssert\GreaterThanDate(lessThanField="dob",greaterThanField="onsetDate",message="form.validation.onset-after-dob")
 */
class IBD extends BaseCase
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD\NationalLab", mappedBy="caseFile",cascade={"persist"})
     */
    protected $nationalLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD\ReferenceLab", mappedBy="caseFile",cascade={"persist"})
     */
    protected $referenceLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD\SiteLab", mappedBy="caseFile",cascade={"persist"})
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
     * @var FourDoses $pcvDoses
     * @ORM\Column(name="pcvDoses",type="FourDoses",nullable=true)
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
     * @var \DateTime $bloodCollectDate
     * @ORM\Column(name="bloodCollectDate",type="date",nullable=true)
     */
    private $bloodCollectDate;

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
     * @ORM\Column(name="dischDx",type="DischargeDiagnosis",nullable=true)
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
     * @var string $dischClassOther
     * @ORM\Column(name="dischClassOther",type="string",nullable=true)
     */
    private $dischClassOther;

    /**
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Groups({"api"})
     */
    private $comment;

    /**
     * @var IBDCaseResult $result
     * @ORM\Column(name="result",type="IBDCaseResult",nullable=true)
     * @Groups({"api"})
     */
    private $result;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->result = new IBDCaseResult(IBDCaseResult::UNKNOWN);
    }

    /**
     * @return \DateTime
     */
    public function getOnsetDate()
    {
        return $this->onsetDate;
    }

    /**
     * @return Diagnosis
     */
    public function getAdmDx()
    {
        return $this->admDx;
    }

    /**
     * @return string
     */
    public function getAdmDxOther()
    {
        return $this->admDxOther;
    }

    /**
     * @return TripleChoice
     */
    public function getAntibiotics()
    {
        return $this->antibiotics;
    }

    /**
     * @return TripleChoice
     */
    public function getMenSeizures()
    {
        return $this->menSeizures;
    }

    /**
     * @return TripleChoice
     */
    public function getMenFever()
    {
        return $this->menFever;
    }

    /**
     * @return TripleChoice
     */
    public function getMenAltConscious()
    {
        return $this->menAltConscious;
    }

    /**
     * @return TripleChoice
     */
    public function getMenInabilityFeed()
    {
        return $this->menInabilityFeed;
    }

    /**
     * @return TripleChoice
     */
    public function getMenNeckStiff()
    {
        return $this->menNeckStiff;
    }

    /**
     * @return TripleChoice
     */
    public function getMenRash()
    {
        return $this->menRash;
    }

    /**
     * @return TripleChoice
     */
    public function getMenFontanelleBulge()
    {
        return $this->menFontanelleBulge;
    }

    /**
     * @return TripleChoice
     */
    public function getMenLethargy()
    {
        return $this->menLethargy;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuDiffBreathe()
    {
        return $this->pneuDiffBreathe;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuChestIndraw()
    {
        return $this->pneuChestIndraw;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuCough()
    {
        return $this->pneuCough;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuCyanosis()
    {
        return $this->pneuCyanosis;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuStridor()
    {
        return $this->pneuStridor;
    }

    /**
     * @return int
     */
    public function getPneuRespRate()
    {
        return $this->pneuRespRate;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuVomit()
    {
        return $this->pneuVomit;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuHypothermia()
    {
        return $this->pneuHypothermia;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuMalnutrition()
    {
        return $this->pneuMalnutrition;
    }

    /**
     * @return TripleChoice
     */
    public function getCxrDone()
    {
        return $this->cxrDone;
    }

    /**
     * @return CXRResult
     */
    public function getCxrResult()
    {
        return $this->cxrResult;
    }

    /**
     * @return CXRAdditionalResult
     */
    public function getCxrAdditionalResult()
    {
        return $this->cxrAdditionalResult;
    }

    /**
     * @return VaccinationReceived
     */
    public function getHibReceived()
    {
        return $this->hibReceived;
    }

    /**
     * @return FourDoses
     */
    public function getHibDoses()
    {
        return $this->hibDoses;
    }

    /**
     * @return \DateTime
     */
    public function getHibMostRecentDose()
    {
        return $this->hibMostRecentDose;
    }

    /**
     * @return VaccinationReceived
     */
    public function getPcvReceived()
    {
        return $this->pcvReceived;
    }

    /**
     * @return FourDoses
     */
    public function getPcvDoses()
    {
        return $this->pcvDoses;
    }

    /**
     * @return PCVType
     */
    public function getPcvType()
    {
        return $this->pcvType;
    }

    /**
     * @return \DateTime
     */
    public function getPcvMostRecentDose()
    {
        return $this->pcvMostRecentDose;
    }

    /**
     * @return VaccinationReceived
     */
    public function getMeningReceived()
    {
        return $this->meningReceived;
    }

    /**
     * @return MeningitisVaccinationType
     */
    public function getMeningType()
    {
        return $this->meningType;
    }

    /**
     * @return \DateTime
     */
    public function getMeningMostRecentDose()
    {
        return $this->meningMostRecentDose;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfCollected()
    {
        return $this->csfCollected;
    }

    /**
     * @return \DateTime
     */
    public function getCsfCollectDateTime()
    {
        return $this->csfCollectDateTime;
    }

    /**
     * @return CSFAppearance
     */
    public function getCsfAppearance()
    {
        return $this->csfAppearance;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodCollected()
    {
        return $this->bloodCollected;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getBloodCollectDate()
    {
        return $this->bloodCollectDate;
    }

    /**
     * @return OtherSpecimen
     */
    public function getOtherSpecimenCollected()
    {
        return $this->otherSpecimenCollected;
    }

    /**
     * @return string
     */
    public function getOtherSpecimenOther()
    {
        return $this->otherSpecimenOther;
    }

    /**
     * @return DischargeOutcome
     */
    public function getDischOutcome()
    {
        return $this->dischOutcome;
    }

    /**
     * @return Diagnosis
     */
    public function getDischDx()
    {
        return $this->dischDx;
    }

    /**
     * @return mixed
     */
    public function getDischDxOther()
    {
        return $this->dischDxOther;
    }

    /**
     * @return DischargeClassification
     */
    public function getDischClass()
    {
        return $this->dischClass;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return IBDCaseResult
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param \DateTime|null $onsetDate
     * @return $this
     */
    public function setOnsetDate(\DateTime $onsetDate = null)
    {
        $this->onsetDate = $onsetDate;
        return $this;
    }

    /**
     * @param Diagnosis $admDx
     * @return $this
     */
    public function setAdmDx(Diagnosis $admDx = null)
    {
        $this->admDx = $admDx;
        return $this;
    }

    /**
     * @param $admDxOther
     * @return $this
     */
    public function setAdmDxOther($admDxOther)
    {
        $this->admDxOther = $admDxOther;
        return $this;
    }

    /**
     * @param TripleChoice $antibiotics
     * @return $this
     */
    public function setAntibiotics(TripleChoice $antibiotics = null)
    {
        $this->antibiotics = $antibiotics;
        return $this;
    }

    /**
     * @param TripleChoice $menSeizures
     * @return $this
     */
    public function setMenSeizures(TripleChoice $menSeizures = null)
    {
        $this->menSeizures = $menSeizures;
        return $this;
    }

    /**
     * @param TripleChoice $menFever
     * @return $this
     */
    public function setMenFever(TripleChoice $menFever = null)
    {
        $this->menFever = $menFever;
        return $this;
    }

    /**
     * @param TripleChoice $menAltConscious
     * @return $this
     */
    public function setMenAltConscious(TripleChoice $menAltConscious = null)
    {
        $this->menAltConscious = $menAltConscious;
        return $this;
    }

    /**
     * @param TripleChoice $menInabilityFeed
     * @return $this
     */
    public function setMenInabilityFeed(TripleChoice $menInabilityFeed = null)
    {
        $this->menInabilityFeed = $menInabilityFeed;
        return $this;
    }

    /**
     * @param TripleChoice $menNeckStiff
     * @return $this
     */
    public function setMenNeckStiff(TripleChoice $menNeckStiff = null)
    {
        $this->menNeckStiff = $menNeckStiff;
        return $this;
    }

    /**
     * @param TripleChoice $menRash
     * @return $this
     */
    public function setMenRash(TripleChoice $menRash = null)
    {
        $this->menRash = $menRash;
        return $this;
    }

    /**
     * @param TripleChoice $menFontanelleBulge
     * @return $this
     */
    public function setMenFontanelleBulge(TripleChoice $menFontanelleBulge = null)
    {
        $this->menFontanelleBulge = $menFontanelleBulge;
        return $this;
    }

    /**
     * @param TripleChoice $menLethargy
     * @return $this
     */
    public function setMenLethargy(TripleChoice $menLethargy = null)
    {
        $this->menLethargy = $menLethargy;
        return $this;
    }

    /**
     * @param TripleChoice $pneuDiffBreathe
     * @return $this
     */
    public function setPneuDiffBreathe(TripleChoice $pneuDiffBreathe = null)
    {
        $this->pneuDiffBreathe = $pneuDiffBreathe;
        return $this;
    }

    /**
     * @param TripleChoice $pneuChestIndraw
     * @return $this
     */
    public function setPneuChestIndraw(TripleChoice $pneuChestIndraw = null)
    {
        $this->pneuChestIndraw = $pneuChestIndraw;
        return $this;
    }

    /**
     * @param TripleChoice $pneuCough
     * @return $this
     */
    public function setPneuCough(TripleChoice $pneuCough = null)
    {
        $this->pneuCough = $pneuCough;
        return $this;
    }

    /**
     * @param TripleChoice $pneuCyanosis
     * @return $this
     */
    public function setPneuCyanosis(TripleChoice $pneuCyanosis = null)
    {
        $this->pneuCyanosis = $pneuCyanosis;
        return $this;
    }

    /**
     * @param TripleChoice $pneuStridor
     * @return $this
     */
    public function setPneuStridor(TripleChoice $pneuStridor = null)
    {
        $this->pneuStridor = $pneuStridor;
        return $this;
    }

    /**
     * @param $pneuRespRate
     * @return $this
     */
    public function setPneuRespRate($pneuRespRate)
    {
        $this->pneuRespRate = $pneuRespRate;
        return $this;
    }

    /**
     * @param TripleChoice $pneuVomit
     * @return $this
     */
    public function setPneuVomit(TripleChoice $pneuVomit = null)
    {
        $this->pneuVomit = $pneuVomit;
        return $this;
    }

    /**
     * @param TripleChoice $pneuHypothermia
     * @return $this
     */
    public function setPneuHypothermia(TripleChoice $pneuHypothermia = null)
    {
        $this->pneuHypothermia = $pneuHypothermia;
        return $this;
    }

    /**
     * @param TripleChoice $pneuMalnutrition
     * @return $this
     */
    public function setPneuMalnutrition(TripleChoice $pneuMalnutrition = null)
    {
        $this->pneuMalnutrition = $pneuMalnutrition;
        return $this;
    }

    /**
     * @param TripleChoice $cxrDone
     * @return $this
     */
    public function setCxrDone(TripleChoice $cxrDone = null)
    {
        $this->cxrDone = $cxrDone;
        return $this;
    }

    /**
     * @param CXRResult $cxrResult
     * @return $this
     */
    public function setCxrResult(CXRResult $cxrResult = null)
    {
        $this->cxrResult = $cxrResult;
        return $this;
    }

    /**
     * @param CXRAdditionalResult $cxrAdditionalResult
     * @return $this
     */
    public function setCxrAdditionalResult(CXRAdditionalResult $cxrAdditionalResult = null)
    {
        $this->cxrAdditionalResult = $cxrAdditionalResult;
        return $this;
    }

    /**
     * @param VaccinationReceived $hibReceived
     * @return $this
     */
    public function setHibReceived(VaccinationReceived $hibReceived = null)
    {
        $this->hibReceived = $hibReceived;
        return $this;
    }

    /**
     * @param FourDoses $hibDoses
     * @return $this
     */
    public function setHibDoses(FourDoses $hibDoses = null)
    {
        $this->hibDoses = $hibDoses;
        return $this;
    }

    /**
     * @param $hibMostRecentDose
     * @return $this
     */
    public function setHibMostRecentDose(\DateTime $hibMostRecentDose = null)
    {
        $this->hibMostRecentDose = $hibMostRecentDose;

        return $this;
    }

    /**
     * @param VaccinationReceived $pcvReceived
     * @return $this
     */
    public function setPcvReceived(VaccinationReceived $pcvReceived = null)
    {
        $this->pcvReceived = $pcvReceived;
        return $this;
    }

    /**
     * @param FourDoses $pcvDoses
     * @return $this
     */
    public function setPcvDoses(FourDoses $pcvDoses = null)
    {
        $this->pcvDoses = $pcvDoses;
        return $this;
    }

    /**
     * @param PCVType $pcvType
     * @return $this
     */
    public function setPcvType(PCVType $pcvType = null)
    {
        $this->pcvType = $pcvType;
        return $this;
    }

    /**
     * @param $pcvMostRecentDose
     * @return $this
     */
    public function setPcvMostRecentDose(\DateTime $pcvMostRecentDose = null)
    {
        $this->pcvMostRecentDose = $pcvMostRecentDose;

        return $this;
    }

    /**
     * @param VaccinationReceived $meningReceived
     * @return $this
     */
    public function setMeningReceived(VaccinationReceived $meningReceived = null)
    {
        $this->meningReceived = $meningReceived;
        return $this;
    }

    /**
     * @param MeningitisVaccinationType $meningType
     * @return $this
     */
    public function setMeningType(MeningitisVaccinationType $meningType = null)
    {
        $this->meningType = $meningType;
        return $this;
    }

    /**
     * @param $meningMostRecentDose
     * @return $this
     */
    public function setMeningMostRecentDose(\DateTime $meningMostRecentDose = null)
    {
        $this->meningMostRecentDose = $meningMostRecentDose;

        return $this;
    }

    /**
     * @param TripleChoice $csfCollected
     * @return $this
     */
    public function setCsfCollected(TripleChoice $csfCollected = null)
    {
        $this->csfCollected = $csfCollected;
        return $this;
    }

    /**
     * @param \DateTime|null $csfCollectDateTime
     * @return $this
     */
    public function setCsfCollectDateTime(\DateTime $csfCollectDateTime = null)
    {
        $this->csfCollectDateTime = $csfCollectDateTime;
        return $this;
    }

    /**
     * @param CSFAppearance $csfAppearance
     * @return $this
     */
    public function setCsfAppearance(CSFAppearance $csfAppearance = null)
    {
        $this->csfAppearance = $csfAppearance;
        return $this;
    }

    /**
     * @param \DateTime|null $date
     * @return $this
     */
    public function setBloodCollectDate(\DateTime $date = null)
    {
        $this->bloodCollectDate = $date;

        return $this;
    }

    /**
     * @param TripleChoice $bloodCollected
     * @return $this
     */
    public function setBloodCollected(TripleChoice $bloodCollected = null)
    {
        $this->bloodCollected = $bloodCollected;
        return $this;
    }

    /**
     * @param OtherSpecimen $otherSpecimenCollected
     * @return $this
     */
    public function setOtherSpecimenCollected(OtherSpecimen $otherSpecimenCollected = null)
    {
        $this->otherSpecimenCollected = $otherSpecimenCollected;
        return $this;
    }

    /**
     * @param $otherSpecimenOther
     * @return $this
     */
    public function setOtherSpecimenOther($otherSpecimenOther)
    {
        $this->otherSpecimenOther = $otherSpecimenOther;
        return $this;
    }

    /**
     * @param DischargeOutcome $dischOutcome
     * @return $this
     */
    public function setDischOutcome(DischargeOutcome $dischOutcome = null)
    {
        $this->dischOutcome = $dischOutcome;
        return $this;
    }

    /**
     * @param DischargeDiagnosis $dischDx
     * @return $this
     */
    public function setDischDx(DischargeDiagnosis $dischDx = null)
    {
        $this->dischDx = $dischDx;
        return $this;
    }

    /**
     * @param $dischDxOther
     * @return $this
     */
    public function setDischDxOther($dischDxOther)
    {
        $this->dischDxOther = $dischDxOther;
        return $this;
    }

    /**
     * @param DischargeClassification $dischClass
     * @return $this
     */
    public function setDischClass(DischargeClassification $dischClass = null)
    {
        $this->dischClass = $dischClass;
        return $this;
    }

    /**
     * @param $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @param IBDCaseResult $result
     * @return $this
     */
    public function setResult(IBDCaseResult $result = null)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getDischClassOther()
    {
        return $this->dischClassOther;
    }

    /**
     * @param string $dischClassOther
     * @return \NS\SentinelBundle\Entity\IBD
     */
    public function setDischClassOther($dischClassOther)
    {
        $this->dischClassOther = $dischClassOther;
        return $this;
    }

//    /**
//     * @param ExecutionContextInterface $context
//     */
//    public function validate(ExecutionContextInterface $context)
//    {
//        // with both an admission date and onset date, ensure the admission happened after onset
//        if($this->admDate && $this->onsetDate && $this->admDate < $this->onsetDate)
//            $context->addViolationAt('admDate', "form.validation.admission-after-onset");
//
//        // with both an dob and onset date, ensure the onset is after dob
//        if($this->dob && $this->onsetDate && $this->onsetDate < $this->dob)
//            $context->addViolationAt ('dob', "form.validation.onset-after-dob");
//
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
//    }
}
