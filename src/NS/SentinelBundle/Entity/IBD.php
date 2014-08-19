<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\CSFAppearance;
use NS\SentinelBundle\Form\Types\CXRResult;
use NS\SentinelBundle\Form\Types\CXRAdditionalResult;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\DischargeClassification;
use NS\SentinelBundle\Form\Types\Doses;
use NS\SentinelBundle\Form\Types\PCVType;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\IBDCaseResult;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationType;
use NS\SentinelBundle\Form\Types\OtherSpecimen;
use NS\SentinelBundle\Form\Types\LatResult;
use NS\SentinelBundle\Form\Types\CultureResult;
use NS\SentinelBundle\Form\Types\GramStain;
use NS\SentinelBundle\Form\Types\GramStainOrganism;
use NS\SentinelBundle\Form\Types\BinaxResult;
use NS\SentinelBundle\Form\Types\PCRResult;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\SpnSerotype;
use NS\SentinelBundle\Form\Types\HiSerotype;
use NS\SentinelBundle\Form\Types\NmSerogroup;
use NS\SentinelBundle\Form\Types\Volume;

use NS\UtilBundle\Form\Types\ArrayChoice;

use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\AccessType;

/**
 * Description of IBD
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD")
 * @ORM\Table(name="ibd_cases",uniqueConstraints={@ORM\UniqueConstraint(name="ibd_site_case_id_idx",columns={"site_id","caseId"})})
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION","ROLE_REGION_API"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_COUNTRY_API"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_SITE_API"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @Assert\Callback(methods={"validate"})
 * @AccessType("public_method")
 */
class IBD extends BaseCase
{
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
     * @var TripleChoice $hibReceived
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
     * @var TripleChoice $pcvReceived
     * @ORM\Column(name="pcvReceived",type="VaccinationReceived",nullable=true)
     * @Groups({"api"})
     */
    private $pcvReceived;

    /**
     * @var FourDoses $pcvDoses
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
     * @var DateTime $meningMostRecentDose
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
     * @var DateTime $csfCollectDateTime
     * @ORM\Column(name="csfCollectDateTime",type="datetime",nullable=true)
     * @Groups({"api"})
     */
    private $csfCollectDateTime;

    /**
     * @var DateTime $csfAppearance
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
//LAB START
//-----------------------------------------
// CSF
    /**
     * @var string $siteCsfId
     * @ORM\Column(name="siteCsfId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfSiteId;

    /**
     * @var \DateTime $csfSiteDateTime
     * @ORM\Column(name="csfSiteDateTime",type="datetime",nullable=true)
     * @Assert\DateTime
     * @Groups({"api"})
     */
    private $csfSiteDateTime;

    /**
     * @var integer $csfWcc
     * @ORM\Column(name="csfWcc", type="integer",nullable=true)
     * @Assert\Range(min=0,max=9999,minMessage="You cannot have a negative white blood cell count",maxMessage="Invalid value")
     * @Groups({"api"})
     */
    private $csfWcc;

    /**
     * @var integer $csfGlucose
     * @ORM\Column(name="csfGlucose", type="integer",nullable=true)
     * @Assert\GreaterThanOrEqual(value=0,message="Invalid value - value must be greater than 0")
     * @Groups({"api"})
     */
    private $csfGlucose;

    /**
     * @var integer $csfProtein
     * @ORM\Column(name="csfProtein", type="integer",nullable=true)
     * @Assert\GreaterThanOrEqual(value=0,message="Invalid value - value must be greater than 0")
     * @Groups({"api"})
     */
    private $csfProtein;

    /**
     * @var TripleChoice $csfCultDone
     * @ORM\Column(name="csfCultDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfCultDone;

    /**
     * @var TripleChoice $csfGramDone
     * @ORM\Column(name="csfGramDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramDone;

    /**
     * @var TripleChoice $csfBinaxDone
     * @ORM\Column(name="csfBinaxDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfBinaxDone;

    /**
     * @var TripleChoice $csfLatDone
     * @ORM\Column(name="csfLatDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfLatDone;

    /**
     * @var TripleChoice $csfPcrDone
     * @ORM\Column(name="csfPcrDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfPcrDone;

    /**
     * @var CultureResult $csfCultResult
     * @ORM\Column(name="csfCultResult",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfCultResult;

    /**
     * @var string $csfCultOther
     * @ORM\Column(name="csfCultOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfCultOther;

    /**
     * @var GramStain
     * @ORM\Column(name="csfGramResult",type="GramStain",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramResult;

    /**
     * @var GramStainOrganism $csfGramResultOrganism
     * @ORM\Column(name="csfGramResultOrganism",type="GramStainOrganism",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramResultOrganism;

    /**
     * @var string $csfGramOther
     * @ORM\Column(name="csfGramOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramOther;

    /**
     * @var BinaxResult
     * @ORM\Column(name="csfBinaxResult",type="BinaxResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfBinaxResult;

    /**
     * @var LatResult
     * @ORM\Column(name="csfLatResult",type="LatResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfLatResult;

    /**
     * @var string
     * @ORM\Column(name="csfLatOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfLatOther;

    /**
     * @var PCRResult
     * @ORM\Column(name="csfPcrResult",type="PCRResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfPcrResult;

    /**
     * @var string $csfPcrOther
     * @ORM\Column(name="csfPcrOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfPcrOther;

    /**
     * @var TripleChoice $csfSentToNL
     * @ORM\Column(name="csfSentToNL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfSentToNL;

    /**
     * @var \DateTime $csfDateSentToNL
     * @ORM\Column(name="csfDateSentToNL",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $csfDateSentToNL;

    /**
     * @var string $csfNLId
     * @ORM\Column(name="csfNLId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfNLId;

    /**
     * @var \DateTime $csfNLDateReceived
     * @ORM\Column(name="csfNLDateReceived",type="datetime",nullable=true)
     * @Groups({"api"})
     */
    private $csfNLDateReceived;

