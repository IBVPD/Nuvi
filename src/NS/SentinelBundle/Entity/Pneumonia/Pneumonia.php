<?php

namespace NS\SentinelBundle\Entity\Pneumonia;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Form\Types\FourDoses;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\Pneumonia\Types\CXRAdditionalResult;
use NS\SentinelBundle\Form\Pneumonia\Types\CXRResult;
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
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;

/**
 * Description of IBD
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Pneumonia\PneumoniaRepository")
 * @ORM\Table(name="pneu_cases",uniqueConstraints={@ORM\UniqueConstraint(name="pneu_site_case_id_idx",columns={"site_id","case_id"})})
 * @ORM\HasLifecycleCallbacks
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\PneumoniaListener"})
 *
 * @LocalAssert\GreaterThanDate(atPath="adm_date",lessThanField="onsetDate",greaterThanField="admDate",message="form.validation.admission-after-onset")
 * @LocalAssert\GreaterThanDate(atPath="onset_date",lessThanField="birthdate",greaterThanField="onsetDate",message="form.validation.onset-after-dob")
 * @LocalAssert\GreaterThanDate(lessThanField="admDate",greaterThanField="bloodCollectDate",message="form.validation.admission-after-blood-collection")
 * @LocalAssert\GreaterThanDate(lessThanField="admDate",greaterThanField="pleuralFluidCollectDate",message="form.validation.admission-after-pleural-fluid-collection")
 *
 * @LocalAssert\RelatedField(sourceField="admDx",sourceValue={"2","3"},fields={"pneuCyanosis","pneuVomit","pneuHypothermia","pneuMalnutrition","pneuDiffBreathe","pneuChestIndraw","pneuCough","pneuStridor","pneu_resp_rate","cxrDone"},message="field-is-required-due-to-adm-diagnosis")
 * @LocalAssert\PCV()
 */
class Pneumonia extends BaseCase
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Pneumonia\NationalLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $nationalLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Pneumonia\ReferenceLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $referenceLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Pneumonia\SiteLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $siteLab;

    /**
     * @Serializer\Exclude()
     */
    protected $siteLabClass = SiteLab::class;

    /**
     * @Serializer\Exclude()
     */
    protected $referenceClass = ReferenceLab::class;

    /**
     * @Serializer\Exclude()
     */
    protected $nationalClass  = NationalLab::class;

