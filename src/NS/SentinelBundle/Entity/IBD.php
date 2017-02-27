<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\IBD\Types\CSFAppearance;
use NS\SentinelBundle\Form\IBD\Types\CXRAdditionalResult;
use NS\SentinelBundle\Form\IBD\Types\CXRResult;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\VaccinationType;
use NS\SentinelBundle\Form\IBD\Types\OtherSpecimen;
use NS\SentinelBundle\Form\IBD\Types\PCVType;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Validators as LocalAssert;

/**
 * Description of IBD
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBDRepository")
 * @ORM\Table(name="ibd_cases",uniqueConstraints={@ORM\UniqueConstraint(name="ibd_site_case_id_idx",columns={"site_id","case_id"})})
 * @ORM\HasLifecycleCallbacks
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\IBDListener"})
 *
 * @LocalAssert\GreaterThanDate(lessThanField="onsetDate",greaterThanField="admDate",message="form.validation.admission-after-onset")
 * @LocalAssert\GreaterThanDate(lessThanField="birthdate",greaterThanField="onsetDate",message="form.validation.onset-after-dob")
 * @LocalAssert\GreaterThanDate(lessThanField="admDate",greaterThanField="csfCollectDate",message="form.validation.onset-after-dob")
 * @LocalAssert\GreaterThanDate(lessThanField="admDate",greaterThanField="bloodCollectDate",message="form.validation.onset-after-dob")
 * @LocalAssert\GreaterThanDate(lessThanField="admDate",greaterThanField="pleuralFluidCollectDate",message="form.validation.onset-after-dob")
 *
 * @LocalAssert\RelatedField(sourceField="admDx",sourceValue={"1"},fields={"menSeizures","menFever","menAltConscious","menInabilityFeed","menNeckStiff","menRash","menFontanelleBulge","menLethargy"})
 * @LocalAssert\RelatedField(sourceField="admDx",sourceValue={"2","3"},fields={"pneuCyanosis","pneuVomit","pneuHypothermia","pneuMalnutrition","pneuDiffBreathe","pneuChestIndraw","pneuCough","pneuStridor","pneu_resp_rate","cxrDone",})
 */
class IBD extends BaseCase
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD\NationalLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $nationalLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD\ReferenceLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $referenceLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD\SiteLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $siteLab;

    /**
     * @Serializer\Exclude()
     */
    protected $siteLabClass = '\NS\SentinelBundle\Entity\IBD\SiteLab';

    /**
     * @Serializer\Exclude()
     */
    protected $referenceClass = '\NS\SentinelBundle\Entity\IBD\ReferenceLab';

    /**
     * @Serializer\Exclude()
     */
    protected $nationalClass  = '\NS\SentinelBundle\Entity\IBD\NationalLab';

