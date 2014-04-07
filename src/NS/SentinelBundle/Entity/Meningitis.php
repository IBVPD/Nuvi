<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\CSFAppearance;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\DischargeClassification;
use NS\SentinelBundle\Form\Types\Doses;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\MeningitisCaseResult;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationType;
use NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;
use NS\UtilBundle\Form\Types\ArrayChoice;

use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Description of Meningitis
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Meningitis")
 * @ORM\Table(name="meningitis_cases",uniqueConstraints={@ORM\UniqueConstraint(name="site_case_id_idx",columns={"site_id","caseId"})})
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB","ROLE_NL_LAB"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @Assert\Callback(methods={"validate"})
 */
class Meningitis implements IdentityAssignmentInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\NS\SentinelBundle\Generator\Custom")
     * @var string $id
     * @ORM\Column(name="id",type="string")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="BaseLab", mappedBy="case")
     */
    private $externalLabs;

    private $referenceLab = -1;
    private $nationalLab = -1;

    /**
     * @ORM\OneToOne(targetEntity="SiteLab", mappedBy="case")
     */
    private $lab;

    /**
     * @var Region $region
     * @ORM\ManyToOne(targetEntity="Region",inversedBy="meningitisCases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @var Country $country
     * @ORM\ManyToOne(targetEntity="Country",inversedBy="meningitisCases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @var Site $site
     * @ORM\ManyToOne(targetEntity="Site",inversedBy="meningitisCases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

// Case based demographic
    /**
     * @var string $caseId
     * @ORM\Column(name="caseId",type="string",nullable=false)
     * @Assert\NotBlank
     */
    private $caseId;

    /**
     * @var DateTime $dob
     * @ORM\Column(name="dob",type="date",nullable=true)
     * @Assert\Date
     */
    private $dob;

    /**
     * @var integer $ageInMonths
     * @ORM\Column(name="ageInMonths",type="integer",nullable=true)
     * @Assert\Range(min=0,max=59,minMessage="Children should older than 0 months",maxMessage="Children should be younger than 59 months to be tracked")
     */
    private $ageInMonths;

    /**
     * @var Gender $gender
     * @ORM\Column(name="gender",type="Gender",nullable=true)
     */
    private $gender;

    /**
     * @var string $district
     * @ORM\Column(name="district",type="string",nullable=true)
     */
    private $district;

//Case-based Clinical Data
    /**
     * @var DateTime $admDate
     * @ORM\Column(name="admDate",type="date",nullable=true)
     */
    private $admDate;

    /**
     * @var DateTime $onsetDate
     * @ORM\Column(name="onsetDate",type="date",nullable=true)
     */
    private $onsetDate;

    /**
     * @var Diagnosis $admDx
     * @ORM\Column(name="admDx",type="Diagnosis",nullable=true)
     */
    private $admDx;

    /**
     * @var string $admDxOther
     * @ORM\Column(name="admDxOther",type="string",nullable=true)
     */
    private $admDxOther;

    /**
     * @var TripleChoice $antibiotics
     * @ORM\Column(name="antibiotics",type="TripleChoice",nullable=true)
     */
    private $antibiotics;

//MENINGITIS
    /**
     * @var TripleChoice $menSeizures
     * @ORM\Column(name="menSeizures",type="TripleChoice",nullable=true)
     */
    private $menSeizures;

    /**
     * @var TripleChoice $menFever
     * @ORM\Column(name="menFever",type="TripleChoice",nullable=true)
     */
    private $menFever;

    /**
     * @var TripleChoice $menAltConscious
     * @ORM\Column(name="menAltConscious",type="TripleChoice",nullable=true)
     */
    private $menAltConscious;

    /**
     * @var TripleChoice $menInabilityFeed
     * @ORM\Column(name="menInabilityFeed",type="TripleChoice",nullable=true)
     */
    private $menInabilityFeed;

    /**
     * @var TripleChoice $menNeckStiff
     * @ORM\Column(name="menNeckStiff",type="TripleChoice",nullable=true)
     */
    private $menNeckStiff;

    /**
     * @var TripleChoice $menRash
     * @ORM\Column(name="menRash",type="TripleChoice",nullable=true)
     */
    private $menRash;

    /**
     * @var TripleChoice $menFontanelleBulge
     * @ORM\Column(name="menFontanelleBulge",type="TripleChoice",nullable=true)
     */
    private $menFontanelleBulge;

    /**
     * @var TripleChoice $menLethargy
     * @ORM\Column(name="menLethargy",type="TripleChoice",nullable=true)
     */
    private $menLethargy;