//Case-based Clinical Data
    /**
     * @var DateTime|null
     * @ORM\Column(name="onset_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     */
    private $onset_date;

    /**
     * @var Diagnosis|null
     * @ORM\Column(name="adm_dx",type="Diagnosis",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"AMR"})
     * @ArrayChoiceConstraint(groups={"AMR"})
     */
    private $adm_dx;

    /**
     * @var string|null
     * @ORM\Column(name="adm_dx_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $adm_dx_other;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="antibiotics",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $antibiotics;

    /**
     * @var string|null
     * @ORM\Column(name="antibiotic_name",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $antibiotic_name;

//PNEUMONIA / SEPSIS
    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_diff_breathe",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_diff_breathe;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_chest_indraw",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_chest_indraw;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_cough",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_cough;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_cyanosis",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_cyanosis;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_stridor",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_stridor;

    /**
     * @var integer|null
     * @ORM\Column(name="pneu_resp_rate",type="integer",nullable=true)
     * @Assert\Range(min=10,max=150,minMessage="Please provide a valid respiratory rate",maxMessage="Please provide a valid respiratory rate")
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_resp_rate;

    /**
     * @var integer|null
     * @ORM\Column(name="pneu_oxygen_saturation",type="integer",nullable=true)
     * @Assert\Range(min=80,max=100,minMessage="Please provide a valid oxygen saturation level",maxMessage="Please provide a valid oxygen saturation level")
     *
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_oxygen_saturation;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_vomit",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_vomit;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_hypothermia",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_hypothermia;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_malnutrition",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_malnutrition;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pneu_fever",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pneu_fever;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="cxr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $cxr_done;

    /**
     * @var CXRResult|null
     * @ORM\Column(name="cxr_result",type="CXRResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $cxr_result;

    /**
     * @var CXRAdditionalResult|null
     * @ORM\Column(name="cxr_additional_result",type="CXRAdditionalResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $cxr_additional_result;

//Case-based Vaccination History
    /**
     * @var VaccinationReceived|null
     * @ORM\Column(name="hib_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"AMR"})
     * @ArrayChoiceConstraint(groups={"AMR"})
     */
    private $hib_received;

    /**
     * @var FourDoses|null
     * @ORM\Column(name="hib_doses",type="FourDoses",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $hib_doses;

    /**
     * @var DateTime|null
     * @ORM\Column(name="hib_most_recent_dose",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate()
     */
    private $hib_most_recent_dose;

    /**
     * @var VaccinationReceived|null
     * @ORM\Column(name="pcv_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"AMR"})
     * @ArrayChoiceConstraint(groups={"AMR"})
     */
    private $pcv_received;

    /**
     * @var FourDoses|null
     * @ORM\Column(name="pcv_doses",type="FourDoses",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pcv_doses;

    /**
     * @var PCVType|null
     * @ORM\Column(name="pcv_type",type="PCVType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pcv_type;

    /**
     * @var DateTime|null
     * @ORM\Column(name="pcv_most_recent_dose",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate()
     */
    private $pcv_most_recent_dose;

    /**
     * @var VaccinationReceived|null
     * @ORM\Column(name="mening_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"AMR"})
     * @ArrayChoiceConstraint(groups={"AMR"})
     */
    private $mening_received;

    /**
     * @var VaccinationType|null
     * @ORM\Column(name="mening_type",type="IBDVaccinationType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $mening_type;

    /**
     * @var DateTime|null
     * @ORM\Column(name="mening_date",type="date",nullable=true)
     * @Assert\Date
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $mening_date;

//Case-based Specimen Collection Data
    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_collected", type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_collected;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_collect_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate()
     */
    private $blood_collect_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_collect_time",type="time",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     */
    private $blood_collect_time;

    /**
     * @var OtherSpecimen|null
     * @ORM\Column(name="other_specimen_collected",type="OtherSpecimen",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_specimen_collected;

    /**
     * @var string|null
     * @ORM\Column(name="other_specimen_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_specimen_other;

//Case-based Outcome Data
    /**
     * @var DischargeOutcome|null
     * @ORM\Column(name="disch_outcome",type="IBDDischargeOutcome",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_outcome;

    /**
     * @var DischargeDiagnosis|null
     * @ORM\Column(name="disch_dx",type="IBDDischargeDiagnosis",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_dx;

    /**
     * @var string|null
     * @ORM\Column(name="disch_dx_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_dx_other;

    /**
     * @var DischargeClassification|null
     * @ORM\Column(name="disch_class",type="IBDDischargeClassification",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_class;

    /**
     * @var string|null
     * @ORM\Column(name="disch_class_other",type="string",nullable=true)
     */
    private $disch_class_other;

    /**
     * @var string|null
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $comment;

    /**
     * @var CaseResult|null
     * @ORM\Column(name="result",type="CaseResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $result;

    //PAHO/AMR Specific Variables
    /**
     * @var int|null
     * @ORM\Column(name="blood_number_of_samples",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_number_of_samples;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_second_collect_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate()
     */
    private $blood_second_collect_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_second_collect_time",type="time",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     */
    private $blood_second_collect_time;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pleural_fluid_collected",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_collected;

    /**
     * @var DateTime|null
     * @ORM\Column(name="pleural_fluid_collect_date",type="date",nullable=true)
     *
     * @LocalAssert\NoFutureDate()
     *
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $pleural_fluid_collect_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="pleural_fluid_collect_time",type="time",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     */
    private $pleural_fluid_collect_time;

    public function __construct()
    {
        parent::__construct();
        $this->result = new CaseResult(CaseResult::UNKNOWN);
        $this->adm_dx = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
    }

    public function getOnsetDate(): ?DateTime
    {
        return $this->onset_date;
    }

    public function getAdmDx(): ?Diagnosis
    {
        return $this->adm_dx;
    }

    public function getAdmDxOther(): ?string
    {
        return $this->adm_dx_other;
    }

    public function getAntibiotics(): ?TripleChoice
    {
        return $this->antibiotics;
    }

    public function getAntibioticName(): ?string
    {
        return $this->antibiotic_name;
    }

    public function getPneuDiffBreathe(): ?TripleChoice
    {
        return $this->pneu_diff_breathe;
    }

    public function getPneuChestIndraw(): ?TripleChoice
    {
        return $this->pneu_chest_indraw;
    }

    public function getPneuCough(): ?TripleChoice
    {
        return $this->pneu_cough;
    }

    public function getPneuCyanosis(): ?TripleChoice
    {
        return $this->pneu_cyanosis;
    }

    public function getPneuStridor(): ?TripleChoice
    {
        return $this->pneu_stridor;
    }

    public function getPneuRespRate(): ?int
    {
        return $this->pneu_resp_rate;
    }

    public function getPneuOxygenSaturation(): ?int
    {
        return $this->pneu_oxygen_saturation;
    }

    public function setPneuOxygenSaturation(?int $pneu_oxygen_saturation): void
    {
        $this->pneu_oxygen_saturation = $pneu_oxygen_saturation;
    }

    public function getPneuVomit(): ?TripleChoice
    {
        return $this->pneu_vomit;
    }

    public function getPneuHypothermia(): ?TripleChoice
    {
        return $this->pneu_hypothermia;
    }

    public function getPneuMalnutrition(): ?TripleChoice
    {
        return $this->pneu_malnutrition;
    }

    public function getPneuFever(): ?TripleChoice
    {
        return $this->pneu_fever;
    }

    public function setPneuFever(?TripleChoice $pneu_fever): void
    {
        $this->pneu_fever = $pneu_fever;
    }

    public function getCxrDone(): ?TripleChoice
    {
        return $this->cxr_done;
    }

    public function getCxrResult(): ?CXRResult
    {
        return $this->cxr_result;
    }

    public function getCxrAdditionalResult(): ?CXRAdditionalResult
    {
        return $this->cxr_additional_result;
    }

    public function getHibReceived(): ?VaccinationReceived
    {
        return $this->hib_received;
    }

    public function getHibDoses(): ?FourDoses
    {
        return $this->hib_doses;
    }

    public function getHibMostRecentDose(): ?DateTime
    {
        return $this->hib_most_recent_dose;
    }

    public function getPcvReceived(): ?VaccinationReceived
    {
        return $this->pcv_received;
    }

    public function getPcvDoses(): ?FourDoses
    {
        return $this->pcv_doses;
    }

    public function getPcvType(): ?PCVType
    {
        return $this->pcv_type;
    }

    public function getPcvMostRecentDose(): ?DateTime
    {
        return $this->pcv_most_recent_dose;
    }

    public function getBloodCollected(): ?TripleChoice
    {
        return $this->blood_collected;
    }

    public function getBloodCollectDate(): ?DateTime
    {
        return $this->blood_collect_date;
    }

    public function getBloodCollectTime(): ?DateTime
    {
        return $this->blood_collect_time;
    }

    public function setBloodCollectTime(?DateTime $blood_collect_time = null): void
    {
        $this->blood_collect_time = $blood_collect_time;
    }

    public function getOtherSpecimenCollected(): ?OtherSpecimen
    {
        return $this->other_specimen_collected;
    }

    public function getOtherSpecimenOther(): ?string
    {
        return $this->other_specimen_other;
    }

    public function getDischOutcome(): ?DischargeOutcome
    {
        return $this->disch_outcome;
    }

    public function getDischDx(): ?DischargeDiagnosis
    {
        return $this->disch_dx;
    }

    public function getDischDxOther(): ?string
    {
        return $this->disch_dx_other;
    }

    public function getDischClass(): ?DischargeClassification
    {
        return $this->disch_class;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getResult(): ?CaseResult
    {
        return $this->result;
    }

    public function setOnsetDate(?DateTime $onsetDate = null): void
    {
        $this->onset_date = $onsetDate;
    }

    public function setAdmDx(?Diagnosis $adm_dx = null): void
    {
        $this->adm_dx = $adm_dx;
    }

    public function setAdmDxOther(?string $adm_dxOther): void
    {
        $this->adm_dx_other = $adm_dxOther;
    }

    public function setAntibiotics(?TripleChoice $antibiotics = null): void
    {
        $this->antibiotics = $antibiotics;
    }

    public function setAntibioticName(?string $antibiotic_name): void
    {
        $this->antibiotic_name = $antibiotic_name;
    }

    public function setPneuDiffBreathe(TripleChoice $pneuDiffBreathe = null): void
    {
        $this->pneu_diff_breathe = $pneuDiffBreathe;
    }

    public function setPneuChestIndraw(?TripleChoice $pneuChestIndraw = null): void
    {
        $this->pneu_chest_indraw = $pneuChestIndraw;
    }

    public function setPneuCough(?TripleChoice $pneuCough = null): void
    {
        $this->pneu_cough = $pneuCough;
    }

    public function setPneuCyanosis(?TripleChoice $pneuCyanosis = null): void
    {
        $this->pneu_cyanosis = $pneuCyanosis;
    }

    public function setPneuStridor(?TripleChoice $pneuStridor = null): void
    {
        $this->pneu_stridor = $pneuStridor;
    }

    public function setPneuRespRate(?int $pneuRespRate): void
    {
        $this->pneu_resp_rate = $pneuRespRate;
    }

    public function setPneuVomit(?TripleChoice $pneuVomit = null): void
    {
        $this->pneu_vomit = $pneuVomit;
    }

    public function setPneuHypothermia(?TripleChoice $pneuHypothermia = null): void
    {
        $this->pneu_hypothermia = $pneuHypothermia;
    }

    public function setPneuMalnutrition(?TripleChoice $pneuMalnutrition = null): void
    {
        $this->pneu_malnutrition = $pneuMalnutrition;
    }

    public function setCxrDone(?TripleChoice $cxrDone = null): void
    {
        $this->cxr_done = $cxrDone;
    }

    public function setCxrResult(?CXRResult $cxrResult = null): void
    {
        $this->cxr_result = $cxrResult;
    }

    public function setCxrAdditionalResult(?CXRAdditionalResult $cxrAdditionalResult = null): void
    {
        $this->cxr_additional_result = $cxrAdditionalResult;
    }

    public function setHibReceived(?VaccinationReceived $hibReceived = null): void
    {
        $this->hib_received = $hibReceived;
    }

    public function setHibDoses(?FourDoses $hibDoses = null): void
    {
        $this->hib_doses = $hibDoses;
    }

    public function setHibMostRecentDose(?DateTime $hibMostRecentDose = null): void
    {
        $this->hib_most_recent_dose = $hibMostRecentDose;
    }

    public function setPcvReceived(?VaccinationReceived $pcvReceived = null): void
    {
        $this->pcv_received = $pcvReceived;
    }

    public function setPcvDoses(?FourDoses $pcvDoses = null): void
    {
        $this->pcv_doses = $pcvDoses;
    }

    public function setPcvType(?PCVType $pcvType = null): void
    {
        $this->pcv_type = $pcvType;
    }

    public function setPcvMostRecentDose(?DateTime $pcvMostRecentDose = null): void
    {
        $this->pcv_most_recent_dose = $pcvMostRecentDose;
    }

    public function getMeningReceived(): ?VaccinationReceived
    {
        return $this->mening_received;
    }

    public function getMeningType(): ?VaccinationType
    {
        return $this->mening_type;
    }

    public function getMeningDate(): ?DateTime
    {
        return $this->mening_date;
    }

    public function setMeningReceived(?VaccinationReceived $meningReceived = null): void
    {
        $this->mening_received = $meningReceived;
    }

    public function setMeningType(?VaccinationType $meningType = null): void
    {
        $this->mening_type = $meningType;
    }

    public function setMeningDate(?DateTime $meningMostRecentDose = null): void
    {
        $this->mening_date = $meningMostRecentDose;
    }

    public function setBloodCollectDate(?DateTime $date = null): void
    {
        $this->blood_collect_date = $date;
    }

    public function setBloodCollected(?TripleChoice $bloodCollected = null): void
    {
        $this->blood_collected = $bloodCollected;
    }

    public function setOtherSpecimenCollected(?OtherSpecimen $otherSpecimenCollected = null): void
    {
        $this->other_specimen_collected = $otherSpecimenCollected;
    }

    public function setOtherSpecimenOther(?string $otherSpecimenOther): void
    {
        $this->other_specimen_other = $otherSpecimenOther;
    }

    public function setDischOutcome(?DischargeOutcome $dischOutcome = null): void
    {
        $this->disch_outcome = $dischOutcome;
    }

    public function setDischDx(?DischargeDiagnosis $dischDx = null): void
    {
        $this->disch_dx = $dischDx;
    }

    public function setDischDxOther(?string $dischDxOther): void
    {
        $this->disch_dx_other = $dischDxOther;
    }

    public function setDischClass(?DischargeClassification $dischClass = null): void
    {
        $this->disch_class = $dischClass;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setResult(?CaseResult $result = null): void
    {
        $this->result = $result;
    }

    public function getDischClassOther(): ?string
    {
        return $this->disch_class_other;
    }

    public function setDischClassOther(?string $dischClassOther): void
    {
        $this->disch_class_other= $dischClassOther;
    }

    //========================================
    //PAHO/AMR specific fields

    public function getBloodNumberOfSamples(): ?int
    {
        return $this->blood_number_of_samples;
    }

    public function setBloodNumberOfSamples(?int $blood_number_of_samples): void
    {
        $this->blood_number_of_samples = $blood_number_of_samples;
    }

    public function getBloodSecondCollectDate(): ?DateTime
    {
        return $this->blood_second_collect_date;
    }

    public function setBloodSecondCollectDate(?DateTime $blood_second_collect_date = null): void
    {
        $this->blood_second_collect_date = $blood_second_collect_date;
    }

    public function getBloodSecondCollectTime(): ?DateTime
    {
        return $this->blood_second_collect_time;
    }

    public function setBloodSecondCollectTime(?DateTime $blood_second_collect_time = null): void
    {
        $this->blood_second_collect_time = $blood_second_collect_time;
    }

    public function getPleuralFluidCollected(): ?TripleChoice
    {
        return $this->pleural_fluid_collected;
    }

    public function setPleuralFluidCollected(?TripleChoice $pleural_fluid_collected): void
    {
        $this->pleural_fluid_collected = $pleural_fluid_collected;
    }

    public function getPleuralFluidCollectDate(): ?DateTime
    {
        return $this->pleural_fluid_collect_date;
    }

    public function setPleuralFluidCollectDate(?DateTime $pleural_fluid_collect_date): void
    {
        $this->pleural_fluid_collect_date = $pleural_fluid_collect_date;
    }

    public function getPleuralFluidCollectTime(): ?DateTime
    {
        return $this->pleural_fluid_collect_time;
    }

    public function setPleuralFluidCollectTime(?DateTime $pleural_fluid_collect_time): void
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