    /**
     * @var TripleChoice $csfIsolateSentToNL
     * @ORM\Column(name="csfIsolateSentToNL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfIsolateSentToNL;

    /**
     * @var \DateTime $csfIsolateDateSentToNL
     * @ORM\Column(name="csfIsolateDateSentToNL",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $csfIsolateDateSentToNL;

    /**
     * @var \DateTime $csfIsolateNLDateReceived
     * @ORM\Column(name="csfIsolateNLDateReceived",type="datetime",nullable=true)
     * @Groups({"api"})
     */
    private $csfIsolateNLDateReceived;

    /**
     * @var TripleChoice $csfSentToRRL
     * @ORM\Column(name="csfSentToRRL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfSentToRRL;

    /**
     * @var \DateTime $csfDateSentToRRL
     * @ORM\Column(name="csfDateSentToRRL",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $csfDateSentToRRL;

    /**
     * @var string $csfRRLId
     * @ORM\Column(name="csfRRLId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfRRLId;

    /**
     * @var \DateTime $csfRRLDateReceived
     * @ORM\Column(name="csfRRLDateReceived",type="datetime",nullable=true)
     * @Groups({"api"})
     */
    private $csfRRLDateReceived;

    /**
     * @var TripleChoice $csfIsolateSentToRRL
     * @ORM\Column(name="csfIsolateSentToRRL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfIsolateSentToRRL;

    /**
     * @var \DateTime $csfIsolateDateSentToRRL
     * @ORM\Column(name="csfIsolateDateSentToRRL",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $csfIsolateDateSentToRRL;

    /**
     * @var \DateTime $csfIsolateRRLDateReceived
     * @ORM\Column(name="csfIsolateRRLDateReceived",type="datetime",nullable=true)
     * @Groups({"api"})
     */
    private $csfIsolateRRLDateReceived;

    /**
     * @var TripleChoice $csfStore
     * @ORM\Column(name="csfStore",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfStore;

    /**
     * @var TripleChoice $csfIsolStore
     * @ORM\Column(name="csfIsolStore",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfIsolStore;

    /**
     * @var Volume $csfVolume
     * @ORM\Column(name="csfVolume",type="Volume",nullable=true)
     * @Groups({"api"})
     */
    private $csfVolume;

    /**
     * @var \DateTime $csfSiteDNAExtractionDate
     * @ORM\Column(name="csfSiteDNAExtractionDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $csfSiteDNAExtractionDate;

    /**
     * @var integer $csfSiteDNAVolume
     * @ORM\Column(name="csfSiteDNAVolume",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $csfSiteDNAVolume;

    /**
     * @var \DateTime $csfNLDNAExtractionDate
     * @ORM\Column(name="csfNLDNAExtractionDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $csfNLDNAExtractionDate;

    /**
     * @var integer $csfNLDNAVolume
     * @ORM\Column(name="csfNLDNAVolume",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $csfNLDNAVolume;

    /**
     * @var \DateTime $csfRRLDNAExtractionDate
     * @ORM\Column(name="csfRRLDNAExtractionDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $csfRRLDNAExtractionDate;

    /**
     * @var integer $csfRRLDNAVolume
     * @ORM\Column(name="csfRRLDNAVolume",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $csfRRLDNAVolume;

//-----------------------------------------
// Blood
    /**
     * @var string $bloodSiteId
     * @ORM\Column(name="bloodSiteId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodSiteId;

    /**
     * @var \DateTime $bloodSiteDate
     * @ORM\Column(name="bloodSiteDate",type="date",nullable=true)
     * @Assert\DateTime
     * @Groups({"api"})
     */
    private $bloodSiteDate;

    /**
     * @var TripleChoice $bloodSentToNL
     * @ORM\Column(name="bloodSentToNL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodSentToNL;

    /**
     * @var string $bloodNLId
     * @ORM\Column(name="bloodNLId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodNLId;

    /**
     * @var \DateTime $bloodDateSentToNL
     * @ORM\Column(name="bloodDateSentToNL",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $bloodDateSentToNL;

    /**
     * @var \DateTime $bloodNLDateReceived
     * @ORM\Column(name="bloodNLDateReceived",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $bloodNLDateReceived;

    /**
     * @var TripleChoice $bloodIsolateSentToNL
     * @ORM\Column(name="bloodIsolateSentToNL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodIsolateSentToNL;

    /**
     * @var \DateTime $bloodIsolateDateSentToNL
     * @ORM\Column(name="bloodIsolateDateSentToNL",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $bloodIsolateDateSentToNL;

    /**
     * @var \DateTime $bloodIsolateNLDateReceived
     * @ORM\Column(name="bloodIsolateNLDateReceived",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $bloodIsolateNLDateReceived;

    /**
     * @var TripleChoice $bloodSentToRRL
     * @ORM\Column(name="bloodSentToRRL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodSentToRRL;

    /**
     * @var string $bloodRRLId
     * @ORM\Column(name="bloodRRLId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodRRLId;

    /**
     * @var \DateTime $bloodDateSentToRRL
     * @ORM\Column(name="bloodDateSentToRRL",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $bloodDateSentToRRL;

    /**
     * @var \DateTime $bloodRRLDateReceived
     * @ORM\Column(name="bloodRRLDateReceived",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $bloodRRLDateReceived;

    /**
     * @var TripleChoice $bloodIsolateSentToRRL
     * @ORM\Column(name="bloodIsolateSentToRRL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodIsolateSentToRRL;

    /**
     * @var \DateTime $bloodIsolateDateSentToRRL
     * @ORM\Column(name="bloodIsolateDateSentToRRL",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $bloodIsolateDateSentToRRL;

    /**
     * @var \DateTime $bloodIsolateRRLDateReceived
     * @ORM\Column(name="bloodIsolateRRLDateReceived",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $bloodIsolateRRLDateReceived;

    /**
     * @var TripleChoice $bloodCultDone
     * @ORM\Column(name="bloodCultDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodCultDone;

    /**
     * @var TripleChoice $bloodGramDone
     * @ORM\Column(name="bloodGramDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramDone;

    /**
     * @var TripleChoice $bloodPcrDone
     * @ORM\Column(name="bloodPcrDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodPcrDone;

    /**
     * @var CultureResult
     * @ORM\Column(name="bloodCultResult",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $bloodCultResult;

    /**
     * @var string
     * @ORM\Column(name="bloodCultOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodCultOther;

    /**
     * @var GramStain
     * @ORM\Column(name="bloodGramResult",type="GramStain",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramResult;

    /**
     * @var GramStainOrganism $bloodGramResultOrganism
     * @ORM\Column(name="bloodGramResultOrganism",type="GramStainOrganism",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramResultOrganism;

    /**
     * @var string $bloodGramOther
     * @ORM\Column(name="bloodGramOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramOther;

    /**
     * @var PCRResult
     * @ORM\Column(name="bloodPcrResult",type="PCRResult",nullable=true)
     * @Groups({"api"})
     */
    private $bloodPcrResult;

