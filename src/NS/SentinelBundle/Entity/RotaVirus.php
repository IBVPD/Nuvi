<?php

namespace NS\SentinelBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived;
use \NS\SentinelBundle\Form\Types\RotavirusVaccinationType;
use \NS\SentinelBundle\Form\Types\Dehydration;
use \NS\SentinelBundle\Form\Types\Rehydration;
use \NS\SentinelBundle\Form\Types\ThreeDoses;
use \NS\SentinelBundle\Form\Types\RotavirusDischargeOutcome;
use \Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
use \JMS\Serializer\Annotation\Groups;
use \JMS\Serializer\Annotation\Exclude;

/**
 * Description of RotaVirus
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirusRepository")
 * @ORM\Table(name="rotavirus_cases",uniqueConstraints={@ORM\UniqueConstraint(name="rotavirus_site_case_id_idx",columns={"site_id","case_id"})})
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\RotaVirusListener"})
 */
class RotaVirus extends BaseCase
{
    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Rota\SiteLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $siteLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Rota\NationalLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $nationalLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Rota\ReferenceLab", mappedBy="caseFile", cascade={"persist","remove"}, orphanRemoval=true)
     */
    protected $referenceLab;

    /**
     * @Exclude()
     */
    protected $siteLabClass   = '\NS\SentinelBundle\Entity\Rota\SiteLab';
    /**
     * @Exclude()
     */
    protected $referenceClass = '\NS\SentinelBundle\Entity\Rota\ReferenceLab';

    /**
     * @Exclude()
     */
    protected $nationalClass  = '\NS\SentinelBundle\Entity\Rota\NationalLab';

//iii. Case-based Clinical Data

    /**
     * @var TripleChoice $intensiveCare
     * @ORM\Column(name="intensiveCare",type="TripleChoice",nullable=true)
     */
    private $intensiveCare;

    /**
     * symp_diarrhoea
     * @var TripleChoice $symp_diarrhea
     * @ORM\Column(name="symp_diarrhea",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $symp_diarrhea;

    /**
     * symp_dia_onset_date
     * @var \DateTime $symp_dia_onset_date
     * @ORM\Column(name="symp_dia_onset_date",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $symp_dia_onset_date;

    /**
     * symp_dia_episodes
     * @var integer $symp_dia_episodes
     * @ORM\Column(name="symp_dia_episodes",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $symp_dia_episodes;

    /**
     * symp_dia_duration
     * @var integer $symp_dia_duration
     * @ORM\Column(name="symp_dia_duration",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $symp_dia_duration;

    /**
     * symp_vomit
     * @var TripleChoice $symp_vomit
     * @ORM\Column(name="symp_vomit",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $symp_vomit;

    /**
     * symp_vomit_episodes
     * @var integer $symp_vomit_episodes
     * @ORM\Column(name="symp_vomit_episodes",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $symp_vomit_episodes;

    /**
     * symp_vomit_duration
     * @var integer $symp_vomit_duration
     * @ORM\Column(name="symp_vomit_duration",type="integer",nullable=true)
     * @Groups({"api"})
     */
    private $symp_vomit_duration;

    /**
     * symp_dehydration
     * @var TripleChoice $symp_dehydration
     * @ORM\Column(name="symp_dehydration",type="Dehydration",nullable=true)
     * @Groups({"api"})
     */
    private $symp_dehydration;

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
     * @var Rehydration $rehydration_type
     * @ORM\Column(name="rehydration_type",type="Rehydration",nullable=true)
     * @Groups({"api"})
     */
    private $rehydration_type;

    /**
     * rehydration_type_other
     * @var string $rehydration_other
     * @ORM\Column(name="rehydration_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $rehydration_other;

//iv. Case-based Vaccination History
    /**
     * @var RotavirusVaccinationReceived $rv_received
     * @ORM\Column(name="rv_received",type="RotavirusVaccinationReceived",nullable=true)
     * @Groups({"api"})
     */
    private $rv_received;

    /**
     * RV_type
     * @var RotavirusVaccinationType $rv_type
     * @ORM\Column(name="rv_type",type="RotavirusVaccinationType",nullable=true)
     * @Groups({"api"})
     */
    private $rv_type;

    /**
     * RV_doses
     * @var ThreeDoses $rv_doses
     * @ORM\Column(name="rv_doses",type="ThreeDoses",nullable=true)
     * @Groups({"api"})
     */
    private $rv_doses;

    /**
     * RV_dose1_date
     * @var \DateTime $rv_dose1_date
     * @ORM\Column(name="rv_dose1_date",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $rv_dose1_date;

    /**
     * RV_dose2_date
     * @var \DateTime $rv_dose2_date
     * @ORM\Column(name="rv_dose2_date",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $rv_dose2_date;

    /**
     * RV_dose3_date
     * @var \DateTime $rv_dose3_date
     * @ORM\Column(name="rv_dose3_date",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $rv_dose3_date;

//v. Case-based Specimen Collection Data
    /**
     * stool_collected
     * @var TripleChoice $stool_collected
     * @ORM\Column(name="stool_collected",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $stool_collected;

    /**
     * stool_ID
     * @var string $stool_id
     * @ORM\Column(name="stool_id",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $stool_id;

    /**
     * stool_collect_date
     * @var \DateTime $stool_collect_date
     * @ORM\Column(name="stool_collect_date",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $stool_collect_date;

//vii. Case-based Outcome Data
    /**
     * disch_outcome
     * @var RotavirusDischargeOutcome $disch_outcome
     * @ORM\Column(name="disch_outcome",type="RotavirusDischargeOutcome",nullable=true)
     * @Groups({"api"})
     */
    private $disch_outcome;

    /**
     * @var \DateTime $disch_date
     * @ORM\Column(name="disch_date",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $disch_date;

    /**
     * @var string $disch_class_other
     * @ORM\Column(name="disch_class_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $disch_class_other;

    /**
     * comment
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Groups({"api"})
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
        return $this->symp_dia_duration;
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
     * @return RotavirusVaccinationReceived
     */
    public function getVaccinationReceived()
    {
        return $this->rv_received;
    }

    /**
     * @return RotavirusVaccinationType
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
     * @return RotavirusDischargeOutcome
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
        $this->symp_dia_episodes = $symptomVomitEpisodes;
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
     * @param RotavirusVaccinationReceived $vaccinationReceived
     * @return $this
     */
    public function setVaccinationReceived(RotavirusVaccinationReceived $vaccinationReceived = null)
    {
        $this->rv_received = $vaccinationReceived;
        return $this;
    }

    /**
     * @param RotavirusVaccinationType $vaccinationType
     * @return $this
     */
    public function setVaccinationType(RotavirusVaccinationType $vaccinationType = null)
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
     * @param RotavirusDischargeOutcome $dischargeOutcome
     * @return $this
     */
    public function setDischargeOutcome(RotavirusDischargeOutcome $dischargeOutcome = null)
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
}