//PNEUMONIA / SEPSIS

    /**
     * @var TripleChoice $pneuDiffBreathe
     * @ORM\Column(name="pneuDiffBreathe",type="TripleChoice",nullable=true)
     */
    private $pneuDiffBreathe;

    /**
     * @var TripleChoice $pneuChestIndraw
     * @ORM\Column(name="pneuChestIndraw",type="TripleChoice",nullable=true)
     */
    private $pneuChestIndraw;

    /**
     * @var TripleChoice $pneuCough
     * @ORM\Column(name="pneuCough",type="TripleChoice",nullable=true)
     */
    private $pneuCough;

    /**
     * @var TripleChoice $pneuCyanosis
     * @ORM\Column(name="pneuCyanosis",type="TripleChoice",nullable=true)
     */
    private $pneuCyanosis;

    /**
     * @var TripleChoice $pneuStridor
     * @ORM\Column(name="pneuStridor",type="TripleChoice",nullable=true)
     */
    private $pneuStridor;

    /**
     * @var integer $pneuRespRate
     * @ORM\Column(name="pneuRespRate",type="integer",nullable=true)
     * @Assert\Range(min=0,max=200,minMessage="Please provide a valid respiratory rate",maxMessage="Please provide a valid respiratory rate")
     */
    private $pneuRespRate;

    /**
     * @var TripleChoice $pneuVomit
     * @ORM\Column(name="pneuVomit",type="TripleChoice",nullable=true)
     */
    private $pneuVomit;

    /**
     * @var TripleChoice $pneuHypothermia
     * @ORM\Column(name="pneuHypothermia",type="TripleChoice",nullable=true)
     */
    private $pneuHypothermia;

    /**
     * @var TripleChoice $pneuMalnutrition
     * @ORM\Column(name="pneuMalnutrition",type="TripleChoice",nullable=true)
     */
    private $pneuMalnutrition;

//Case-based Vaccination History
    /**
     * @var TripleChoice $hibReceived
     * @ORM\Column(name="hibReceived",type="TripleChoice",nullable=true)
     */
    private $hibReceived;

    /**
     * @var Doses $hibDoses
     * @ORM\Column(name="hibDoses",type="Doses",nullable=true)
     */
    private $hibDoses;

    /**
     * @var TripleChoice $pcvReceived
     * @ORM\Column(name="pcvReceived",type="TripleChoice",nullable=true)
     */
    private $pcvReceived;

    /**
     * @var Doses $pcvDoses
     * @ORM\Column(name="pcvDoses",type="Doses",nullable=true)
     */
    private $pcvDoses;

    /**
     * @var MeningitisVaccinationReceived $meningReceived
     * @ORM\Column(name="meningReceived",type="MeningitisVaccinationReceived",nullable=true)
     */
    private $meningReceived;

    /**
     * @var MeningitisVaccinationType $meningType
     * @ORM\Column(name="meningType",type="MeningitisVaccinationType",nullable=true)
     */
    private $meningType;

    /**
     * @var DateTime $meningMostRecentDose
     * @ORM\Column(name="meningMostRecentDose",type="date",nullable=true)
     * @Assert\Date
     */
    private $meningMostRecentDose;

//Case-based Specimen Collection Data

    /**
     * @var boolean $csfCollected
     * @ORM\Column(name="csfCollected",type="boolean",nullable=true)
     */
    private $csfCollected;

    /**
     * @var string $csfId
     * @ORM\Column(name="csfId",type="string",nullable=true)
     */
    private $csfId;

    /**
     * @var DateTime $csfCollectDateTime
     * @ORM\Column(name="csfCollectDateTime",type="datetime",nullable=true)
     */
    private $csfCollectDateTime;

    /**
     * @var DateTime $csfAppearance
     * @ORM\Column(name="csfAppearance",type="CSFAppearance",nullable=true)
     */
    private $csfAppearance;

    /**
     * @var boolean $bloodCollected
     * @ORM\Column(name="bloodCollected", type="boolean",nullable=true)
     */
    private $bloodCollected;

    /**
     * @var string $bloodId
     * @ORM\Column(name="bloodId",type="string",nullable=true)
     */
    private $bloodId;

