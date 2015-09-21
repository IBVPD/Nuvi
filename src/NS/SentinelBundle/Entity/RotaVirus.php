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
 * @ORM\Table(name="rotavirus_cases",uniqueConstraints={@ORM\UniqueConstraint(name="rotavirus_site_case_id_idx",columns={"site_id","caseId"})})
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
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Rota\SiteLab", mappedBy="caseFile",cascade={"persist"})
     */
    protected $siteLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Rota\NationalLab", mappedBy="caseFile",cascade={"persist"})
     */
    protected $nationalLab;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Rota\ReferenceLab", mappedBy="caseFile",cascade={"persist"})
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

    /**
     * @return TripleChoice
     */
    public function getSymptomDiarrhea()
    {
        return $this->symptomDiarrhea;
    }

    /**
     * @return \DateTime
     */
    public function getSymptomDiarrheaOnset()
    {
        return $this->symptomDiarrheaOnset;
    }

    /**
     * @return int
     */
    public function getSymptomDiarrheaEpisodes()
    {
        return $this->symptomDiarrheaEpisodes;
    }

    /**
     * @return int
     */
    public function getSymptomDiarrheaDuration()
    {
        return $this->symptomDiarrheaDuration;
    }

    /**
     * @return TripleChoice
     */
    public function getSymptomVomit()
    {
        return $this->symptomVomit;
    }

    /**
     * @return int
     */
    public function getSymptomVomitEpisodes()
    {
        return $this->symptomVomitEpisodes;
    }

    /**
     * @return int
     */
    public function getSymptomVomitDuration()
    {
        return $this->symptomVomitDuration;
    }

    /**
     * @return TripleChoice
     */
    public function getSymptomDehydration()
    {
        return $this->symptomDehydration;
    }

    /**
     * @return Dehydration
     */
    public function getSymptomDehydrationAmount()
    {
        return $this->symptomDehydrationAmount;
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
        return $this->rehydrationType;
    }

    /**
     * @return string
     */
    public function getRehydrationOther()
    {
        return $this->rehydrationOther;
    }

    /**
     * @return RotavirusVaccinationReceived
     */
    public function getVaccinationReceived()
    {
        return $this->vaccinationReceived;
    }

    /**
     * @return RotavirusVaccinationType
     */
    public function getVaccinationType()
    {
        return $this->vaccinationType;
    }

    /**
     * @return ThreeDoses
     */
    public function getDoses()
    {
        return $this->doses;
    }

    /**
     * @return \DateTime
     */
    public function getFirstVaccinationDose()
    {
        return $this->firstVaccinationDose;
    }

    /**
     * @return \DateTime
     */
    public function getSecondVaccinationDose()
    {
        return $this->secondVaccinationDose;
    }

    /**
     * @return \DateTime
     */
    public function getThirdVaccinationDose()
    {
        return $this->thirdVaccinationDose;
    }

    /**
     * @return TripleChoice
     */
    public function getStoolCollected()
    {
        return $this->stoolCollected;
    }

    /**
     * @return string
     */
    public function getStoolId()
    {
        return $this->stoolId;
    }

    /**
     * @return \DateTime
     */
    public function getStoolCollectionDate()
    {
        return $this->stoolCollectionDate;
    }

    /**
     * @return RotavirusDischargeOutcome
     */
    public function getDischargeOutcome()
    {
        return $this->dischargeOutcome;
    }

    /**
     * @return \DateTime
     */
    public function getDischargeDate()
    {
        return $this->dischargeDate;
    }

    /**
     * @return string
     */
    public function getDischargeClassOther()
    {
        return $this->dischargeClassOther;
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
    public function setSymptomDiarrhea(TripleChoice $symptomDiarrhea)
    {
        $this->symptomDiarrhea = $symptomDiarrhea;
        return $this;
    }

    /**
     * @param \DateTime|null $symptomDiarrheaOnset
     * @return $this
     */
    public function setSymptomDiarrheaOnset(\DateTime $symptomDiarrheaOnset = null)
    {
        $this->symptomDiarrheaOnset = $symptomDiarrheaOnset;

        return $this;
    }

    /**
     * @param $symptomDiarrheaEpisodes
     * @return $this
     */
    public function setSymptomDiarrheaEpisodes($symptomDiarrheaEpisodes)
    {
        $this->symptomDiarrheaEpisodes = $symptomDiarrheaEpisodes;
        return $this;
    }

    /**
     * @param $symptomDiarrheaDuration
     * @return $this
     */
    public function setSymptomDiarrheaDuration($symptomDiarrheaDuration)
    {
        $this->symptomDiarrheaDuration = $symptomDiarrheaDuration;
        return $this;
    }

    /**
     * @param TripleChoice $symptomVomit
     * @return $this
     */
    public function setSymptomVomit(TripleChoice $symptomVomit)
    {
        $this->symptomVomit = $symptomVomit;
        return $this;
    }

    /**
     * @param $symptomVomitEpisodes
     * @return $this
     */
    public function setSymptomVomitEpisodes($symptomVomitEpisodes)
    {
        $this->symptomVomitEpisodes = $symptomVomitEpisodes;
        return $this;
    }

    /**
     * @param $symptomVomitDuration
     * @return $this
     */
    public function setSymptomVomitDuration($symptomVomitDuration)
    {
        $this->symptomVomitDuration = $symptomVomitDuration;
        return $this;
    }

    /**
     * @param TripleChoice $symptomDehydration
     * @return $this
     */
    public function setSymptomDehydration(TripleChoice $symptomDehydration)
    {
        $this->symptomDehydration = $symptomDehydration;
        return $this;
    }

    /**
     * @param Dehydration $symptomDehydrationAmount
     * @return $this
     */
    public function setSymptomDehydrationAmount(Dehydration $symptomDehydrationAmount)
    {
        $this->symptomDehydrationAmount = $symptomDehydrationAmount;
        return $this;
    }

    /**
     * @param TripleChoice $rehydration
     * @return $this
     */
    public function setRehydration(TripleChoice $rehydration)
    {
        $this->rehydration = $rehydration;
        return $this;
    }

    /**
     * @param Rehydration $rehydrationType
     * @return $this
     */
    public function setRehydrationType(Rehydration $rehydrationType)
    {
        $this->rehydrationType = $rehydrationType;
        return $this;
    }

    /**
     * @param $rehydrationOther
     * @return $this
     */
    public function setRehydrationOther($rehydrationOther)
    {
        $this->rehydrationOther = $rehydrationOther;
        return $this;
    }

    /**
     * @param RotavirusVaccinationReceived $vaccinationReceived
     * @return $this
     */
    public function setVaccinationReceived(RotavirusVaccinationReceived $vaccinationReceived)
    {
        $this->vaccinationReceived = $vaccinationReceived;
        return $this;
    }

    /**
     * @param RotavirusVaccinationType $vaccinationType
     * @return $this
     */
    public function setVaccinationType(RotavirusVaccinationType $vaccinationType)
    {
        $this->vaccinationType = $vaccinationType;
        return $this;
    }

    /**
     * @param ThreeDoses $doses
     * @return $this
     */
    public function setDoses(ThreeDoses $doses)
    {
        $this->doses = $doses;
        return $this;
    }

    /**
     * @param \DateTime|null $firstVaccinationDose
     * @return $this
     */
    public function setFirstVaccinationDose(\DateTime $firstVaccinationDose = null)
    {
        $this->firstVaccinationDose = $firstVaccinationDose;

        return $this;
    }

    /**
     * @param \DateTime|null $secondVaccinationDose
     * @return $this
     */
    public function setSecondVaccinationDose(\DateTime $secondVaccinationDose = null)
    {
        $this->secondVaccinationDose = $secondVaccinationDose;

        return $this;
    }

    /**
     * @param \DateTime|null $thirdVaccinationDose
     * @return $this
     */
    public function setThirdVaccinationDose(\DateTime $thirdVaccinationDose = null)
    {
        $this->thirdVaccinationDose = $thirdVaccinationDose;

        return $this;
    }

    /**
     * @param TripleChoice $stoolCollected
     * @return $this
     */
    public function setStoolCollected(TripleChoice $stoolCollected)
    {
        $this->stoolCollected = $stoolCollected;
        return $this;
    }

    /**
     * @param $stoolId
     * @return $this
     */
    public function setStoolId($stoolId)
    {
        $this->stoolId = $stoolId;
        return $this;
    }

    /**
     * @param \DateTime|null $stoolCollectionDate
     * @return $this
     */
    public function setStoolCollectionDate(\DateTime $stoolCollectionDate = null)
    {
        $this->stoolCollectionDate = $stoolCollectionDate;

        return $this;
    }

    /**
     * @param RotavirusDischargeOutcome $dischargeOutcome
     * @return $this
     */
    public function setDischargeOutcome(RotavirusDischargeOutcome $dischargeOutcome)
    {
        $this->dischargeOutcome = $dischargeOutcome;
        return $this;
    }

    /**
     * @param \DateTime|null $dischargeDate
     * @return $this
     */
    public function setDischargeDate(\DateTime $dischargeDate = null)
    {
        $this->dischargeDate = $dischargeDate;

        return $this;
    }

    /**
     * @param $dischargeClassOther
     * @return $this
     */
    public function setDischargeClassOther($dischargeClassOther)
    {
        $this->dischargeClassOther = $dischargeClassOther;
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
    public function setIntensiveCare(TripleChoice $intensiveCare)
    {
        $this->intensiveCare = $intensiveCare;
        return $this;
    }
}