//Case-based Clinical Data
    /**
     * @var \DateTime $onsetDate
     * @ORM\Column(name="onset_date",type="date",nullable=true)
     * @Serializer\Groups({"api"})
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     */
    protected $onset_date;

    /**
     * @var Diagnosis $admDx
     * @ORM\Column(name="adm_dx",type="Diagnosis",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $adm_dx;

    /**
     * @var string $admDxOther
     * @ORM\Column(name="adm_dx_other",type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $adm_dx_other;

    /**
     * @var TripleChoice $antibiotics
     * @ORM\Column(name="antibiotics",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $antibiotics;

//MENINGITIS
    /**
     * @var TripleChoice $menSeizures
     * @ORM\Column(name="men_seizures",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $men_seizures;

    /**
     * @var TripleChoice $menFever
     * @ORM\Column(name="men_fever",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $men_fever;

    /**
     * @var TripleChoice $menAltConscious
     * @ORM\Column(name="men_alt_conscious",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $men_alt_conscious;

    /**
     * @var TripleChoice $menInabilityFeed
     * @ORM\Column(name="men_inability_feed",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $men_inability_feed;

    /**
     * @var TripleChoice $men_neck_stiff
     * @ORM\Column(name="men_neck_stiff",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $men_neck_stiff;

    /**
     * @var TripleChoice $menRash
     * @ORM\Column(name="men_rash",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $men_rash;

    /**
     * @var TripleChoice $menFontanelleBulge
     * @ORM\Column(name="men_fontanelle_bulge",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $men_fontanelle_bulge;

    /**
     * @var TripleChoice $menLethargy
     * @ORM\Column(name="men_lethargy",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $men_lethargy;

//PNEUMONIA / SEPSIS
    /**
     * @var TripleChoice $pneuDiffBreathe
     * @ORM\Column(name="pneu_diff_breathe",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pneu_diff_breathe;

    /**
     * @var TripleChoice $pneuChestIndraw
     * @ORM\Column(name="pneu_chest_indraw",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pneu_chest_indraw;

    /**
     * @var TripleChoice $pneuCough
     * @ORM\Column(name="pneu_cough",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pneu_cough;

    /**
     * @var TripleChoice $pneuCyanosis
     * @ORM\Column(name="pneu_cyanosis",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pneu_cyanosis;

    /**
     * @var TripleChoice $pneuStridor
     * @ORM\Column(name="pneu_stridor",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pneu_stridor;

    /**
     * @var integer $pneuRespRate
     * @ORM\Column(name="pneu_resp_rate",type="integer",nullable=true)
     * @Assert\Range(min=10,max=150,minMessage="Please provide a valid respiratory rate",maxMessage="Please provide a valid respiratory rate")
     * @Serializer\Groups({"api"})
     */
    private $pneu_resp_rate;

    /**
     * @var integer $pneu_oxygen_saturation
     * @ORM\Column(name="pneu_oxygen_saturation",type="integer",nullable=true)
     * @Assert\Range(min=10,max=100,minMessage="Please provide a valid oxygen saturation level",maxMessage="Please provide a valid oxygen saturation level")
     *
     * @Serializer\Groups({"api"})
     */
    private $pneu_oxygen_saturation;

    /**
     * @var TripleChoice $pneuVomit
     * @ORM\Column(name="pneu_vomit",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pneu_vomit;

    /**
     * @var TripleChoice $pneuHypothermia
     * @ORM\Column(name="pneu_hypothermia",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pneu_hypothermia;

    /**
     * @var TripleChoice $pneuMalnutrition
     * @ORM\Column(name="pneu_malnutrition",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pneu_malnutrition;

    /**
     * @var TripleChoice $cxrDone
     * @ORM\Column(name="cxr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $cxr_done;

    /**
     * @var CXRResult $cxrResult
     * @ORM\Column(name="cxr_result",type="CXRResult",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $cxr_result;

    /**
     * @var CXRAdditionalResult $cxrResult
     * @ORM\Column(name="cxr_additional_result",type="CXRAdditionalResult",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $cxr_additional_result;

//Case-based Vaccination History
    /**
     * @var VaccinationReceived $hibReceived
     * @ORM\Column(name="hib_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $hib_received;

    /**
     * @var FourDoses $hibDoses
     * @ORM\Column(name="hib_doses",type="FourDoses",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $hib_doses;

    /**
     * @var \DateTime $hibMostRecentDose
     * @ORM\Column(name="hib_most_recent_dose",type="date",nullable=true)
     * @Serializer\Groups({"api"})
     * @LocalAssert\NoFutureDate()
     */
    private $hib_most_recent_dose;

    /**
     * @var VaccinationReceived $pcvReceived
     * @ORM\Column(name="pcv_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pcv_received;

    /**
     * @var FourDoses $pcvDoses
     * @ORM\Column(name="pcv_doses",type="FourDoses",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pcv_doses;

    /**
     * @var PCVType $pcvType
     * @ORM\Column(name="pcv_type",type="PCVType",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $pcv_type;

    /**
     * @var \DateTime $pcvMostRecentDose
     * @ORM\Column(name="pcv_most_recent_dose",type="date",nullable=true)
     * @Serializer\Groups({"api"})
     * @LocalAssert\NoFutureDate()
     */
    private $pcv_most_recent_dose;

    /**
     * @var VaccinationReceived $meningReceived
     * @ORM\Column(name="mening_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $mening_received;

    /**
     * @var VaccinationType $meningType
     * @ORM\Column(name="mening_type",type="IBDVaccinationType",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $mening_type;

    /**
     * @var \DateTime $mening_date
     * @ORM\Column(name="mening_date",type="date",nullable=true)
     * @Assert\Date
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api"})
     */
    private $mening_date;