//Case-based Outcome Data
    /**
     * @var DischargeOutcome $dischOutcome
     * @ORM\Column(name="dischOutcome",type="DischargeOutcome",nullable=true)
     */
    private $dischOutcome;

    /**
     * @var Diagnosis $dischDx
     * @ORM\Column(name="dischDx",type="Diagnosis",nullable=true)
     */
    private $dischDx;

    /**
     * @var $dischDxOther
     * @ORM\Column(name="dischDxOther",type="string",nullable=true)
     */
    private $dischDxOther;

    /**
     * @var DischargeClassification $dischClass
     * @ORM\Column(name="dischClass",type="DischargeClassification",nullable=true)
     */
    private $dischClass;

    /**
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     */
    private $comment;

    /**
     * @var MeningitisCaseResult $result
     * @ORM\Column(name="result",type="MeningitisCaseResult")
     */
    private $result;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     */
    private $status;

    /**
     * @var boolean $sentToReferenceLab
     * @ORM\Column(name="sentToReferenceLab",type="boolean")
     */
    private $sentToReferenceLab = false;

    /**
     * @var boolean $sentToNationalLab
     * @ORM\Column(name="sentToNationalLab",type="boolean")
     */
    private $sentToNationalLab = false;

    public function __construct()
    {
        $this->result             = new MeningitisCaseResult(0);
        $this->status             = new CaseStatus(0);
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function hasId()
    {
        return !empty($this->id);
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function setDob($dob)
    {
        if(!$dob instanceOf \DateTime)
            return;

        $this->dob = $dob;

        $interval = ($this->admDate) ? $dob->diff($this->admDate) : $dob->diff(new \DateTime());
        $this->setAgeInMonths(($interval->format('%a') / 30));

        return $this;
    }

    public function getAdmDate()
    {
        return $this->admDate;
    }

    public function setAdmDate($admDate)
    {
        $this->admDate = $admDate;

        if (($this->admDate && $this->dob))
        {
            $interval = $this->dob->diff($this->admDate);
            $this->setAgeInMonths(($interval->format('%a') / 30));
        }

        return $this;
    }

    public function getLab()
    {
        return $this->lab;
    }

    public function getAgeInMonths()
    {
        return $this->ageInMonths;
    }

    public function getGender()
    {
        return $this->gender;
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

    public function getCsfId()
    {
        return $this->csfId;
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

    public function getBloodId()
    {
        return $this->bloodId;
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

    public function setLab($lab)
    {
        $this->lab = $lab;
        return $this;
    }

    public function setAgeInMonths($ageInMonths)
    {
        $this->ageInMonths = $ageInMonths;
        return $this;
    }

    public function setGender(Gender $gender)
    {
        $this->gender = $gender;
        return $this;
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

    public function setHibReceived(TripleChoice $hibReceived)
    {
        $this->hibReceived = $hibReceived;
        return $this;
    }

    public function setHibDoses(Doses $hibDoses)
    {
        $this->hibDoses = $hibDoses;
        return $this;
    }

    public function setPcvReceived(TripleChoice $pcvReceived)
    {
        $this->pcvReceived = $pcvReceived;
        return $this;
    }

    public function setPcvDoses(Doses $pcvDoses)
    {
        $this->pcvDoses = $pcvDoses;
        return $this;
    }

    public function setMeningReceived(MeningitisVaccinationReceived $meningReceived)
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

    public function setCsfId($csfId)
    {
        $this->csfId = $csfId;
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

    public function setBloodId($bloodId)
    {
        $this->bloodId = $bloodId;
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

    /**
     * Set region
     *
     * @param \NS\SentinelBundle\Entity\Region $region
     * @return Meningitis
     */
    public function setRegion(Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \NS\SentinelBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set country
     *
     * @param \NS\SentinelBundle\Entity\Country $country
     * @return Meningitis
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        $this->setRegion($country->getRegion());

        return $this;
    }

    /**
     * Get country
     *
     * @return \NS\SentinelBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set site
     *
     * @param \NS\SentinelBundle\Entity\Site $site
     * @return Meningitis
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;

        $this->setCountry($site->getCountry());

        return $this;
    }

    public function getFullIdentifier($id)
    {
        return sprintf("%s-%s-%d-%06d", $this->country->getCode(), $this->site->getCode(), date('y'), $id);
    }

    /**
     * Get site
     *
     * @return \NS\SentinelBundle\Entity\Site 
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set SiteLab
     *
     * @param \NS\SentinelBundle\Entity\SiteLab $lab
     * @return Meningitis
     */
    public function setSiteLab(\NS\SentinelBundle\Entity\SiteLab $lab = null)
    {
        $this->lab = $lab;
    
        return $this;
    }

    /**
     * Get SiteLab
     *
     * @return \NS\SentinelBundle\Entity\SiteLab 
     */
    public function getSiteLab()
    {
        return $this->lab;
    }

    public function hasSiteLab()
    {
        return ($this->lab instanceof SiteLab);
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isComplete()
    {
        return $this->status->getValue() == CaseStatus::COMPLETE;
    }

    public function setResult(MeningitisCaseResult $result)
    {
        $this->result = $result;
        return $this;
    }

    public function setStatus(CaseStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->_calculateStatus();
        $this->_calculateResult();
    }

    /** 
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->_calculateStatus();
        $this->_calculateResult();
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
    private function _calculateResult()
    {
        if($this->status->getValue() >= CaseStatus::CANCELLED)
            return;

        // Test Suspected
        if($this->ageInMonths < 60 && $this->menFever && $this->menFever->equal(TripleChoice::YES))
        {
            if(($this->menAltConscious && $this->menAltConscious->equal(TripleChoice::YES)) || ($this->menNeckStiff && $this->menNeckStiff->equal(TripleChoice::YES)) )
                $this->result->setValue (MeningitisCaseResult::SUSPECTED);
        }
        else if($this->ageInMonths < 60 && $this->admDx && $this->admDx->equal(Diagnosis::MENINGITIS))
            $this->result->setValue (MeningitisCaseResult::SUSPECTED);

        if($this->result && $this->result->equal(MeningitisCaseResult::SUSPECTED))
        {
            // Probable
            if($this->csfAppearance && $this->csfAppearance->equal(CSFAppearance::TURBID))
                $this->result->setValue (MeningitisCaseResult::PROBABLE);

            // Confirmed
        }
    }

    private function _calculateStatus()
    {
        if($this->status->getValue() >= CaseStatus::CANCELLED)
            return;

        if($this->getIncompleteField())
            $this->status->setValue(CaseStatus::OPEN);
        else
            $this->status->setValue(CaseStatus::COMPLETE);

        return;
    }

    public function getIncompleteField()
    {
        foreach($this->getMinimumRequiredFields() as $field)
        {
            if(is_null($this->$field) || empty($this->$field) || ($this->$field instanceof ArrayChoice && $this->$field->equal(-1)) )
                return $field;
        }

        // this isn't covered by the above loop because its valid for ageInMonths == 0 but 0 == empty
        if(is_null($this->ageInMonths))
            return 'ageInMonths';

        if($this->admDx && $this->admDx->equal(Diagnosis::OTHER) && empty($this->admDxOther))
            return 'admDx';

        if($this->dischDx && $this->dischDx->equal(Diagnosis::OTHER) && empty($this->dischDxOther))
            return 'dischDx';

        if($this->hibReceived && $this->hibReceived->equal(TripleChoice::YES) && (is_null($this->hibDoses) || $this->hibDoses->equal(ArrayChoice::NO_SELECTION)))
            return 'hibReceived';

        if($this->pcvReceived && $this->pcvReceived->equal(TripleChoice::YES) && (is_null($this->pcvDoses) || $this->pcvDoses->equal(ArrayChoice::NO_SELECTION)))
            return 'pcvReceived';

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
            if(is_null($this->csfId))
                return 'csfCollected1';
            if(empty($this->csfId))
                return 'csfCollected2';
            if(is_null($this->csfCollectDateTime))
                return 'csfCollectDateTime';
            if(is_null($this->csfAppearance))
                return 'csfAppearance1';
            if($this->csfAppearance->equal(ArrayChoice::NO_SELECTION))
                return 'csfAppearance2';
        }

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
                    'dischOutcome',
                    'dischDx',
                    'dischClass',
                    );

        return ($this->country->getTracksPneumonia()) ? array_merge($fields,$this->getPneumiaRequiredFields()) : $fields;
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

        // if admission diagnosis is other, enforce value in 'admission diagnosis other' field
        if($this->admDx && $this->admDx->equal(Diagnosis::OTHER) && empty($this->admDxOther))
            $context->addViolationAt('admDx',"form.validation.admissionDx-other-without-other-text");

        // if discharge diagnosis is other, enforce value in 'discharge diagnosis other' field
        if($this->dischDx && $this->dischDx->equal(Diagnosis::OTHER) && empty($this->dischDxOther))
            $context->addViolationAt('dischDx',"form.validation.dischargeDx-other-without-other-text");

        if($this->hibReceived && $this->hibReceived->equal(TripleChoice::YES) && (is_null($this->hibDoses) || $this->hibDoses->equal(ArrayChoice::NO_SELECTION)))
            $context->addViolationAt('hibDoses', "form.validation.hibReceived-other-hibDoses-unselected");

        if($this->pcvReceived && $this->pcvReceived->equal(TripleChoice::YES) && (is_null($this->pcvDoses) || $this->pcvDoses->equal(ArrayChoice::NO_SELECTION)))
            $context->addViolationAt('pcvDoses', "form.validation.pcvReceived-other-pcvDoses-unselected '".$this->pcvReceived."'" );

        if($this->meningReceived && ($this->meningReceived->equal(MeningitisVaccinationReceived::YES_CARD ) || $this->meningReceived->equal(MeningitisVaccinationReceived::YES_HISTORY)))
        {
            if(is_null($this->meningType))
                $context->addViolationAt('meningType', "form.validation.meningReceived-meningType-empty");

            if($this->meningType->equal(ArrayChoice::NO_SELECTION))
                $context->addViolationAt('meningType', "form.validation.meningReceived-meningType-empty");

            if(is_null($this->meningMostRecentDose))
                $context->addViolationAt('meningType', "form.validation.meningReceived-meningMostRecentDose-empty");
        }

        if($this->csfCollected && $this->csfCollected->equal(TripleChoice::YES))
        {
            if(is_null($this->csfId) || empty($this->csfId))
                $context->addViolationAt('csfId', "form.validation.csfCollected-csfId-empty");
                
            if(is_null($this->csfCollectDateTime))
                $context->addViolationAt('csfId', "form.validation.csfCollected-csfCollectDateTime-empty");

            if(is_null($this->csfAppearance) || $this->csfAppearance->equal(ArrayChoice::NO_SELECTION))
                $context->addViolationAt('csfId', "form.validation.csfCollected-csfAppearance-empty");
        }
    }

    /**
     * Add externalLabs
     *
     * @param \NS\SentinelBundle\Entity\BaseLab $externalLabs
     * @return Meningitis
     */
    public function addExternalLab(\NS\SentinelBundle\Entity\BaseLab $externalLabs)
    {
        $this->externalLabs[] = $externalLabs;
    
        return $this;
    }

    /**
     * Remove externalLabs
     *
     * @param \NS\SentinelBundle\Entity\BaseLab $externalLabs
     */
    public function removeExternalLab(\NS\SentinelBundle\Entity\BaseLab $externalLabs)
    {
        $this->externalLabs->removeElement($externalLabs);
    }

    /**
     * Get externalLabs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExternalLabs()
    {
        return $this->externalLabs;
    }

    private function _findReferenceLab()
    {
        if(is_integer($this->referenceLab) && $this->referenceLab == -1)
        {
            foreach($this->externalLabs as $l)
            {
                if($l instanceof ReferenceLab)
                {
                    $this->referenceLab = $l;
                    return;
                }
            }

            $this->referenceLab = null;
        }
    }

    /**
     * Get ReferenceLab
     *
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function getReferenceLab()
    {
        $this->_findReferenceLab();
        return $this->referenceLab;
    }

    public function hasReferenceLab()
    {
        $this->_findReferenceLab();
        return ($this->referenceLab instanceof ReferenceLab);
    }

    private function _findNationalLab()
    {
        if(is_integer($this->nationalLab) && $this->nationalLab == -1)
        {
            foreach($this->externalLabs as $l)
            {
                if($l instanceof NationalLab)
                {
                    $this->nationalLab = $l;
                    return;
                }
            }

            $this->nationalLab = null;
        }
    }

    /**
     * Get NationalLab
     *
     * @return \NS\SentinelBundle\Entity\NationalLab
     */
    public function getNationalLab()
    {
        $this->_findNationalLab();
        return $this->nationalLab;
    }

    public function hasNationalLab()
    {
        $this->_findNationalLab();
        return ($this->nationalLab instanceof NationalLab);
    }

    /**
     * Set sentToReferenceLab
     *
     * @param boolean $sentToReferenceLab
     * @return Meningitis
     */
    public function setSentToReferenceLab($sentToReferenceLab)
    {
        $this->sentToReferenceLab = $sentToReferenceLab;
    
        return $this;
    }

    /**
     * Get sentToReferenceLab
     *
     * @return boolean 
     */
    public function getSentToReferenceLab()
    {
        return $this->sentToReferenceLab;
    }

    /**
     * Set sentToNationalLab
     *
     * @param boolean $sentToNationalLab
     * @return Meningitis
     */
    public function setSentToNationalLab($sentToNationalLab)
    {
        $this->sentToNationalLab = $sentToNationalLab;
    
        return $this;
    }

    /**
     * Get sentToNationalLab
     *
     * @return boolean 
     */
    public function getSentToNationalLab()
    {
        return $this->sentToNationalLab;
    }
}