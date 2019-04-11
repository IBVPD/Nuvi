<?php

namespace NS\SentinelBundle\Entity;

use DateTime;
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
use NS\SentinelBundle\Entity\RotaVirus\SiteLab;
use NS\SentinelBundle\Entity\RotaVirus\ReferenceLab;
use NS\SentinelBundle\Entity\RotaVirus\NationalLab;

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
    protected $siteLabClass   = SiteLab::class;

    /**
     * @Serializer\Exclude()
     */
    protected $referenceClass = ReferenceLab::class;

    /**
     * @Serializer\Exclude()
     */
    protected $nationalClass  = NationalLab::class;

//iii. Case-based Clinical Data

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="intensiveCare",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $intensiveCare;

    /**
     * symp_diarrhoea
     * @var TripleChoice|null
     * @ORM\Column(name="symp_diarrhea",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_diarrhea;

    /**
     * symp_dia_onset_date
     * @var DateTime|null
     * @ORM\Column(name="symp_dia_onset_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $symp_dia_onset_date;

    /**
     * symp_dia_episodes
     * @var int|null
     * @ORM\Column(name="symp_dia_episodes",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_dia_episodes;

    /**
     * symp_dia_duration
     * @var int|null
     * @ORM\Column(name="symp_dia_duration",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_dia_duration;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="symp_dia_bloody",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_dia_bloody;

    /**
     * symp_vomit
     * @var TripleChoice|null
     * @ORM\Column(name="symp_vomit",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_vomit;

    /**
     * symp_vomit_episodes
     * @var int|null
     * @ORM\Column(name="symp_vomit_episodes",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_vomit_episodes;

    /**
     * symp_vomit_duration
     * @var int|null
     * @ORM\Column(name="symp_vomit_duration",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_vomit_duration;

    /**
     * symp_dehydration
     * @var Dehydration|null
     * @ORM\Column(name="symp_dehydration",type="Dehydration",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $symp_dehydration;

// Treatment
    /**
     * rehydration
     * @var TripleChoice|null
     * @ORM\Column(name="rehydration",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rehydration;

    /**
     * rehydration_type
     * @var Rehydration|null
     * @ORM\Column(name="rehydration_type",type="Rehydration",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rehydration_type;

    /**
     * rehydration_type_other
     * @var string|null
     * @ORM\Column(name="rehydration_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rehydration_other;

//iv. Case-based Vaccination History
    /**
     * @var VaccinationReceived|null
     * @ORM\Column(name="rv_received",type="VaccinationReceived",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rv_received;

    /**
     * RV_type
     * @var VaccinationType|null
     * @ORM\Column(name="rv_type",type="RVVaccinationType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rv_type;

    /**
     * RV_doses
     * @var ThreeDoses|null
     * @ORM\Column(name="rv_doses",type="ThreeDoses",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $rv_doses;

    /**
     * RV_dose1_date
     * @var DateTime|null
     * @ORM\Column(name="rv_dose1_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $rv_dose1_date;

    /**
     * RV_dose2_date
     * @var DateTime|null
     * @ORM\Column(name="rv_dose2_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $rv_dose2_date;

    /**
     * RV_dose3_date
     * @var DateTime|null
     * @ORM\Column(name="rv_dose3_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $rv_dose3_date;

//v. Case-based Specimen Collection Data
    /**
     * stool_collected
     * @var TripleChoice|null
     * @ORM\Column(name="stool_collected",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank()
     */
    private $stool_collected;

    /**
     * stool_ID
     * @var string|null
     * @ORM\Column(name="stool_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $stool_id;

    /**
     * stool_collect_date
     * @var DateTime|null
     * @ORM\Column(name="stool_collect_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $stool_collect_date;

//vii. Case-based Outcome Data
    /**
     * disch_outcome
     * @var DischargeOutcome|null
     * @ORM\Column(name="disch_outcome",type="RVDischargeOutcome",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_outcome;

    /**
     * @var DateTime|null
     * @ORM\Column(name="disch_date",type="date",nullable=true)
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\NoFutureDate
     */
    private $disch_date;

    /**
     * @var DischargeClassification|null
     * @ORM\Column(name="disch_class",type="RVDischargeClassification",nullable=true)
     */
    private $disch_class;

    /**
     * @var string|null
     * @ORM\Column(name="disch_class_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $disch_class_other;

    /**
     * comment
     * @var string|null
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $comment;

    public function getSymptomDiarrhea(): ?TripleChoice
    {
        return $this->symp_diarrhea;
    }

    public function getSymptomDiarrheaOnset(): ?DateTime
    {
        return $this->symp_dia_onset_date;
    }

    public function getSymptomDiarrheaEpisodes(): ?int
    {
        return $this->symp_dia_episodes;
    }

    public function getSymptomDiarrheaDuration(): ?int
    {
        return $this->symp_dia_duration;
    }

    public function getSymptomDiarrheaBloody(): ?TripleChoice
    {
        return $this->symp_dia_bloody;
    }

    public function setSymptomDiarrheaBloody(?TripleChoice $symp_dia_bloody): void
    {
        $this->symp_dia_bloody = $symp_dia_bloody;
    }

    public function getSymptomVomit(): ?TripleChoice
    {
        return $this->symp_vomit;
    }

    public function getSymptomVomitEpisodes(): ?int
    {
        return $this->symp_vomit_episodes;
    }

    public function getSymptomVomitDuration(): ?int
    {
        return $this->symp_vomit_duration;
    }

    public function getSymptomDehydration(): ?Dehydration
    {
        return $this->symp_dehydration;
    }

    public function getRehydration(): ?TripleChoice
    {
        return $this->rehydration;
    }

    public function getRehydrationType(): ?Rehydration
    {
        return $this->rehydration_type;
    }

    public function getRehydrationOther(): ?string
    {
        return $this->rehydration_other;
    }

    public function getVaccinationReceived(): ?VaccinationReceived
    {
        return $this->rv_received;
    }

    public function getVaccinationType(): ?VaccinationType
    {
        return $this->rv_type;
    }

    public function getDoses(): ?ThreeDoses
    {
        return $this->rv_doses;
    }

    public function getFirstVaccinationDose(): ?DateTime
    {
        return $this->rv_dose1_date;
    }

    public function getSecondVaccinationDose(): ?DateTime
    {
        return $this->rv_dose2_date;
    }

    public function getThirdVaccinationDose(): ?DateTime
    {
        return $this->rv_dose3_date;
    }

    public function getStoolCollected(): ?TripleChoice
    {
        return $this->stool_collected;
    }

    public function getStoolId(): ?string
    {
        return $this->stool_id;
    }

    public function getStoolCollectionDate(): ?DateTime
    {
        return $this->stool_collect_date;
    }

    public function getDischargeOutcome(): ?DischargeOutcome
    {
        return $this->disch_outcome;
    }

    public function getDischargeDate(): ?DateTime
    {
        return $this->disch_date;
    }

    public function getDischargeClassOther(): ?string
    {
        return $this->disch_class_other;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setSymptomDiarrhea(?TripleChoice $symptomDiarrhea = null): void
    {
        $this->symp_diarrhea = $symptomDiarrhea;
    }

    public function setSymptomDiarrheaOnset(DateTime $symptomDiarrheaOnset = null): void
    {
        $this->symp_dia_onset_date = $symptomDiarrheaOnset;
    }

    public function setSymptomDiarrheaEpisodes(?int $symptomDiarrheaEpisodes): void
    {
        $this->symp_dia_episodes = $symptomDiarrheaEpisodes;
    }

    public function setSymptomDiarrheaDuration(?int $symptomDiarrheaDuration): void
    {
        $this->symp_dia_duration = $symptomDiarrheaDuration;
    }

    public function setSymptomVomit(TripleChoice $symptomVomit = null): void
    {
        $this->symp_vomit = $symptomVomit;
    }

    public function setSymptomVomitEpisodes(?int $symptomVomitEpisodes): void
    {
        $this->symp_vomit_episodes = $symptomVomitEpisodes;
    }

    public function setSymptomVomitDuration(?int $symptomVomitDuration): void
    {
        $this->symp_vomit_duration = $symptomVomitDuration;
    }

    public function setSymptomDehydration(Dehydration $symptomDehydration = null): void
    {
        $this->symp_dehydration = $symptomDehydration;
    }

    public function setRehydration(TripleChoice $rehydration = null): void
    {
        $this->rehydration = $rehydration;
    }

    public function setRehydrationType(Rehydration $rehydrationType = null): void
    {
        $this->rehydration_type = $rehydrationType;
    }

    public function setRehydrationOther(?string $rehydrationOther): void
    {
        $this->rehydration_other = $rehydrationOther;
    }

    public function setVaccinationReceived(VaccinationReceived $vaccinationReceived = null): void
    {
        $this->rv_received = $vaccinationReceived;
    }

    public function setVaccinationType(VaccinationType $vaccinationType = null): void
    {
        $this->rv_type = $vaccinationType;
    }

    public function setDoses(ThreeDoses $doses = null): void
    {
        $this->rv_doses = $doses;
    }

    public function setFirstVaccinationDose(DateTime $firstVaccinationDose = null): void
    {
        $this->rv_dose1_date = $firstVaccinationDose;
    }

    public function setSecondVaccinationDose(DateTime $secondVaccinationDose = null): void
    {
        $this->rv_dose2_date = $secondVaccinationDose;
    }

    public function setThirdVaccinationDose(DateTime $thirdVaccinationDose = null): void
    {
        $this->rv_dose3_date = $thirdVaccinationDose;
    }

    public function setStoolCollected(TripleChoice $stoolCollected = null): void
    {
        $this->stool_collected = $stoolCollected;
    }

    public function setStoolId(?string $stoolId): void
    {
        $this->stool_id = $stoolId;
    }

    public function setStoolCollectionDate(DateTime $stoolCollectionDate = null): void
    {
        $this->stool_collect_date = $stoolCollectionDate;
    }

    public function setDischargeOutcome(DischargeOutcome $dischargeOutcome = null): void
    {
        $this->disch_outcome = $dischargeOutcome;
    }

    public function setDischargeDate(DateTime $dischargeDate = null): void
    {
        $this->disch_date = $dischargeDate;
    }

    public function getDischargeClassification(): ?DischargeClassification
    {
        return $this->disch_class;
    }

    public function getDischClass(): ?DischargeClassification
    {
        return $this->disch_class;
    }

    public function setDischClass(DischargeClassification $disch_class = null): void
    {
        $this->disch_class = $disch_class;
    }

    public function setDischargeClassification(DischargeClassification $disch_class = null): void
    {
        $this->disch_class = $disch_class;
    }

    public function setDischargeClassOther(?string $dischargeClassOther): void
    {
        $this->disch_class_other = $dischargeClassOther;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getIntensiveCare(): ?TripleChoice
    {
        return $this->intensiveCare;
    }

    public function setIntensiveCare(TripleChoice $intensiveCare = null): void
    {
        $this->intensiveCare = $intensiveCare;
    }

    public function getSympDiarrhea(): ?TripleChoice
    {
        return $this->symp_diarrhea;
    }

    public function getSympDiaOnsetDate(): ?DateTime
    {
        return $this->symp_dia_onset_date;
    }

    public function getSympDiaEpisodes(): ?int
    {
        return $this->symp_dia_episodes;
    }

    public function getSympDiaDuration(): ?int
    {
        return $this->symp_dia_duration;
    }

    public function getSympDiaBloody(): ?TripleChoice
    {
        return $this->symp_dia_bloody;
    }

    public function getSympVomit(): ?TripleChoice
    {
        return $this->symp_vomit;
    }

    public function getSympVomitEpisodes(): ?int
    {
        return $this->symp_vomit_episodes;
    }

    public function getSympVomitDuration(): ?int
    {
        return $this->symp_vomit_duration;
    }

    public function getSympDehydration(): ?Dehydration
    {
        return $this->symp_dehydration;
    }

    public function getRvReceived(): ?VaccinationReceived
    {
        return $this->rv_received;
    }

    public function getRvType(): ?VaccinationType
    {
        return $this->rv_type;
    }

    public function getRvDoses(): ?ThreeDoses
    {
        return $this->rv_doses;
    }

    public function getRvDose1Date(): ?DateTime
    {
        return $this->rv_dose1_date;
    }

    public function getRvDose2Date(): ?DateTime
    {
        return $this->rv_dose2_date;
    }

    public function getRvDose3Date(): ?DateTime
    {
        return $this->rv_dose3_date;
    }

    public function getStoolCollectDate(): ?DateTime
    {
        return $this->stool_collect_date;
    }

    public function getDischOutcome(): ?DischargeOutcome
    {
        return $this->disch_outcome;
    }

    public function getDischDate(): ?DateTime
    {
        return $this->disch_date;
    }

    public function getDischClassOther(): ?string
    {
        return $this->disch_class_other;
    }

    public function setSympDiarrhea(TripleChoice $symp_diarrhea): void
    {
        $this->symp_diarrhea = $symp_diarrhea;
    }

    public function setSympDiaOnsetDate(?DateTime $symp_dia_onset_date): void
    {
        $this->symp_dia_onset_date = $symp_dia_onset_date;
    }

    public function setSympDiaEpisodes(?int $symp_dia_episodes): void
    {
        $this->symp_dia_episodes = $symp_dia_episodes;
    }

    public function setSympDiaDuration(?int $symp_dia_duration): void
    {
        $this->symp_dia_duration = $symp_dia_duration;
    }

    public function setSympDiaBloody(TripleChoice $symp_dia_bloody): void
    {
        $this->symp_dia_bloody = $symp_dia_bloody;
    }

    public function setSympVomit(TripleChoice$symp_vomit): void
    {
        $this->symp_vomit = $symp_vomit;
    }

    public function setSympVomitEpisodes(?int $symp_vomit_episodes): void
    {
        $this->symp_vomit_episodes = $symp_vomit_episodes;
    }

    public function setSympVomitDuration(?int $symp_vomit_duration): void
    {
        $this->symp_vomit_duration = $symp_vomit_duration;
    }

    public function setSympDehydration(?Dehydration $symp_dehydration): void
    {
        $this->symp_dehydration = $symp_dehydration;
    }

    public function setRvReceived(VaccinationReceived $rv_received): void
    {
        $this->rv_received = $rv_received;
    }

    public function setRvType(VaccinationType $rv_type): void
    {
        $this->rv_type = $rv_type;
    }

    public function setRvDoses(ThreeDoses $rv_doses): void
    {
        $this->rv_doses = $rv_doses;
    }

    public function setRvDose1Date(?DateTime $rv_dose1_date): void
    {
        $this->rv_dose1_date = $rv_dose1_date;
    }

    public function setRvDose2Date(?DateTime $rv_dose2_date): void
    {
        $this->rv_dose2_date = $rv_dose2_date;
    }

    public function setRvDose3Date(?DateTime $rv_dose3_date): void
    {
        $this->rv_dose3_date = $rv_dose3_date;
    }

    public function setStoolCollectDate(?DateTime $stool_collect_date): void
    {
        $this->stool_collect_date = $stool_collect_date;
    }

    public function setDischOutcome(?DischargeOutcome $disch_outcome): void
    {
        $this->disch_outcome = $disch_outcome;
    }

    public function setDischDate(?DateTime $disch_date): void
    {
        $this->disch_date = $disch_date;
    }

    public function setDischClassOther(?string $disch_class_other): void
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
