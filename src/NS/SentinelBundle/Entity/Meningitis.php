<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\CSFAppearance;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\Doses;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\MeningitisCaseStatus;
use \NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;

use Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

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

    /**
     * @var DateTime $dob
     * @ORM\Column(name="dob",type="date",nullable=true)
     */
    private $dob;

    /**
     * @var integer $ageInMonths
     * @ORM\Column(name="ageInMonths",type="integer",nullable=true)
     */
    private $ageInMonths;

    /**
     * @var Gender $gender
     * @ORM\Column(name="gender",type="Gender",nullable=true)
     */
    private $gender;
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
     * @var TripleChoice $meningReceived
     * @ORM\Column(name="meningReceived",type="TripleChoice",nullable=true)
     */
    private $meningReceived;

    /**
     * @var Doses $meningDoses
     * @ORM\Column(name="meningDoses",type="Doses",nullable=true)
     */
    private $meningDoses;

    /**
     * @var TripleChoice $dtpReceived
     * @ORM\Column(name="dtpReceived",type="TripleChoice",nullable=true)
     */
    private $dtpReceived;

    /**
     * @var Doses $dtpDoses
     * @ORM\Column(name="dtpDoses",type="Doses",nullable=true)
     */
    private $dtpDoses;
//Case-based Clinical Data
    /**
     * @var DateTime $admDate
     * @ORM\Column(name="admDate",type="date",nullable=true)
     */
    private $admDate;

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
     * @var TripleChoice $menStridor
     * @ORM\Column(name="menStridor",type="TripleChoice",nullable=true)
     */
    private $menStridor;

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

    /**
     * @var TripleChoice $menPoorSucking
     * @ORM\Column(name="menPoorSucking",type="TripleChoice",nullable=true)
     */
    private $menPoorSucking;

    /**
     * @var TripleChoice $menIrritability
     * @ORM\Column(name="menIrritability",type="TripleChoice",nullable=true)
     */
    private $menIrritability;

    /**
     * @var text $menSymptomOther
     * @ORM\Column(name="menSymptomOther",type="text",nullable=true)
     */
    private $menSymptomOther;
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
     * @var integer $pneuRespRate
     * @ORM\Column(name="pneuRespRate",type="integer",nullable=true)
     */
    private $pneuRespRate;

    /**
     * @var text $pneuSymptomOther
     * @ORM\Column(name="pneuSymptomOther",type="text",nullable=true)
     */
    private $pneuSymptomOther;
//Case-based Specimen Collection Data

    /**
     * @var DateTime $csfCollected
     * @ORM\Column(name="csfCollected",type="boolean",nullable=true)
     */
    private $csfCollected;
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
    private $bloodId;

