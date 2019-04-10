<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\VaccinationReceived;
use NS\SentinelBundle\Form\RotaVirus\Types\VaccinationType;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use NS\SentinelBundle\Form\RotaVirus\Types\Dehydration;
use NS\SentinelBundle\Form\RotaVirus\Types\Rehydration;
use NS\SentinelBundle\Form\Types\ThreeDoses;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Validators as LocalAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of RotaVirus
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirusRepository")
 * @ORM\Table(name="rotavirus_cases",uniqueConstraints={@ORM\UniqueConstraint(name="rotavirus_site_case_id_idx",columns={"site_id","case_id"})})
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 *
 * @LocalAssert\GreaterThanDate(atPath="adm_date",lessThanField="firstVaccinationDose",greaterThanField="admDate",message="form.validation.vaccination-after-admission")
 * @LocalAssert\GreaterThanDate(atPath="adm_date",lessThanField="secondVaccinationDose",greaterThanField="admDate",message="form.validation.vaccination-after-admission")
 * @LocalAssert\GreaterThanDate(atPath="adm_date",lessThanField="thirdVaccinationDose",greaterThanField="admDate",message="form.validation.vaccination-after-admission")
 * @LocalAssert\GreaterThanDate(atPath="stool_collect_date",lessThanField="admDate",greaterThanField="stoolCollectionDate",message="form.validation.stool-collection-before-admission")
 * @LocalAssert\GreaterThanDate(atPath="disch_date",lessThanField="admDate",greaterThanField="dischargeDate",message="form.validation.stool-collection-before-admission")
 * @LocalAssert\RelatedField(sourceField="stoolCollected",sourceValue={"1"},fields={"stoolCollectionDate"})
 * @LocalAssert\TacPhaseTwo()
 *
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\RotaVirusListener"})
 */
class RotaVirus extends BaseCase
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus\SiteLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $siteLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus\NationalLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $nationalLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus\ReferenceLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     * @Serializer\Groups({"delete"})
     */
    protected $referenceLab;

    /**
     * @Serializer\Exclude()
     */
    protected $siteLabClass   = '\NS\SentinelBundle\Entity\RotaVirus\SiteLab';

    /**
     * @Serializer\Exclude()
     */
    protected $referenceClass = '\NS\SentinelBundle\Entity\RotaVirus\ReferenceLab';

    /**
     * @Serializer\Exclude()
     */
    protected $nationalClass  = '\NS\SentinelBundle\Entity\RotaVirus\NationalLab';