//Case-based Specimen Collection Data

    /**
     * @var TripleChoice $csfCollected
     * @ORM\Column(name="csf_collected",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $csf_collected;

    /**
     * @var \DateTime $csfCollectDate
     * @ORM\Column(name="csf_collect_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api"})
     */
    private $csf_collect_date;

    /**
     * @var \DateTime $csfCollectTime
     * @ORM\Column(name="csf_collect_time",type="time",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $csf_collect_time;

    /**
     * @var CSFAppearance $csfAppearance
     * @ORM\Column(name="csf_appearance",type="CSFAppearance",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $csf_appearance;

    /**
     * @var TripleChoice $bloodCollected
     * @ORM\Column(name="blood_collected", type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $blood_collected;

    /**
     * @var \DateTime $bloodCollectDate
     * @ORM\Column(name="blood_collect_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     */
    private $blood_collect_date;

    /**
     * @var \DateTime $blood_collect_time
     * @ORM\Column(name="blood_collect_time",type="time",nullable=true)
     */
    private $blood_collect_time;

    /**
     * @var OtherSpecimen $otherSpecimenCollected
     * @ORM\Column(name="other_specimen_collected",type="OtherSpecimen",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $other_specimen_collected;

    /**
     * @var string $otherSpecimenOther
     * @ORM\Column(name="other_specimen_other",type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $other_specimen_other;

//Case-based Outcome Data
    /**
     * @var DischargeOutcome $dischOutcome
     * @ORM\Column(name="disch_outcome",type="IBDDischargeOutcome",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $disch_outcome;

    /**
     * @var Diagnosis $dischDx
     * @ORM\Column(name="disch_dx",type="IBDDischargeDiagnosis",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $disch_dx;

    /**
     * @var $dischDxOther
     * @ORM\Column(name="disch_dx_other",type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $disch_dx_other;

    /**
     * @var DischargeClassification $dischClass
     * @ORM\Column(name="disch_class",type="IBDDischargeClassification",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $disch_class;

    /**
     * @var string $dischClassOther
     * @ORM\Column(name="disch_class_other",type="string",nullable=true)
     */
    private $disch_class_other;

    /**
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $comment;

    /**
     * @var CaseResult $result
     * @ORM\Column(name="result",type="CaseResult",nullable=true)
     * @Serializer\Groups({"api"})
     */
    private $result;

    //PAHO/AMR Specific Variables
    /**
     * @var integer
     * @ORM\Column(name="blood_number_of_samples",type="integer",nullable=true)
     */
    private $blood_number_of_samples;

    /**
     * @var TripleChoice
     * @ORM\Column(name="pleural_fluid_collected",type="TripleChoice",nullable=true)
     */
    private $pleural_fluid_collected;

    /**
     * @var \DateTime
     * @ORM\Column(name="pleural_fluid_collect_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     */
    private $pleural_fluid_collect_date;

    /**
     * @var \DateTime
     * @ORM\Column(name="pleural_fluid_collect_time",type="time",nullable=true)
     */
    private $pleural_fluid_collect_time;

    public function __construct()
    {
        parent::__construct();
        $this->result = new CaseResult(CaseResult::UNKNOWN);
    }

    /**
     * @return \DateTime
     */
    public function getOnsetDate()
    {
        return $this->onset_date;
    }

    /**
     * @return Diagnosis
     */
    public function getAdmDx()
    {
        return $this->adm_dx;
    }

    /**
     * @return string
     */
    public function getAdmDxOther()
    {
        return $this->adm_dx_other;
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
        return $this->men_seizures;
    }

    /**
     * @return TripleChoice
     */
    public function getMenFever()
    {
        return $this->men_fever;
    }

    /**
     * @return TripleChoice
     */
    public function getMenAltConscious()
    {
        return $this->men_alt_conscious;
    }

    /**
     * @return TripleChoice
     */
    public function getMenInabilityFeed()
    {
        return $this->men_inability_feed;
    }

    /**
     * @return TripleChoice
     */
    public function getMenNeckStiff()
    {
        return $this->men_neck_stiff;
    }

    /**
     * @return TripleChoice
     */
    public function getMenRash()
    {
        return $this->men_rash;
    }

    /**
     * @return TripleChoice
     */
    public function getMenFontanelleBulge()
    {
        return $this->men_fontanelle_bulge;
    }

    /**
     * @return TripleChoice
     */
    public function getMenLethargy()
    {
        return $this->men_lethargy;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuDiffBreathe()
    {
        return $this->pneu_diff_breathe;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuChestIndraw()
    {
        return $this->pneu_chest_indraw;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuCough()
    {
        return $this->pneu_cough;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuCyanosis()
    {
        return $this->pneu_cyanosis;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuStridor()
    {
        return $this->pneu_stridor;
    }

    /**
     * @return int
     */
    public function getPneuRespRate()
    {
        return $this->pneu_resp_rate;
    }

    /**
     * @return int
     */
    public function getPneuOxygenSaturation()
    {
        return $this->pneu_oxygen_saturation;
    }

    /**
     * @param int $pneu_oxygen_saturation
     */
    public function setPneuOxygenSaturation($pneu_oxygen_saturation)
    {
        $this->pneu_oxygen_saturation = $pneu_oxygen_saturation;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuVomit()
    {
        return $this->pneu_vomit;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuHypothermia()
    {
        return $this->pneu_hypothermia;
    }

    /**
     * @return TripleChoice
     */
    public function getPneuMalnutrition()
    {
        return $this->pneu_malnutrition;
    }

    /**
     * @return TripleChoice
     */
    public function getCxrDone()
    {
        return $this->cxr_done;
    }

    /**
     * @return CXRResult
     */
    public function getCxrResult()
    {
        return $this->cxr_result;
    }

    /**
     * @return CXRAdditionalResult
     */
    public function getCxrAdditionalResult()
    {
        return $this->cxr_additional_result;
    }

    /**
     * @return VaccinationReceived
     */
    public function getHibReceived()
    {
        return $this->hib_received;
    }

    /**
     * @return FourDoses
     */
    public function getHibDoses()
    {
        return $this->hib_doses;
    }

    /**
     * @return \DateTime
     */
    public function getHibMostRecentDose()
    {
        return $this->hib_most_recent_dose;
    }

    /**
     * @return VaccinationReceived
     */
    public function getPcvReceived()
    {
        return $this->pcv_received;
    }

    /**
     * @return FourDoses
     */
    public function getPcvDoses()
    {
        return $this->pcv_doses;
    }

    /**
     * @return PCVType
     */
    public function getPcvType()
    {
        return $this->pcv_type;
    }

    /**
     * @return \DateTime
     */
    public function getPcvMostRecentDose()
    {
        return $this->pcv_most_recent_dose;
    }

    /**
     * @return VaccinationReceived
     */
    public function getMeningReceived()
    {
        return $this->mening_received;
    }

    /**
     * @return VaccinationType
     */
    public function getMeningType()
    {
        return $this->mening_type;
    }

    /**
     * @return \DateTime
     */
    public function getMeningDate()
    {
        return $this->mening_date;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfCollected()
    {
        return $this->csf_collected;
    }

    /**
     * @return \DateTime
     */
    public function getCsfCollectDate()
    {
        return $this->csf_collect_date;
    }

    /**
     * @return \DateTime
     */
    public function getCsfCollectTime()
    {
        return $this->csf_collect_time;
    }

    /**
     * @return CSFAppearance
     */
    public function getCsfAppearance()
    {
        return $this->csf_appearance;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodCollected()
    {
        return $this->blood_collected;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getBloodCollectDate()
    {
        return $this->blood_collect_date;
    }

    /**
     * @return \DateTime
     */
    public function getBloodCollectTime()
    {
        return $this->blood_collect_time;
    }

    /**
     * @param \DateTime $blood_collect_time
     */
    public function setBloodCollectTime(\DateTime $blood_collect_time = null)
    {
        $this->blood_collect_time = $blood_collect_time;
    }

    /**
     * @return OtherSpecimen
     */
    public function getOtherSpecimenCollected()
    {
        return $this->other_specimen_collected;
    }

    /**
     * @return string
     */
    public function getOtherSpecimenOther()
    {
        return $this->other_specimen_other;
    }

    /**
     * @return DischargeOutcome
     */
    public function getDischOutcome()
    {
        return $this->disch_outcome;
    }

    /**
     * @return Diagnosis
     */
    public function getDischDx()
    {
        return $this->disch_dx;
    }

    /**
     * @return mixed
     */
    public function getDischDxOther()
    {
        return $this->disch_dx_other;
    }

    /**
     * @return DischargeClassification
     */
    public function getDischClass()
    {
        return $this->disch_class;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return CaseResult
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
        $this->onset_date = $onsetDate;
        return $this;
    }

    /**
     * @param Diagnosis $adm_dx
     * @return $this
     */
    public function setAdmDx(Diagnosis $adm_dx = null)
    {
        $this->adm_dx = $adm_dx;
        return $this;
    }

    /**
     * @param $adm_dxOther
     * @return $this
     */
    public function setAdmDxOther($adm_dxOther)
    {
        $this->adm_dx_other = $adm_dxOther;
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
        $this->men_seizures = $menSeizures;
        return $this;
    }

    /**
     * @param TripleChoice $menFever
     * @return $this
     */
    public function setMenFever(TripleChoice $menFever = null)
    {
        $this->men_fever = $menFever;
        return $this;
    }

    /**
     * @param TripleChoice $menAltConscious
     * @return $this
     */
    public function setMenAltConscious(TripleChoice $menAltConscious = null)
    {
        $this->men_alt_conscious = $menAltConscious;
        return $this;
    }

    /**
     * @param TripleChoice $menInabilityFeed
     * @return $this
     */
    public function setMenInabilityFeed(TripleChoice $menInabilityFeed = null)
    {
        $this->men_inability_feed = $menInabilityFeed;
        return $this;
    }

    /**
     * @param TripleChoice $menNeckStiff
     * @return $this
     */
    public function setMenNeckStiff(TripleChoice $menNeckStiff = null)
    {
        $this->men_neck_stiff = $menNeckStiff;
        return $this;
    }

    /**
     * @param TripleChoice $menRash
     * @return $this
     */
    public function setMenRash(TripleChoice $menRash = null)
    {
        $this->men_rash = $menRash;
        return $this;
    }

    /**
     * @param TripleChoice $menFontanelleBulge
     * @return $this
     */
    public function setMenFontanelleBulge(TripleChoice $menFontanelleBulge = null)
    {
        $this->men_fontanelle_bulge = $menFontanelleBulge;
        return $this;
    }

    /**
     * @param TripleChoice $menLethargy
     * @return $this
     */
    public function setMenLethargy(TripleChoice $menLethargy = null)
    {
        $this->men_lethargy = $menLethargy;
        return $this;
    }

    /**
     * @param TripleChoice $pneuDiffBreathe
     * @return $this
     */
    public function setPneuDiffBreathe(TripleChoice $pneuDiffBreathe = null)
    {
        $this->pneu_diff_breathe = $pneuDiffBreathe;
        return $this;
    }

    /**
     * @param TripleChoice $pneuChestIndraw
     * @return $this
     */
    public function setPneuChestIndraw(TripleChoice $pneuChestIndraw = null)
    {
        $this->pneu_chest_indraw = $pneuChestIndraw;
        return $this;
    }

    /**
     * @param TripleChoice $pneuCough
     * @return $this
     */
    public function setPneuCough(TripleChoice $pneuCough = null)
    {
        $this->pneu_cough = $pneuCough;
        return $this;
    }

    /**
     * @param TripleChoice $pneuCyanosis
     * @return $this
     */
    public function setPneuCyanosis(TripleChoice $pneuCyanosis = null)
    {
        $this->pneu_cyanosis = $pneuCyanosis;
        return $this;
    }

    /**
     * @param TripleChoice $pneuStridor
     * @return $this
     */
    public function setPneuStridor(TripleChoice $pneuStridor = null)
    {
        $this->pneu_stridor = $pneuStridor;
        return $this;
    }

    /**
     * @param $pneuRespRate
     * @return $this
     */
    public function setPneuRespRate($pneuRespRate)
    {
        $this->pneu_resp_rate = $pneuRespRate;
        return $this;
    }

    /**
     * @param TripleChoice $pneuVomit
     * @return $this
     */
    public function setPneuVomit(TripleChoice $pneuVomit = null)
    {
        $this->pneu_vomit = $pneuVomit;
        return $this;
    }

    /**
     * @param TripleChoice $pneuHypothermia
     * @return $this
     */
    public function setPneuHypothermia(TripleChoice $pneuHypothermia = null)
    {
        $this->pneu_hypothermia = $pneuHypothermia;
        return $this;
    }

    /**
     * @param TripleChoice $pneuMalnutrition
     * @return $this
     */
    public function setPneuMalnutrition(TripleChoice $pneuMalnutrition = null)
    {
        $this->pneu_malnutrition = $pneuMalnutrition;
        return $this;
    }

    /**
     * @param TripleChoice $cxrDone
     * @return $this
     */
    public function setCxrDone(TripleChoice $cxrDone = null)
    {
        $this->cxr_done = $cxrDone;
        return $this;
    }

    /**
     * @param CXRResult $cxrResult
     * @return $this
     */
    public function setCxrResult(CXRResult $cxrResult = null)
    {
        $this->cxr_result = $cxrResult;
        return $this;
    }

    /**
     * @param CXRAdditionalResult $cxrAdditionalResult
     * @return $this
     */
    public function setCxrAdditionalResult(CXRAdditionalResult $cxrAdditionalResult = null)
    {
        $this->cxr_additional_result = $cxrAdditionalResult;
        return $this;
    }

    /**
     * @param VaccinationReceived $hibReceived
     * @return $this
     */
    public function setHibReceived(VaccinationReceived $hibReceived = null)
    {
        $this->hib_received = $hibReceived;
        return $this;
    }

    /**
     * @param FourDoses $hibDoses
     * @return $this
     */
    public function setHibDoses(FourDoses $hibDoses = null)
    {
        $this->hib_doses = $hibDoses;
        return $this;
    }

    /**
     * @param $hibMostRecentDose
     * @return $this
     */
    public function setHibMostRecentDose(\DateTime $hibMostRecentDose = null)
    {
        $this->hib_most_recent_dose = $hibMostRecentDose;

        return $this;
    }

    /**
     * @param VaccinationReceived $pcvReceived
     * @return $this
     */
    public function setPcvReceived(VaccinationReceived $pcvReceived = null)
    {
        $this->pcv_received = $pcvReceived;
        return $this;
    }

    /**
     * @param FourDoses $pcvDoses
     * @return $this
     */
    public function setPcvDoses(FourDoses $pcvDoses = null)
    {
        $this->pcv_doses = $pcvDoses;
        return $this;
    }

    /**
     * @param PCVType $pcvType
     * @return $this
     */
    public function setPcvType(PCVType $pcvType = null)
    {
        $this->pcv_type = $pcvType;
        return $this;
    }

    /**
     * @param $pcvMostRecentDose
     * @return $this
     */
    public function setPcvMostRecentDose(\DateTime $pcvMostRecentDose = null)
    {
        $this->pcv_most_recent_dose = $pcvMostRecentDose;

        return $this;
    }

    /**
     * @param VaccinationReceived $meningReceived
     * @return $this
     */
    public function setMeningReceived(VaccinationReceived $meningReceived = null)
    {
        $this->mening_received = $meningReceived;
        return $this;
    }

    /**
     * @param VaccinationType $meningType
     * @return $this
     */
    public function setMeningType(VaccinationType $meningType = null)
    {
        $this->mening_type = $meningType;
        return $this;
    }

    /**
     * @param $meningMostRecentDose
     * @return $this
     */
    public function setMeningDate(\DateTime $meningMostRecentDose = null)
    {
        $this->mening_date = $meningMostRecentDose;

        return $this;
    }

    /**
     * @param TripleChoice $csfCollected
     * @return $this
     */
    public function setCsfCollected(TripleChoice $csfCollected = null)
    {
        $this->csf_collected = $csfCollected;
        return $this;
    }

    /**
     * @param \DateTime $date
     * @return IBD
     */
    public function setCsfCollectDate(\DateTime $date = null)
    {
        $this->csf_collect_date = $date;
        return $this;
    }

    /**
     * @param \DateTime $time
     * @return IBD
     */
    public function setCsfCollectTime(\DateTime $time = null)
    {
        $this->csf_collect_time = $time;
        return $this;
    }

    /**
     * @param CSFAppearance $csfAppearance
     * @return $this
     */
    public function setCsfAppearance(CSFAppearance $csfAppearance = null)
    {
        $this->csf_appearance = $csfAppearance;
        return $this;
    }

    /**
     * @param \DateTime|null $date
     * @return $this
     */
    public function setBloodCollectDate(\DateTime $date = null)
    {
        $this->blood_collect_date = $date;

        return $this;
    }

    /**
     * @param TripleChoice $bloodCollected
     * @return $this
     */
    public function setBloodCollected(TripleChoice $bloodCollected = null)
    {
        $this->blood_collected = $bloodCollected;
        return $this;
    }

    /**
     * @param OtherSpecimen $otherSpecimenCollected
     * @return $this
     */
    public function setOtherSpecimenCollected(OtherSpecimen $otherSpecimenCollected = null)
    {
        $this->other_specimen_collected = $otherSpecimenCollected;
        return $this;
    }

    /**
     * @param $otherSpecimenOther
     * @return $this
     */
    public function setOtherSpecimenOther($otherSpecimenOther)
    {
        $this->other_specimen_other = $otherSpecimenOther;
        return $this;
    }

    /**
     * @param DischargeOutcome $dischOutcome
     * @return $this
     */
    public function setDischOutcome(DischargeOutcome $dischOutcome = null)
    {
        $this->disch_outcome = $dischOutcome;
        return $this;
    }

    /**
     * @param DischargeDiagnosis $dischDx
     * @return $this
     */
    public function setDischDx(DischargeDiagnosis $dischDx = null)
    {
        $this->disch_dx = $dischDx;
        return $this;
    }

    /**
     * @param $dischDxOther
     * @return $this
     */
    public function setDischDxOther($dischDxOther)
    {
        $this->disch_dx_other = $dischDxOther;
        return $this;
    }

    /**
     * @param DischargeClassification $dischClass
     * @return $this
     */
    public function setDischClass(DischargeClassification $dischClass = null)
    {
        $this->disch_class = $dischClass;
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
     * @param CaseResult $result
     * @return $this
     */
    public function setResult(CaseResult $result = null)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getDischClassOther()
    {
        return $this->disch_class_other;
    }

    /**
     * @param string $dischClassOther
     * @return \NS\SentinelBundle\Entity\IBD
     */
    public function setDischClassOther($dischClassOther)
    {
        $this->disch_class_other= $dischClassOther;
        return $this;
    }

    //========================================
    //PAHO/AMR specific fields

    /**
     * @return int
     */
    public function getBloodNumberOfSamples()
    {
        return $this->blood_number_of_samples;
    }

    /**
     * @param int $blood_number_of_samples
     */
    public function setBloodNumberOfSamples($blood_number_of_samples)
    {
        $this->blood_number_of_samples = $blood_number_of_samples;
    }

    /**
     * @return TripleChoice
     */
    public function getPleuralFluidCollected()
    {
        return $this->pleural_fluid_collected;
    }

    /**
     * @param TripleChoice $pleural_fluid_collected
     */
    public function setPleuralFluidCollected($pleural_fluid_collected)
    {
        $this->pleural_fluid_collected = $pleural_fluid_collected;
    }

    /**
     * @return \DateTime
     */
    public function getPleuralFluidCollectDate()
    {
        return $this->pleural_fluid_collect_date;
    }

    /**
     * @param \DateTime $pleural_fluid_collect_date
     */
    public function setPleuralFluidCollectDate($pleural_fluid_collect_date)
    {
        $this->pleural_fluid_collect_date = $pleural_fluid_collect_date;
    }

    /**
     * @return \DateTime
     */
    public function getPleuralFluidCollectTime()
    {
        return $this->pleural_fluid_collect_time;
    }

    /**
     * @param \DateTime $pleural_fluid_collect_time
     */
    public function setPleuralFluidCollectTime($pleural_fluid_collect_time)
    {
        $this->pleural_fluid_collect_time = $pleural_fluid_collect_time;
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
//        // with both an birthdate and onset date, ensure the onset is after birthdate
//        if($this->birthdate && $this->onsetDate && $this->onsetDate < $this->birthdate)
//            $context->addViolationAt ('birthdate', "form.validation.onset-after-dob");
//
// The following validations need to store errors in the object or force form validation prior to form submission
//        // if admission diagnosis is other, enforce value in 'admission diagnosis other' field
//        if($this->adm_dx && $this->adm_dx->equal(Diagnosis::OTHER) && empty($this->adm_dxOther))
//            $context->addViolationAt('adm_dx',"form.validation.admissionDx-other-without-other-text");
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
//            if(is_null($this->mening_date))
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