//Case-based Outcome Data
    /**
     * @var DishchargeOutcome $dischOutcome
     * @ORM\Column(name="dischOutcome",type="DischargeOutcome",nullable=true)
     */
    private $dischOutcome;

    /**
     * @var Diagnosis $dischDx
     * @ORM\Column(name="dischDx",type="Diagnosis",nullable=true)
     */
    private $dischDx;
    
    /**
     *
     * @var $dischDxOther
     * @ORM\Column(name="dischDxOther",type="string",nullable=true)
     */
    private $dischDxOther;

    /**
     * @var TripleChoice $dischSequelae
     * @ORM\Column(name="dischSequelae",type="TripleChoice",nullable=true)
     */
    private $dischSequelae;

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

    public function getAgeInMonths()
    {
        return $this->ageInMonths;
    }

    public function setAgeInMonths($ageInMonths)
    {
        $this->ageInMonths = $ageInMonths;
        return $this;
    }

    public function getHibReceived()
    {
        return $this->hibReceived;
    }

    public function setHibReceived(TripleChoice $hibReceived)
    {
        $this->hibReceived = $hibReceived;
        return $this;
    }

    public function getHibDoses()
    {
        return $this->hibDoses;
    }

    public function setHibDoses(Doses $hibDoses)
    {
        $this->hibDoses = $hibDoses;
        return $this;
    }

    public function getPcvReceived()
    {
        return $this->pcvReceived;
    }

    public function setPcvReceived(TripleChoice $pcvReceived)
    {
        $this->pcvReceived = $pcvReceived;
        return $this;
    }

    public function getPcvDoses()
    {
        return $this->pcvDoses;
    }

    public function setPcvDoses(Doses $pcvDoses)
    {
        $this->pcvDoses = $pcvDoses;
        return $this;
    }

    public function getMeningReceived()
    {
        return $this->meningReceived;
    }

    public function setMeningReceived(TripleChoice $meningReceived)
    {
        $this->meningReceived = $meningReceived;
        return $this;
    }

    public function getMeningDoses()
    {
        return $this->meningDoses;
    }

    public function setMeningDoses(Doses $meningDoses)
    {
        $this->meningDoses = $meningDoses;
        return $this;
    }

    public function getDtpReceived()
    {
        return $this->dtpReceived;
    }

    public function setDtpReceived(TripleChoice $dtpReceived)
    {
        $this->dtpReceived = $dtpReceived;
        return $this;
    }

    public function getDtpDoses()
    {
        return $this->dtpDoses;
    }

    public function setDtpDoses(Doses $dtpDoses)
    {
        $this->dtpDoses = $dtpDoses;
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

    public function getAdmDx()
    {
        return $this->admDx;
    }

    public function setAdmDx(Diagnosis $admDx)
    {
        $this->admDx = $admDx;
        return $this;
    }

    public function getAdmDxOther()
    {
        return $this->admDxOther;
    }

    public function setAdmDxOther($admDxOther)
    {
        $this->admDxOther = $admDxOther;
        return $this;
    }

    public function getMenSeizures()
    {
        return $this->menSeizures;
    }

    public function setMenSeizures(TripleChoice $menSeizures)
    {
        $this->menSeizures = $menSeizures;
        return $this;
    }

    public function getMenFever()
    {
        return $this->menFever;
    }

    public function setMenFever(TripleChoice $menFever)
    {
        $this->menFever = $menFever;
        return $this;
    }

    public function getMenAltConscious()
    {
        return $this->menAltConscious;
    }

    public function setMenAltConscious(TripleChoice $menAltConscious)
    {
        $this->menAltConscious = $menAltConscious;
        return $this;
    }

    public function getMenInabilityFeed()
    {
        return $this->menInabilityFeed;
    }

    public function setMenInabilityFeed(TripleChoice $menInabilityFeed)
    {
        $this->menInabilityFeed = $menInabilityFeed;
        return $this;
    }

    public function getMenStridor()
    {
        return $this->menStridor;
    }

    public function setMenStridor(TripleChoice $menStridor)
    {
        $this->menStridor = $menStridor;
        return $this;
    }

    public function getMenNeckStiff()
    {
        return $this->menNeckStiff;
    }

    public function setMenNeckStiff(TripleChoice $menNeckStiff)
    {
        $this->menNeckStiff = $menNeckStiff;
        return $this;
    }

    public function getMenRash()
    {
        return $this->menRash;
    }

    public function setMenRash(TripleChoice $menRash)
    {
        $this->menRash = $menRash;
        return $this;
    }

    public function getMenFontanelleBulge()
    {
        return $this->menFontanelleBulge;
    }

    public function setMenFontanelleBulge(TripleChoice $menFontanelleBulge)
    {
        $this->menFontanelleBulge = $menFontanelleBulge;
        return $this;
    }

    public function getMenLethargy()
    {
        return $this->menLethargy;
    }

    public function setMenLethargy(TripleChoice $menLethargy)
    {
        $this->menLethargy = $menLethargy;
        return $this;
    }

    public function getMenPoorSucking()
    {
        return $this->menPoorSucking;
    }

    public function setMenPoorSucking(TripleChoice $menPoorSucking)
    {
        $this->menPoorSucking = $menPoorSucking;
        return $this;
    }

    public function getMenIrritability()
    {
        return $this->menIrritability;
    }

    public function setMenIrritability(TripleChoice $menIrritability)
    {
        $this->menIrritability = $menIrritability;
        return $this;
    }

    public function getMenSymptomOther()
    {
        return $this->menSymptomOther;
    }

    public function setMenSymptomOther($menSymptomOther)
    {
        $this->menSymptomOther = $menSymptomOther;
        return $this;
    }

    public function getPneuDiffBreathe()
    {
        return $this->pneuDiffBreathe;
    }

    public function setPneuDiffBreathe(TripleChoice $pneuDiffBreathe)
    {
        $this->pneuDiffBreathe = $pneuDiffBreathe;
        return $this;
    }

    public function getPneuChestIndraw()
    {
        return $this->pneuChestIndraw;
    }

    public function setPneuChestIndraw(TripleChoice $pneuChestIndraw)
    {
        $this->pneuChestIndraw = $pneuChestIndraw;
        return $this;
    }

    public function getPneuCough()
    {
        return $this->pneuCough;
    }

    public function setPneuCough(TripleChoice $pneuCough)
    {
        $this->pneuCough = $pneuCough;
        return $this;
    }

    public function getPneuCyanosis()
    {
        return $this->pneuCyanosis;
    }

    public function setPneuCyanosis(TripleChoice $pneuCyanosis)
    {
        $this->pneuCyanosis = $pneuCyanosis;
        return $this;
    }

    public function getPneuRespRate()
    {
        return $this->pneuRespRate;
    }

    public function setPneuRespRate($pneuRespRate)
    {
        $this->pneuRespRate = $pneuRespRate;
        return $this;
    }

    public function getPneuSymptomOther()
    {
        return $this->pneuSymptomOther;
    }

    public function setPneuSymptomOther($pneuSymptomOther)
    {
        $this->pneuSymptomOther = $pneuSymptomOther;
        return $this;
    }

    public function getCsfCollected()
    {
        return $this->csfCollected;
    }

    public function setCsfCollected($csfCollected)
    {
        $this->csfCollected = $csfCollected;
        return $this;
    }

    public function getCsfId()
    {
        return $this->csfId;
    }

    public function setCsfId($csfId)
    {
        $this->csfId = $csfId;
        return $this;
    }

    public function getCsfCollectDateTime()
    {
        return $this->csfCollectDateTime;
    }

    public function setCsfCollectDateTime($csfCollectDateTime)
    {
        $this->csfCollectDateTime = $csfCollectDateTime;
        return $this;
    }

    public function getCsfAppearance()
    {
        return $this->csfAppearance;
    }

    public function setCsfAppearance(CSFAppearance $csfAppearance)
    {
        $this->csfAppearance = $csfAppearance;
        return $this;
    }

    public function getBloodCollected()
    {
        return $this->bloodCollected;
    }

    public function setBloodCollected($bloodCollected)
    {
        $this->bloodCollected = $bloodCollected;
        return $this;
    }

    public function getBloodId()
    {
        return $this->bloodId;
    }

    public function setBloodId($bloodId)
    {
        $this->bloodId = $bloodId;
        return $this;
    }

    public function getDischOutcome()
    {
        return $this->dischOutcome;
    }

    public function setDischOutcome($dischOutcome)
    {
        $this->dischOutcome = $dischOutcome;
        return $this;
    }

    public function getDischDx()
    {
        return $this->dischDx;
    }

    public function setDischDx(Diagnosis $dischDx)
    {
        $this->dischDx = $dischDx;
        return $this;
    }

    public function getDischDxOther()
    {
        return $this->dischDxOther;
    }

    public function setDischDxOther($dischDxOther)
    {
        $this->dischDxOther = $dischDxOther;
        return $this;
    }

    public function getDischSequelae()
    {
        return $this->dischSequelae;
    }

    public function setDischSequelae(TripleChoice $dischSequelae)
    {
        $this->dischSequelae = $dischSequelae;
        return $this;
    }

    public function getComment()
    {
        return $this->comment;
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

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender(Gender $gender)
    {
        $this->gender = $gender;
        return $this;
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
}