//iii. Case-based Clinical Data

    /**
     * @var TripleChoice $intensiveCare
     * @ORM\Column(name="intensiveCare",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $intensiveCare;

    /**
     * symp_diarrhoea
     * @var TripleChoice $symp_diarrhea
     * @ORM\Column(name="symp_diarrhea",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_diarrhea;

    /**
     * symp_dia_onset_date
     * @var \DateTime $symp_dia_onset_date
     * @ORM\Column(name="symp_dia_onset_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $symp_dia_onset_date;

    /**
     * symp_dia_episodes
     * @var integer $symp_dia_episodes
     * @ORM\Column(name="symp_dia_episodes",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_dia_episodes;

    /**
     * symp_dia_duration
     * @var integer $symp_dia_duration
     * @ORM\Column(name="symp_dia_duration",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_dia_duration;

    /**
     * @var TripleChoice $symp_dia_bloody
     * @ORM\Column(name="symp_dia_bloody",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_dia_bloody;

    /**
     * symp_vomit
     * @var TripleChoice $symp_vomit
     * @ORM\Column(name="symp_vomit",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_vomit;

    /**
     * symp_vomit_episodes
     * @var integer $symp_vomit_episodes
     * @ORM\Column(name="symp_vomit_episodes",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_vomit_episodes;

    /**
     * symp_vomit_duration
     * @var integer $symp_vomit_duration
     * @ORM\Column(name="symp_vomit_duration",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_vomit_duration;

    /**
     * symp_dehydration
     * @var TripleChoice $symp_dehydration
     * @ORM\Column(name="symp_dehydration",type="Dehydration",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_dehydration;

// Treatment
    /**
     * rehydration
     * @var TripleChoice $rehydration
     * @ORM\Column(name="rehydration",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rehydration;

    /**
     * rehydration_type
     * @var Rehydration $rehydration_type
     * @ORM\Column(name="rehydration_type",type="Rehydration",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rehydration_type;

    /**
     * rehydration_type_other
     * @var string $rehydration_other
     * @ORM\Column(name="rehydration_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rehydration_other;

//iv. Case-based Vaccination History
    /**
     * @var VaccinationReceived $rv_received
     * @ORM\Column(name="rv_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rv_received;

    /**
     * RV_type
     * @var VaccinationType $rv_type
     * @ORM\Column(name="rv_type",type="RVVaccinationType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rv_type;

    /**
     * RV_doses
     * @var ThreeDoses $rv_doses
     * @ORM\Column(name="rv_doses",type="ThreeDoses",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rv_doses;

    /**
     * RV_dose1_date
     * @var \DateTime $rv_dose1_date
     * @ORM\Column(name="rv_dose1_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $rv_dose1_date;

    /**
     * RV_dose2_date
     * @var \DateTime $rv_dose2_date
     * @ORM\Column(name="rv_dose2_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $rv_dose2_date;

    /**
     * RV_dose3_date
     * @var \DateTime $rv_dose3_date
     * @ORM\Column(name="rv_dose3_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $rv_dose3_date;

//v. Case-based Specimen Collection Data
    /**
     * stool_collected
     * @var TripleChoice $stool_collected
     * @ORM\Column(name="stool_collected",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank()
     */
    private $stool_collected;

    /**
     * stool_ID
     * @var string $stool_id
     * @ORM\Column(name="stool_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $stool_id;

    /**
     * stool_collect_date
     * @var \DateTime $stool_collect_date
     * @ORM\Column(name="stool_collect_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $stool_collect_date;

//vii. Case-based Outcome Data
    /**
     * disch_outcome
     * @var DischargeOutcome $disch_outcome
     * @ORM\Column(name="disch_outcome",type="RVDischargeOutcome",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_outcome;

    /**
     * @var \DateTime $disch_date
     * @ORM\Column(name="disch_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $disch_date;

    /**
     * @var DischargeClassification
     * @ORM\Column(name="disch_class",type="RVDischargeClassification",nullable=true)
     */
    private $disch_class;

    /**
     * @var string $disch_class_other
     * @ORM\Column(name="disch_class_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_class_other;

    /**
     * comment
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $comment;

    /**
     * @return TripleChoice
     */
    public function getSymptomDiarrhea()
    {
        return $this->symp_diarrhea;
    }

    /**
     * @return \DateTime
     */
    public function getSymptomDiarrheaOnset()
    {
        return $this->symp_dia_onset_date;
    }

    /**
     * @return int
     */
    public function getSymptomDiarrheaEpisodes()
    {
        return $this->symp_dia_episodes;
    }

    /**
     * @return int
     */
    public function getSymptomDiarrheaDuration()
    {
        return $this->symp_dia_duration;
    }

    /**
     * @return TripleChoice
     */
    public function getSymptomDiarrheaBloody()
    {
        return $this->symp_dia_bloody;
    }

    /**
     * @param TripleChoice $symp_dia_bloody
     */
    public function setSymptomDiarrheaBloody($symp_dia_bloody)
    {
        $this->symp_dia_bloody = $symp_dia_bloody;
    }

    /**
     * @return TripleChoice
     */
    public function getSymptomVomit()
    {
        return $this->symp_vomit;
    }

    /**
     * @return int
     */
    public function getSymptomVomitEpisodes()
    {
        return $this->symp_vomit_episodes;
    }

    /**
     * @return int
     */
    public function getSymptomVomitDuration()
    {
        return $this->symp_vomit_duration;
    }

    /**
     * @return TripleChoice
     */
    public function getSymptomDehydration()
    {
        return $this->symp_dehydration;
    }

    /**
     * @return TripleChoice
     */
    public function getRehydration()
    {
        return $this->rehydration;
    }

    /**
     * @return Rehydration
     */
    public function getRehydrationType()
    {
        return $this->rehydration_type;
    }

    /**
     * @return string
     */
    public function getRehydrationOther()
    {
        return $this->rehydration_other;
    }

    /**
     * @return VaccinationReceived
     */
    public function getVaccinationReceived()
    {
        return $this->rv_received;
    }

    /**
     * @return VaccinationType
     */
    public function getVaccinationType()
    {
        return $this->rv_type;
    }

    /**
     * @return ThreeDoses
     */
    public function getDoses()
    {
        return $this->rv_doses;
    }

    /**
     * @return \DateTime
     */
    public function getFirstVaccinationDose()
    {
        return $this->rv_dose1_date;
    }

    /**
     * @return \DateTime
     */
    public function getSecondVaccinationDose()
    {
        return $this->rv_dose2_date;
    }

    /**
     * @return \DateTime
     */
    public function getThirdVaccinationDose()
    {
        return $this->rv_dose3_date;
    }

    /**
     * @return TripleChoice
     */
    public function getStoolCollected()
    {
        return $this->stool_collected;
    }

    /**
     * @return string
     */
    public function getStoolId()
    {
        return $this->stool_id;
    }

    /**
     * @return \DateTime
     */
    public function getStoolCollectionDate()
    {
        return $this->stool_collect_date;
    }

    /**
     * @return DischargeOutcome
     */
    public function getDischargeOutcome()
    {
        return $this->disch_outcome;
    }

    /**
     * @return \DateTime
     */
    public function getDischargeDate()
    {
        return $this->disch_date;
    }

    /**
     * @return string
     */
    public function getDischargeClassOther()
    {
        return $this->disch_class_other;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param TripleChoice $symptomDiarrhea
     * @return $this
     */
    public function setSymptomDiarrhea(TripleChoice $symptomDiarrhea = null)
    {
        $this->symp_diarrhea = $symptomDiarrhea;
        return $this;
    }

    /**
     * @param \DateTime|null $symptomDiarrheaOnset
     * @return $this
     */
    public function setSymptomDiarrheaOnset(\DateTime $symptomDiarrheaOnset = null)
    {
        $this->symp_dia_onset_date = $symptomDiarrheaOnset;

        return $this;
    }

    /**
     * @param $symptomDiarrheaEpisodes
     * @return $this
     */
    public function setSymptomDiarrheaEpisodes($symptomDiarrheaEpisodes)
    {
        $this->symp_dia_episodes = $symptomDiarrheaEpisodes;
        return $this;
    }

    /**
     * @param $symptomDiarrheaDuration
     * @return $this
     */
    public function setSymptomDiarrheaDuration($symptomDiarrheaDuration)
    {
        $this->symp_dia_duration = $symptomDiarrheaDuration;
        return $this;
    }

    /**
     * @param TripleChoice $symptomVomit
     * @return $this
     */
    public function setSymptomVomit(TripleChoice $symptomVomit = null)
    {
        $this->symp_vomit = $symptomVomit;
        return $this;
    }

    /**
     * @param $symptomVomitEpisodes
     * @return $this
     */
    public function setSymptomVomitEpisodes($symptomVomitEpisodes)
    {
        $this->symp_vomit_episodes = $symptomVomitEpisodes;
        return $this;
    }

    /**
     * @param $symptomVomitDuration
     * @return $this
     */
    public function setSymptomVomitDuration($symptomVomitDuration)
    {
        $this->symp_vomit_duration = $symptomVomitDuration;
        return $this;
    }

    /**
     * @param Dehydration $symptomDehydration
     * @return $this
     */
    public function setSymptomDehydration(Dehydration $symptomDehydration = null)
    {
        $this->symp_dehydration = $symptomDehydration;
        return $this;
    }

    /**
     * @param TripleChoice $rehydration
     * @return $this
     */
    public function setRehydration(TripleChoice $rehydration = null)
    {
        $this->rehydration = $rehydration;
        return $this;
    }

    /**
     * @param Rehydration $rehydrationType
     * @return $this
     */
    public function setRehydrationType(Rehydration $rehydrationType = null)
    {
        $this->rehydration_type = $rehydrationType;
        return $this;
    }

    /**
     * @param $rehydrationOther
     * @return $this
     */
    public function setRehydrationOther($rehydrationOther)
    {
        $this->rehydration_other = $rehydrationOther;
        return $this;
    }

    /**
     * @param VaccinationReceived $vaccinationReceived
     * @return $this
     */
    public function setVaccinationReceived(VaccinationReceived $vaccinationReceived = null)
    {
        $this->rv_received = $vaccinationReceived;
        return $this;
    }

    /**
     * @param VaccinationType $vaccinationType
     * @return $this
     */
    public function setVaccinationType(VaccinationType $vaccinationType = null)
    {
        $this->rv_type = $vaccinationType;
        return $this;
    }

    /**
     * @param ThreeDoses $doses
     * @return $this
     */
    public function setDoses(ThreeDoses $doses = null)
    {
        $this->rv_doses = $doses;
        return $this;
    }

    /**
     * @param \DateTime|null $firstVaccinationDose
     * @return $this
     */
    public function setFirstVaccinationDose(\DateTime $firstVaccinationDose = null)
    {
        $this->rv_dose1_date = $firstVaccinationDose;

        return $this;
    }

    /**
     * @param \DateTime|null $secondVaccinationDose
     * @return $this
     */
    public function setSecondVaccinationDose(\DateTime $secondVaccinationDose = null)
    {
        $this->rv_dose2_date = $secondVaccinationDose;

        return $this;
    }

    /**
     * @param \DateTime|null $thirdVaccinationDose
     * @return $this
     */
    public function setThirdVaccinationDose(\DateTime $thirdVaccinationDose = null)
    {
        $this->rv_dose3_date = $thirdVaccinationDose;

        return $this;
    }

    /**
     * @param TripleChoice $stoolCollected
     * @return $this
     */
    public function setStoolCollected(TripleChoice $stoolCollected = null)
    {
        $this->stool_collected = $stoolCollected;
        return $this;
    }

    /**
     * @param $stoolId
     * @return $this
     */
    public function setStoolId($stoolId)
    {
        $this->stool_id = $stoolId;
        return $this;
    }

    /**
     * @param \DateTime|null $stoolCollectionDate
     * @return $this
     */
    public function setStoolCollectionDate(\DateTime $stoolCollectionDate = null)
    {
        $this->stool_collect_date = $stoolCollectionDate;

        return $this;
    }

    /**
     * @param DischargeOutcome $dischargeOutcome
     * @return $this
     */
    public function setDischargeOutcome(DischargeOutcome $dischargeOutcome = null)
    {
        $this->disch_outcome = $dischargeOutcome;
        return $this;
    }

    /**
     * @param \DateTime|null $dischargeDate
     * @return $this
     */
    public function setDischargeDate(\DateTime $dischargeDate = null)
    {
        $this->disch_date = $dischargeDate;

        return $this;
    }

    /**
     * @return DischargeClassification
     */
    public function getDischargeClassification()
    {
        return $this->disch_class;
    }

    /**
     * @return DischargeClassification
     */
    public function getDischClass()
    {
        return $this->disch_class;
    }

    /**
     * @param DischargeClassification $disch_class
     * @return RotaVirus
     */
    public function setDischClass(DischargeClassification $disch_class = null)
    {
        $this->disch_class = $disch_class;
        return $this;
    }

    /**
     * @param DischargeClassification|null $disch_class
     * @return $this
     */
    public function setDischargeClassification(DischargeClassification $disch_class = null)
    {
        $this->disch_class = $disch_class;
        return $this;
    }

    /**
     * @param $dischargeClassOther
     * @return $this
     */
    public function setDischargeClassOther($dischargeClassOther)
    {
        $this->disch_class_other = $dischargeClassOther;
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
     * 
     * @return TripleChoice
     */
    public function getIntensiveCare()
    {
        return $this->intensiveCare;
    }

    /**
     *
     * @param TripleChoice $intensiveCare
     * @return \NS\SentinelBundle\Entity\RotaVirus
     */
    public function setIntensiveCare(TripleChoice $intensiveCare = null)
    {
        $this->intensiveCare = $intensiveCare;
        return $this;
    }

    /**
     * @return TripleChoice
     */
    public function getSympDiarrhea()
    {
        return $this->symp_diarrhea;
    }

    /**
     * @return \DateTime
     */
    public function getSympDiaOnsetDate()
    {
        return $this->symp_dia_onset_date;
    }

    /**
     * @return int
     */
    public function getSympDiaEpisodes()
    {
        return $this->symp_dia_episodes;
    }

    /**
     * @return int
     */
    public function getSympDiaDuration()
    {
        return $this->symp_dia_duration;
    }

    /**
     * @return TripleChoice
     */
    public function getSympDiaBloody()
    {
        return $this->symp_dia_bloody;
    }

    /**
     * @return TripleChoice
     */
    public function getSympVomit()
    {
        return $this->symp_vomit;
    }

    /**
     * @return int
     */
    public function getSympVomitEpisodes()
    {
        return $this->symp_vomit_episodes;
    }

    /**
     * @return int
     */
    public function getSympVomitDuration()
    {
        return $this->symp_vomit_duration;
    }

    /**
     * @return TripleChoice
     */
    public function getSympDehydration()
    {
        return $this->symp_dehydration;
    }

    /**
     * @return VaccinationReceived
     */
    public function getRvReceived()
    {
        return $this->rv_received;
    }

    /**
     * @return VaccinationType
     */
    public function getRvType()
    {
        return $this->rv_type;
    }

    /**
     * @return ThreeDoses
     */
    public function getRvDoses()
    {
        return $this->rv_doses;
    }

    /**
     * @return \DateTime
     */
    public function getRvDose1Date()
    {
        return $this->rv_dose1_date;
    }

    /**
     * @return \DateTime
     */
    public function getRvDose2Date()
    {
        return $this->rv_dose2_date;
    }

    /**
     * @return \DateTime
     */
    public function getRvDose3Date()
    {
        return $this->rv_dose3_date;
    }

    /**
     * @return \DateTime
     */
    public function getStoolCollectDate()
    {
        return $this->stool_collect_date;
    }

    /**
     * @return DischargeOutcome
     */
    public function getDischOutcome()
    {
        return $this->disch_outcome;
    }

    /**
     * @return \DateTime
     */
    public function getDischDate()
    {
        return $this->disch_date;
    }

    /**
     * @return string
     */
    public function getDischClassOther()
    {
        return $this->disch_class_other;
    }

    /**
     * @param TripleChoice $symp_diarrhea
     */
    public function setSympDiarrhea($symp_diarrhea)
    {
        $this->symp_diarrhea = $symp_diarrhea;
    }

    /**
     * @param \DateTime $symp_dia_onset_date
     */
    public function setSympDiaOnsetDate($symp_dia_onset_date)
    {
        $this->symp_dia_onset_date = $symp_dia_onset_date;
    }

    /**
     * @param int $symp_dia_episodes
     */
    public function setSympDiaEpisodes($symp_dia_episodes)
    {
        $this->symp_dia_episodes = $symp_dia_episodes;
    }

    /**
     * @param int $symp_dia_duration
     */
    public function setSympDiaDuration($symp_dia_duration)
    {
        $this->symp_dia_duration = $symp_dia_duration;
    }

    /**
     * @param TripleChoice $symp_dia_bloody
     */
    public function setSympDiaBloody($symp_dia_bloody)
    {
        $this->symp_dia_bloody = $symp_dia_bloody;
    }

    /**
     * @param TripleChoice $symp_vomit
     */
    public function setSympVomit($symp_vomit)
    {
        $this->symp_vomit = $symp_vomit;
    }

    /**
     * @param int $symp_vomit_episodes
     */
    public function setSympVomitEpisodes($symp_vomit_episodes)
    {
        $this->symp_vomit_episodes = $symp_vomit_episodes;
    }

    /**
     * @param int $symp_vomit_duration
     */
    public function setSympVomitDuration($symp_vomit_duration)
    {
        $this->symp_vomit_duration = $symp_vomit_duration;
    }

    /**
     * @param TripleChoice $symp_dehydration
     */
    public function setSympDehydration($symp_dehydration)
    {
        $this->symp_dehydration = $symp_dehydration;
    }

    /**
     * @param VaccinationReceived $rv_received
     */
    public function setRvReceived($rv_received)
    {
        $this->rv_received = $rv_received;
    }

    /**
     * @param VaccinationType $rv_type
     */
    public function setRvType($rv_type)
    {
        $this->rv_type = $rv_type;
    }

    /**
     * @param ThreeDoses $rv_doses
     */
    public function setRvDoses($rv_doses)
    {
        $this->rv_doses = $rv_doses;
    }

    /**
     * @param \DateTime $rv_dose1_date
     */
    public function setRvDose1Date($rv_dose1_date)
    {
        $this->rv_dose1_date = $rv_dose1_date;
    }

    /**
     * @param \DateTime $rv_dose2_date
     */
    public function setRvDose2Date($rv_dose2_date)
    {
        $this->rv_dose2_date = $rv_dose2_date;
    }

    /**
     * @param \DateTime $rv_dose3_date
     */
    public function setRvDose3Date($rv_dose3_date)
    {
        $this->rv_dose3_date = $rv_dose3_date;
    }

    /**
     * @param \DateTime $stool_collect_date
     */
    public function setStoolCollectDate($stool_collect_date)
    {
        $this->stool_collect_date = $stool_collect_date;
    }

    /**
     * @param DischargeOutcome $disch_outcome
     */
    public function setDischOutcome($disch_outcome)
    {
        $this->disch_outcome = $disch_outcome;
    }

    /**
     * @param \DateTime $disch_date
     */
    public function setDischDate($disch_date)
    {
        $this->disch_date = $disch_date;
    }

    /**
     * @param string $disch_class_other
     */
    public function setDischClassOther($disch_class_other)
    {
        $this->disch_class_other = $disch_class_other;
    }
//
//    /**
//     * Get sentToReferenceLab
//     *
//     * @return boolean
//     */
//    public function getSentToReferenceLab()
//    {
//        return $this->siteLab && $this->siteLab->getSentToReferenceLab();
//    }
}