    /**
     * @var string $bloodPcrOther
     * @ORM\Column(name="bloodPcrOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodPcrOther;

//----------------------------------------
// Other Fluids

    /**
     * @var string $otherSiteId
     * @ORM\Column(name="otherSiteId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $otherSiteId;

    /**
     * @var string $otherNLId
     * @ORM\Column(name="otherNLId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $otherNLId;

    /**
     * @var string $otherRRLId
     * @ORM\Column(name="otherRRLId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $otherRRLId;

    /**
     * @var \DateTime $otherSiteDate
     * @ORM\Column(name="otherSiteDate",type="date",nullable=true)
     * @Assert\DateTime
     * @Groups({"api"})
     */
    private $otherSiteDate;

    /**
     * @var TripleChoice $otherSentToNL
     * @ORM\Column(name="otherSentToNL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $otherSentToNL;

    /**
     * @var \DateTime $otherDateSentToNL
     * @ORM\Column(name="otherDateSentToNL",type="date",nullable=true)
     * @Assert\Date
     * @Groups({"api"})
     */
    private $otherDateSentToNL;

    /**
     * @var \DateTime $otherNLDateReceived
     * @ORM\Column(name="otherNLDateReceived",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $otherNLDateReceived;

    /**
     * @var TripleChoice $otherSentToRRL
     * @ORM\Column(name="otherSentToRRL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $otherSentToRRL;

    /**
     * @var \DateTime $otherDateSentToRRL
     * @ORM\Column(name="otherDateSentToRRL",type="date",nullable=true)
     * @Assert\Date
     * @Groups({"api"})
     */
    private $otherDateSentToRRL;

    /**
     * @var \DateTime $otherRRLDateReceived
     * @ORM\Column(name="otherRRLDateReceived",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $otherRRLDateReceived;

    /**
     * @var TripleChoice $otherCultDone
     * @ORM\Column(name="otherCultDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $otherCultDone;

    /**
     * @var CultureResult
     * @ORM\Column(name="otherCultResult",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $otherCultResult;

    /**
     * @var string
     * @ORM\Column(name="otherCultOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $otherCultOther;

//----------------------------------------
// Serotype Results
    /**
     * @var PathogenIdentifier
     * @ORM\Column(name="pathogenIdentifierMethod",type="PathogenIdentifier",nullable=true)
     * @Groups({"api"})
     */
    private $pathogenIdentifierMethod;

    /**
     * @var string
     * @ORM\Column(name="pathogenIdentifierOther", type="string",nullable=true)
     * @Groups({"api"})
     */
    private $pathogenIdentifierOther;

    /**
     * @var SerotypeIdentifier
     * @ORM\Column(name="serotypeIdentifier",type="SerotypeIdentifier",nullable=true)
     * @Groups({"api"})
     */
    private $serotypeIdentifier;

    /**
     * @var string
     * @ORM\Column(name="serotypeIdentifierOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $serotypeIdentifierOther;

    /**
     * @var double
     * @ORM\Column(name="lytA",type="decimal",precision=3, scale=1,nullable=true)
     * @Groups({"api"})
     */
    private $lytA;

    /**
     * @var double
     * @ORM\Column(name="sodC",type="decimal",precision=3, scale=1,nullable=true)
     * @Groups({"api"})
     */
    private $sodC;

    /**
     * @var double
     * @ORM\Column(name="hpd",type="decimal",precision=3, scale=1,nullable=true)
     * @Groups({"api"})
     */
    private $hpd;

    /**
     * @var double
     * @ORM\Column(name="rNaseP",type="decimal",precision=3, scale=1,nullable=true)
     * @Groups({"api"})
     */
    private $rNaseP;

    /**
     * @var double
     * @ORM\Column(name="spnSerotype",type="SpnSerotype",nullable=true)
     * @Groups({"api"})
     */
    private $spnSerotype;

    /**
     * @var string $spnSerotypeOther
     * @ORM\Column(name="spnSerotypeOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $spnSerotypeOther;

    /**
     * @var double
     * @ORM\Column(name="hiSerotype",type="HiSerotype",nullable=true)
     * @Groups({"api"})
     */
    private $hiSerotype;

