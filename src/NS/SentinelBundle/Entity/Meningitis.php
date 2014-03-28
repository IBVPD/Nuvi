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
use NS\SentinelBundle\Form\Types\MeningitisCaseStatus;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationReceived;
use NS\SentinelBundle\Form\Types\MeningitisVaccinationType;
use NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;

use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Description of Meningitis
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Meningitis")
 * @ORM\Table(name="meningitis_cases")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB"},relation="site",class="NSSentinelBundle:Site"),
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
     * @ORM\OneToOne(targetEntity="ReferenceLab", mappedBy="case")
     */
    private $referenceLab;

    /**
     * @ORM\OneToOne(targetEntity="SiteLab", mappedBy="case")
     */
    private $lab;

    /**
     * @var Region $region
     * @ORM\ManyToOne(targetEntity="Region",inversedBy="meningitisCases")
     */
    private $region;

    /**
     * @var Country $country
     * @ORM\ManyToOne(targetEntity="Country",inversedBy="meningitisCases")
     */
    private $country;

    /**
     * @var Site $site
     * @ORM\ManyToOne(targetEntity="Site",inversedBy="meningitisCases")
     */
    private $site;
// Case based demographic
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

    /**
     * @var string $caseId
     * @ORM\Column(name="caseId",type="string",nullable=true)
     */
    private $caseId;

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
     * @var MeningitisCaseStatus $status
     * @ORM\Column(name="status",type="MeningitisCaseStatus")
     */
    private $status;

    public function __construct()
    {
        $this->dob                = new \DateTime();
        $this->admDate            = new \DateTime();
        $this->csfCollectDateTime = new \DateTime();
        $this->csfLabDateTime     = new \DateTime();
        $this->status             = new MeningitisCaseStatus(0);
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
        return sprintf("%s-%s-%s-%06d", $this->getRegion()->getCode(), $this->country->getCode(), $this->site->getCode(),$id);
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
    
    /**
     * Set ReferenceLab
     *
     * @param \NS\SentinelBundle\Entity\ReferenceLab $lab
     * @return Meningitis
     */
    public function setReferenceLab(\NS\SentinelBundle\Entity\ReferenceLab $lab = null)
    {
        $this->referenceLab = $lab;
    
        return $this;
    }

    /**
     * Get ReferenceLab
     *
     * @return \NS\SentinelBundle\Entity\ReferenceLab 
     */
    public function getReferenceLab()
    {
        return $this->referenceLab;
    }

    public function hasReferenceLab()
    {
        return ($this->referenceLab instanceof ReferenceLab);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(MeningitisCaseStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */

    public function prePersist()
    {
    }

    /** 
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
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
    private function _calculateStatus()
    {
        // Test Suspected
        if($this->ageInMonths < 60 && $this->menFever->equal(TripleChoice::YES) )
        {
            if($this->menAltConscious->equal(TripleChoice::YES) || $this->menNeckStiff->equal(TripleChoice::YES))
                $this->status->setValue (MeningitisCaseStatus::SUSPECTED);
        }
        else if($this->ageInMonths < 60 && $this->admDx->equal(Diagnosis::MENINGITIS))
            $this->status->setValue (MeningitisCaseStatus::SUSPECTED);

        if($this->status->equal(MeningitisCaseStatus::SUSPECTED))
        {
            // Probable
            if($this->csfAppearance->equal(CSFAppearance::TURBID))
                $this->status->setValue (MeningitisCaseStatus::PROBABLE);

            // Confirmed
        }
    }

    public function validate(ExecutionContextInterface $context)
    {
        if($this->admDate && $this->onsetDate && $this->admDate < $this->onsetDate)
            $context->addViolationAt ('admDate', "form.validation.admission-after-onset");

        if($this->dob && $this->onsetDate && $this->onsetDate < $this->dob)
            $context->addViolationAt ('dob', "form.validation.onset-after-dob");
    }
}