    /**
     * @var string $hiSerotypeOther
     * @ORM\Column(name="hiSerotypeOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $hiSerotypeOther;

    /**
     * @var double
     * @ORM\Column(name="nmSerogroup",type="NmSerogroup",nullable=true)
     * @Groups({"api"})
     */
    private $nmSerogroup;

    /**
     * @var string $nmSerogroupOther
     * @ORM\Column(name="nmSerogroupOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $nmSerogroupOther;

//LAB END
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

    public function getCaseId()
    {
        return $this->caseId;
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

    public function getHibReceived()
    {
        return $this->hibReceived;
    }

    public function getHibDoses()
    {
        return $this->hibDoses;
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

    public function getOtherSpecimenCollected()
    {
        return $this->otherSpecimenCollected;
    }

    public function getOtherSpecimenOther()
    {
        return $this->otherSpecimenOther;
    }
    public function getCsfSiteId()
    {
        return $this->csfSiteId;
    }

    public function getCsfSiteDateTime()
    {
        return $this->csfSiteDateTime;
    }

    public function getCsfWcc()
    {
        return $this->csfWcc;
    }

    public function getCsfGlucose()
    {
        return $this->csfGlucose;
    }

    public function getCsfProtein()
    {
        return $this->csfProtein;
    }

    public function getCsfCultDone()
    {
        return $this->csfCultDone;
    }

    public function getCsfGramDone()
    {
        return $this->csfGramDone;
    }

    public function getCsfBinaxDone()
    {
        return $this->csfBinaxDone;
    }

    public function getCsfLatDone()
    {
        return $this->csfLatDone;
    }

    public function getCsfPcrDone()
    {
        return $this->csfPcrDone;
    }

    public function getCsfCultResult()
    {
        return $this->csfCultResult;
    }

    public function getCsfCultOther()
    {
        return $this->csfCultOther;
    }

    public function getCsfGramResult()
    {
        return $this->csfGramResult;
    }

    public function getCsfGramResultOrganism()
    {
        return $this->csfGramResultOrganism;
    }

    public function getCsfGramOther()
    {
        return $this->csfGramOther;
    }

    public function getCsfBinaxResult()
    {
        return $this->csfBinaxResult;
    }

    public function getCsfLatResult()
    {
        return $this->csfLatResult;
    }

    public function getCsfLatOther()
    {
        return $this->csfLatOther;
    }

    public function getCsfPcrResult()
    {
        return $this->csfPcrResult;
    }

    public function getCsfPcrOther()
    {
        return $this->csfPcrOther;
    }

    public function getCsfSentToNL()
    {
        return $this->csfSentToNL;
    }

    public function getCsfDateSentToNL()
    {
        return $this->csfDateSentToNL;
    }

    public function getCsfNLId()
    {
        return $this->csfNLId;
    }

    public function getCsfNLDateReceived()
    {
        return $this->csfNLDateReceived;
    }

    public function getCsfIsolateSentToNL()
    {
        return $this->csfIsolateSentToNL;
    }

    public function getCsfIsolateDateSentToNL()
    {
        return $this->csfIsolateDateSentToNL;
    }

    public function getCsfIsolateNLDateReceived()
    {
        return $this->csfIsolateNLDateReceived;
    }

    public function getCsfSentToRRL()
    {
        return $this->csfSentToRRL;
    }

    public function getCsfDateSentToRRL()
    {
        return $this->csfDateSentToRRL;
    }

    public function getCsfRRLId()
    {
        return $this->csfRRLId;
    }

    public function getCsfRRLDateReceived()
    {
        return $this->csfRRLDateReceived;
    }

    public function getCsfIsolateSentToRRL()
    {
        return $this->csfIsolateSentToRRL;
    }

    public function getCsfIsolateDateSentToRRL()
    {
        return $this->csfIsolateDateSentToRRL;
    }

    public function getCsfIsolateRRLDateReceived()
    {
        return $this->csfIsolateRRLDateReceived;
    }

    public function getCsfStore()
    {
        return $this->csfStore;
    }

    public function getCsfIsolStore()
    {
        return $this->csfIsolStore;
    }

    public function getCsfVolume()
    {
        return $this->csfVolume;
    }

    public function getCsfSiteDNAExtractionDate()
    {
        return $this->csfSiteDNAExtractionDate;
    }

    public function getCsfSiteDNAVolume()
    {
        return $this->csfSiteDNAVolume;
    }

    public function getCsfNLDNAExtractionDate()
    {
        return $this->csfNLDNAExtractionDate;
    }

    public function getCsfNLDNAVolume()
    {
        return $this->csfNLDNAVolume;
    }

    public function getCsfRRLDNAExtractionDate()
    {
        return $this->csfRRLDNAExtractionDate;
    }

    public function getCsfRRLDNAVolume()
    {
        return $this->csfRRLDNAVolume;
    }

    public function getBloodSiteId()
    {
        return $this->bloodSiteId;
    }

    public function getBloodSiteDate()
    {
        return $this->bloodSiteDate;
    }

    public function getBloodSentToNL()
    {
        return $this->bloodSentToNL;
    }

    public function getBloodNLId()
    {
        return $this->bloodNLId;
    }

    public function getBloodDateSentToNL()
    {
        return $this->bloodDateSentToNL;
    }

    public function getBloodNLDateReceived()
    {
        return $this->bloodNLDateReceived;
    }

    public function getBloodIsolateSentToNL()
    {
        return $this->bloodIsolateSentToNL;
    }

    public function getBloodIsolateDateSentToNL()
    {
        return $this->bloodIsolateDateSentToNL;
    }

    public function getBloodIsolateNLDateReceived()
    {
        return $this->bloodIsolateNLDateReceived;
    }

    public function getBloodSentToRRL()
    {
        return $this->bloodSentToRRL;
    }

    public function getBloodRRLId()
    {
        return $this->bloodRRLId;
    }

    public function getBloodDateSentToRRL()
    {
        return $this->bloodDateSentToRRL;
    }

    public function getBloodRRLDateReceived()
    {
        return $this->bloodRRLDateReceived;
    }

    public function getBloodIsolateSentToRRL()
    {
        return $this->bloodIsolateSentToRRL;
    }

    public function getBloodIsolateDateSentToRRL()
    {
        return $this->bloodIsolateDateSentToRRL;
    }

    public function getBloodIsolateRRLDateReceived()
    {
        return $this->bloodIsolateRRLDateReceived;
    }

    public function getBloodCultDone()
    {
        return $this->bloodCultDone;
    }

    public function getBloodGramDone()
    {
        return $this->bloodGramDone;
    }

    public function getBloodPcrDone()
    {
        return $this->bloodPcrDone;
    }

    public function getBloodCultResult()
    {
        return $this->bloodCultResult;
    }

    public function getBloodCultOther()
    {
        return $this->bloodCultOther;
    }

    public function getBloodGramResult()
    {
        return $this->bloodGramResult;
    }

    public function getBloodGramResultOrganism()
    {
        return $this->bloodGramResultOrganism;
    }

    public function getBloodGramOther()
    {
        return $this->bloodGramOther;
    }

    public function getBloodPcrResult()
    {
        return $this->bloodPcrResult;
    }

    public function getBloodPcrOther()
    {
        return $this->bloodPcrOther;
    }

    public function getOtherSiteId()
    {
        return $this->otherSiteId;
    }

    public function getOtherNLId()
    {
        return $this->otherNLId;
    }

    public function getOtherRRLId()
    {
        return $this->otherRRLId;
    }

    public function getOtherSiteDate()
    {
        return $this->otherSiteDate;
    }

    public function getOtherSentToNL()
    {
        return $this->otherSentToNL;
    }

    public function getOtherDateSentToNL()
    {
        return $this->otherDateSentToNL;
    }

    public function getOtherNLDateReceived()
    {
        return $this->otherNLDateReceived;
    }

    public function getOtherSentToRRL()
    {
        return $this->otherSentToRRL;
    }

    public function getOtherDateSentToRRL()
    {
        return $this->otherDateSentToRRL;
    }

    public function getOtherRRLDateReceived()
    {
        return $this->otherRRLDateReceived;
    }

    public function getOtherCultDone()
    {
        return $this->otherCultDone;
    }

    public function getOtherCultResult()
    {
        return $this->otherCultResult;
    }

    public function getOtherCultOther()
    {
        return $this->otherCultOther;
    }

    public function getPathogenIdentifierMethod()
    {
        return $this->pathogenIdentifierMethod;
    }

    public function getPathogenIdentifierOther()
    {
        return $this->pathogenIdentifierOther;
    }

    public function getSerotypeIdentifier()
    {
        return $this->serotypeIdentifier;
    }

    public function getSerotypeIdentifierOther()
    {
        return $this->serotypeIdentifierOther;
    }

    public function getLytA()
    {
        return $this->lytA;
    }

    public function getSodC()
    {
        return $this->sodC;
    }

    public function getHpd()
    {
        return $this->hpd;
    }

    public function getRNaseP()
    {
        return $this->rNaseP;
    }

    public function getSpnSerotype()
    {
        return $this->spnSerotype;
    }

    public function getSpnSerotypeOther()
    {
        return $this->spnSerotypeOther;
    }

    public function getHiSerotype()
    {
        return $this->hiSerotype;
    }

    public function getHiSerotypeOther()
    {
        return $this->hiSerotypeOther;
    }

    public function getNmSerogroup()
    {
        return $this->nmSerogroup;
    }

    public function getNmSerogroupOther()
    {
        return $this->nmSerogroupOther;
    }

    public function setCsfSiteId($csfSiteId)
    {
        $this->csfSiteId = $csfSiteId;
        return $this;
    }

    public function setCsfSiteDateTime($csfSiteDateTime)
    {
        $this->csfSiteDateTime = $csfSiteDateTime;
        return $this;
    }

    public function setCsfWcc($csfWcc)
    {
        $this->csfWcc = $csfWcc;
        return $this;
    }

    public function setCsfGlucose($csfGlucose)
    {
        $this->csfGlucose = $csfGlucose;
        return $this;
    }

    public function setCsfProtein($csfProtein)
    {
        $this->csfProtein = $csfProtein;
        return $this;
    }

    public function setCsfCultDone(TripleChoice $csfCultDone)
    {
        $this->csfCultDone = $csfCultDone;
        return $this;
    }

    public function setCsfGramDone(TripleChoice $csfGramDone)
    {
        $this->csfGramDone = $csfGramDone;
        return $this;
    }

    public function setCsfBinaxDone(TripleChoice $csfBinaxDone)
    {
        $this->csfBinaxDone = $csfBinaxDone;
        return $this;
    }

    public function setCsfLatDone(TripleChoice $csfLatDone)
    {
        $this->csfLatDone = $csfLatDone;
        return $this;
    }

    public function setCsfPcrDone(TripleChoice $csfPcrDone)
    {
        $this->csfPcrDone = $csfPcrDone;
        return $this;
    }

    public function setCsfCultResult(CultureResult $csfCultResult)
    {
        $this->csfCultResult = $csfCultResult;
        return $this;
    }

    public function setCsfCultOther($csfCultOther)
    {
        $this->csfCultOther = $csfCultOther;
        return $this;
    }

    public function setCsfGramResult(GramStain $csfGramResult)
    {
        $this->csfGramResult = $csfGramResult;
        return $this;
    }

    public function setCsfGramResultOrganism(GramStainOrganism $csfGramResultOrganism)
    {
        $this->csfGramResultOrganism = $csfGramResultOrganism;
        return $this;
    }

    public function setCsfGramOther($csfGramOther)
    {
        $this->csfGramOther = $csfGramOther;
        return $this;
    }

    public function setCsfBinaxResult(BinaxResult $csfBinaxResult)
    {
        $this->csfBinaxResult = $csfBinaxResult;
        return $this;
    }

    public function setCsfLatResult(LatResult $csfLatResult)
    {
        $this->csfLatResult = $csfLatResult;
        return $this;
    }

    public function setCsfLatOther($csfLatOther)
    {
        $this->csfLatOther = $csfLatOther;
        return $this;
    }

    public function setCsfPcrResult(PCRResult $csfPcrResult)
    {
        $this->csfPcrResult = $csfPcrResult;
        return $this;
    }

    public function setCsfPcrOther($csfPcrOther)
    {
        $this->csfPcrOther = $csfPcrOther;
        return $this;
    }

    public function setCsfSentToNL(TripleChoice $csfSentToNL)
    {
        $this->csfSentToNL = $csfSentToNL;
        return $this;
    }

    public function setCsfDateSentToNL($csfDateSentToNL)
    {
        $this->csfDateSentToNL = $csfDateSentToNL;
        return $this;
    }

    public function setCsfNLId($csfNLId)
    {
        $this->csfNLId = $csfNLId;
        return $this;
    }

    public function setCsfNLDateReceived($csfNLDateReceived)
    {
        $this->csfNLDateReceived = $csfNLDateReceived;
        return $this;
    }

    public function setCsfIsolateSentToNL(TripleChoice $csfIsolateSentToNL)
    {
        $this->csfIsolateSentToNL = $csfIsolateSentToNL;
        return $this;
    }

    public function setCsfIsolateDateSentToNL($csfIsolateDateSentToNL)
    {
        $this->csfIsolateDateSentToNL = $csfIsolateDateSentToNL;
        return $this;
    }

    public function setCsfIsolateNLDateReceived($csfIsolateNLDateReceived)
    {
        $this->csfIsolateNLDateReceived = $csfIsolateNLDateReceived;
        return $this;
    }

    public function setCsfSentToRRL(TripleChoice $csfSentToRRL)
    {
        $this->csfSentToRRL = $csfSentToRRL;
        return $this;
    }

    public function setCsfDateSentToRRL($csfDateSentToRRL)
    {
        $this->csfDateSentToRRL = $csfDateSentToRRL;
        return $this;
    }

    public function setCsfRRLId($csfRRLId)
    {
        $this->csfRRLId = $csfRRLId;
        return $this;
    }

    public function setCsfRRLDateReceived($csfRRLDateReceived)
    {
        $this->csfRRLDateReceived = $csfRRLDateReceived;
        return $this;
    }

    public function setCsfIsolateSentToRRL(TripleChoice $csfIsolateSentToRRL)
    {
        $this->csfIsolateSentToRRL = $csfIsolateSentToRRL;
        return $this;
    }

    public function setCsfIsolateDateSentToRRL($csfIsolateDateSentToRRL)
    {
        $this->csfIsolateDateSentToRRL = $csfIsolateDateSentToRRL;
        return $this;
    }

    public function setCsfIsolateRRLDateReceived($csfIsolateRRLDateReceived)
    {
        $this->csfIsolateRRLDateReceived = $csfIsolateRRLDateReceived;
        return $this;
    }

    public function setCsfStore(TripleChoice $csfStore)
    {
        $this->csfStore = $csfStore;
        return $this;
    }

    public function setCsfIsolStore(TripleChoice $csfIsolStore)
    {
        $this->csfIsolStore = $csfIsolStore;
        return $this;
    }

    public function setCsfVolume(Volume $csfVolume)
    {
        $this->csfVolume = $csfVolume;
        return $this;
    }

    public function setCsfSiteDNAExtractionDate($csfSiteDNAExtractionDate)
    {
        $this->csfSiteDNAExtractionDate = $csfSiteDNAExtractionDate;
        return $this;
    }

    public function setCsfSiteDNAVolume($csfSiteDNAVolume)
    {
        $this->csfSiteDNAVolume = $csfSiteDNAVolume;
        return $this;
    }

    public function setCsfNLDNAExtractionDate($csfNLDNAExtractionDate)
    {
        $this->csfNLDNAExtractionDate = $csfNLDNAExtractionDate;
        return $this;
    }

    public function setCsfNLDNAVolume($csfNLDNAVolume)
    {
        $this->csfNLDNAVolume = $csfNLDNAVolume;
        return $this;
    }

    public function setCsfRRLDNAExtractionDate($csfRRLDNAExtractionDate)
    {
        $this->csfRRLDNAExtractionDate = $csfRRLDNAExtractionDate;
        return $this;
    }

    public function setCsfRRLDNAVolume($csfRRLDNAVolume)
    {
        $this->csfRRLDNAVolume = $csfRRLDNAVolume;
        return $this;
    }

    public function setBloodSiteId($bloodSiteId)
    {
        $this->bloodSiteId = $bloodSiteId;
        return $this;
    }

    public function setBloodSiteDate($bloodSiteDate)
    {
        $this->bloodSiteDate = $bloodSiteDate;
        return $this;
    }

    public function setBloodSentToNL(TripleChoice $bloodSentToNL)
    {
        $this->bloodSentToNL = $bloodSentToNL;
        return $this;
    }

    public function setBloodNLId($bloodNLId)
    {
        $this->bloodNLId = $bloodNLId;
        return $this;
    }

    public function setBloodDateSentToNL($bloodDateSentToNL)
    {
        $this->bloodDateSentToNL = $bloodDateSentToNL;
        return $this;
    }

    public function setBloodNLDateReceived($bloodNLDateReceived)
    {
        $this->bloodNLDateReceived = $bloodNLDateReceived;
        return $this;
    }

    public function setBloodIsolateSentToNL(TripleChoice $bloodIsolateSentToNL)
    {
        $this->bloodIsolateSentToNL = $bloodIsolateSentToNL;
        return $this;
    }

    public function setBloodIsolateDateSentToNL($bloodIsolateDateSentToNL)
    {
        $this->bloodIsolateDateSentToNL = $bloodIsolateDateSentToNL;
        return $this;
    }

    public function setBloodIsolateNLDateReceived($bloodIsolateNLDateReceived)
    {
        $this->bloodIsolateNLDateReceived = $bloodIsolateNLDateReceived;
        return $this;
    }

    public function setBloodSentToRRL(TripleChoice $bloodSentToRRL)
    {
        $this->bloodSentToRRL = $bloodSentToRRL;
        return $this;
    }

    public function setBloodRRLId($bloodRRLId)
    {
        $this->bloodRRLId = $bloodRRLId;
        return $this;
    }

    public function setBloodDateSentToRRL($bloodDateSentToRRL)
    {
        $this->bloodDateSentToRRL = $bloodDateSentToRRL;
        return $this;
    }

    public function setBloodRRLDateReceived($bloodRRLDateReceived)
    {
        $this->bloodRRLDateReceived = $bloodRRLDateReceived;
        return $this;
    }

    public function setBloodIsolateSentToRRL(TripleChoice $bloodIsolateSentToRRL)
    {
        $this->bloodIsolateSentToRRL = $bloodIsolateSentToRRL;
        return $this;
    }

    public function setBloodIsolateDateSentToRRL($bloodIsolateDateSentToRRL)
    {
        $this->bloodIsolateDateSentToRRL = $bloodIsolateDateSentToRRL;
        return $this;
    }

    public function setBloodIsolateRRLDateReceived($bloodIsolateRRLDateReceived)
    {
        $this->bloodIsolateRRLDateReceived = $bloodIsolateRRLDateReceived;
        return $this;
    }

    public function setBloodCultDone(TripleChoice $bloodCultDone)
    {
        $this->bloodCultDone = $bloodCultDone;
        return $this;
    }

    public function setBloodGramDone(TripleChoice $bloodGramDone)
    {
        $this->bloodGramDone = $bloodGramDone;
        return $this;
    }

    public function setBloodPcrDone(TripleChoice $bloodPcrDone)
    {
        $this->bloodPcrDone = $bloodPcrDone;
        return $this;
    }

    public function setBloodCultResult(CultureResult $bloodCultResult)
    {
        $this->bloodCultResult = $bloodCultResult;
        return $this;
    }

    public function setBloodCultOther($bloodCultOther)
    {
        $this->bloodCultOther = $bloodCultOther;
        return $this;
    }

    public function setBloodGramResult(GramStain $bloodGramResult)
    {
        $this->bloodGramResult = $bloodGramResult;
        return $this;
    }

    public function setBloodGramResultOrganism(GramStainOrganism $bloodGramResultOrganism)
    {
        $this->bloodGramResultOrganism = $bloodGramResultOrganism;
        return $this;
    }

    public function setBloodGramOther($bloodGramOther)
    {
        $this->bloodGramOther = $bloodGramOther;
        return $this;
    }

    public function setBloodPcrResult(PCRResult $bloodPcrResult)
    {
        $this->bloodPcrResult = $bloodPcrResult;
        return $this;
    }

    public function setBloodPcrOther($bloodPcrOther)
    {
        $this->bloodPcrOther = $bloodPcrOther;
        return $this;
    }

    public function setOtherSiteId($otherSiteId)
    {
        $this->otherSiteId = $otherSiteId;
        return $this;
    }

    public function setOtherNLId($otherNLId)
    {
        $this->otherNLId = $otherNLId;
        return $this;
    }

    public function setOtherRRLId($otherRRLId)
    {
        $this->otherRRLId = $otherRRLId;
        return $this;
    }

    public function setOtherSiteDate($otherSiteDate)
    {
        $this->otherSiteDate = $otherSiteDate;
        return $this;
    }

    public function setOtherSentToNL(TripleChoice $otherSentToNL)
    {
        $this->otherSentToNL = $otherSentToNL;
        return $this;
    }

    public function setOtherDateSentToNL($otherDateSentToNL)
    {
        $this->otherDateSentToNL = $otherDateSentToNL;
        return $this;
    }

    public function setOtherNLDateReceived($otherNLDateReceived)
    {
        $this->otherNLDateReceived = $otherNLDateReceived;
        return $this;
    }

    public function setOtherSentToRRL(TripleChoice $otherSentToRRL)
    {
        $this->otherSentToRRL = $otherSentToRRL;
        return $this;
    }

    public function setOtherDateSentToRRL($otherDateSentToRRL)
    {
        $this->otherDateSentToRRL = $otherDateSentToRRL;
        return $this;
    }

    public function setOtherRRLDateReceived($otherRRLDateReceived)
    {
        $this->otherRRLDateReceived = $otherRRLDateReceived;
        return $this;
    }

    public function setOtherCultDone(TripleChoice $otherCultDone)
    {
        $this->otherCultDone = $otherCultDone;
        return $this;
    }

    public function setOtherCultResult(CultureResult $otherCultResult)
    {
        $this->otherCultResult = $otherCultResult;
        return $this;
    }

    public function setOtherCultOther($otherCultOther)
    {
        $this->otherCultOther = $otherCultOther;
        return $this;
    }

    public function setPathogenIdentifierMethod(PathogenIdentifier $pathogenIdentifierMethod)
    {
        $this->pathogenIdentifierMethod = $pathogenIdentifierMethod;
        return $this;
    }

    public function setPathogenIdentifierOther($pathogenIdentifierOther)
    {
        $this->pathogenIdentifierOther = $pathogenIdentifierOther;
        return $this;
    }

    public function setSerotypeIdentifier(SerotypeIdentifier $serotypeIdentifier)
    {
        $this->serotypeIdentifier = $serotypeIdentifier;
        return $this;
    }

    public function setSerotypeIdentifierOther($serotypeIdentifierOther)
    {
        $this->serotypeIdentifierOther = $serotypeIdentifierOther;
        return $this;
    }

    public function setLytA($lytA)
    {
        $this->lytA = $lytA;
        return $this;
    }

    public function setSodC($sodC)
    {
        $this->sodC = $sodC;
        return $this;
    }

    public function setHpd($hpd)
    {
        $this->hpd = $hpd;
        return $this;
    }

    public function setRNaseP($rNaseP)
    {
        $this->rNaseP = $rNaseP;
        return $this;
    }

    public function setSpnSerotype($spnSerotype)
    {
        $this->spnSerotype = $spnSerotype;
        return $this;
    }

    public function setSpnSerotypeOther($spnSerotypeOther)
    {
        $this->spnSerotypeOther = $spnSerotypeOther;
        return $this;
    }

    public function setHiSerotype($hiSerotype)
    {
        $this->hiSerotype = $hiSerotype;
        return $this;
    }

    public function setHiSerotypeOther($hiSerotypeOther)
    {
        $this->hiSerotypeOther = $hiSerotypeOther;
        return $this;
    }

    public function setNmSerogroup($nmSerogroup)
    {
        $this->nmSerogroup = $nmSerogroup;
        return $this;
    }

    public function setNmSerogroupOther($nmSerogroupOther)
    {
        $this->nmSerogroupOther = $nmSerogroupOther;
        return $this;
    }

        public function setOtherSpecimenOther($otherSpecimenOther)
    {
        $this->otherSpecimenOther = $otherSpecimenOther;
        return $this;
    }

    public function setOtherSpecimenCollected(OtherSpecimen $otherSpecimenCollected)
    {
        $this->otherSpecimenCollected = $otherSpecimenCollected;
        return $this;
    }

    public function setPcvType(PCVType $pcvType)
    {
        $this->pcvType = $pcvType;
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

    public function getCxrDone()
    {
        return $this->cxrDone;
    }

    public function getCxrResult()
    {
        return $this->cxrResult;
    }

    public function getHibMostRecentDose()
    {
        return $this->hibMostRecentDose;
    }

    public function getPcvMostRecentDose()
    {
        return $this->pcvMostRecentDose;
    }

    public function setHibMostRecentDose($hibMostRecentDose)
    {
        $this->hibMostRecentDose = $hibMostRecentDose;
    }

    public function setPcvMostRecentDose($pcvMostRecentDose)
    {
        $this->pcvMostRecentDose = $pcvMostRecentDose;
    }

    public function setCxrDone(TripleChoice $cxrDone)
    {
        $this->cxrDone = $cxrDone;
    }

    public function setCxrResult(CXRResult $cxrResult)
    {
        $this->cxrResult = $cxrResult;
    }

    public function getCxrAdditionalResult()
    {
        return $this->cxrAdditionalResult;
    }

    public function setCxrAdditionalResult(CXRAdditionalResult $cxrAdditionalResult)
    {
        $this->cxrAdditionalResult = $cxrAdditionalResult;
    }

    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;
        return $this;
    }

    public function setOnsetDate($onsetDate)
    {
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

    public function setHibReceived($hibReceived)
    {
        $this->hibReceived = $hibReceived;
        return $this;
    }

    public function setHibDoses(Doses $hibDoses)
    {
        $this->hibDoses = $hibDoses;
        return $this;
    }

    public function setPcvReceived($pcvReceived)
    {
        $this->pcvReceived = $pcvReceived;
        return $this;
    }

    public function setPcvDoses($pcvDoses)
    {
        $this->pcvDoses = $pcvDoses;
        return $this;
    }

    public function setMeningReceived($meningReceived)
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
        $this->meningMostRecentDose = $meningMostRecentDose;
        return $this;
    }

    public function setCsfCollected($csfCollected)
    {
        $this->csfCollected = $csfCollected;
        return $this;
    }

    public function setCsfCollectDateTime($csfCollectDateTime)
    {
        $this->csfCollectDateTime = $csfCollectDateTime;
        return $this;
    }

    public function setCsfAppearance($csfAppearance)
    {
        $this->csfAppearance = $csfAppearance;
        return $this;
    }

    public function setBloodCollected($bloodCollected)
    {
        $this->bloodCollected = $bloodCollected;
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

    public function getResult()
    {
        return $this->result;
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
                $this->result->setValue( (($this->getCsfWcc() > 10 && $this->getCsfWcc() <=100) && ( ($this->getCsfGlucose() >=0 AND $this->getCsfGlucose() < 40) OR ($this->getCsfProtein() > 100) )) );
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

        if($this->hibReceived && $this->hibReceived->equal(TripleChoice::YES) && (is_null($this->hibDoses) || $this->hibDoses->equal(ArrayChoice::NO_SELECTION)))
            return 'hibReceived';

        if($this->pcvReceived && $this->pcvReceived->equal(TripleChoice::YES) && (is_null($this->pcvDoses) || $this->pcvDoses->equal(ArrayChoice::NO_SELECTION)))
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
            return 'otherSpecimentOther';

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

    public function hasLab()
    {
        return ($this->csfSiteId || $this->csfSiteDateTime);
    }
}
