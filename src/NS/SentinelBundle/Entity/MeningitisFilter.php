<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\CSFAppearance;
use NS\SentinelBundle\Form\Types\CXRResult;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\Doses;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\Role;

/**
 * Description of Meningitis
 * @author gnat
 */
class MeningitisFilter
{
    /**
     * @var string $id
     */
    private $id;

    /**
     * @var Region $region
     */
    private $region;

    /**
     * @var Country $country
     */
    private $country;

    /**
     * @var Site $site
     */
    private $site;

    /**
     * @var DateTime $dob
     */
    private $dob;

    /**
     * @var integer $ageInMonths
     */
    private $ageInMonths;

    /**
     * @var Gender $gender
     */
    private $gender;

//Case-based Vaccination History
    /**
     * @var TripleChoice $hibReceived
     */
    private $hibReceived;

    /**
     * @var Doses $hibDoses
     */
    private $hibDoses;

    /**
     * @var TripleChoice $pcvReceived
     */
    private $pcvReceived;

    /**
     * @var Doses $pcvDoses
     */
    private $pcvDoses;

    /**
     * @var TripleChoice $meningReceived
     */
    private $meningReceived;

    /**
     * @var Doses $meningDoses
     */
    private $meningDoses;

    /**
     * @var TripleChoice $dtpReceived
     */
    private $dtpReceived;

    /**
     * @var Doses $dtpDoses
     */
    private $dtpDoses;

//Case-based Clinical Data
    /**
     * @var DateTime $admDate
     */
    private $admDate;

    /**
     * @var Diagnosis $admDx
     */
    private $admDx;

    /**
     * @var string $admDxOther
     */
    private $admDxOther;

//MENINGITIS
    /**
     * @var TripleChoice $menSeizures
     */
    private $menSeizures;

    /**
     * @var TripleChoice $menFever
     */
    private $menFever;

    /**
     * @var TripleChoice $menAltConscious
     */
    private $menAltConscious;

    /**
     * @var TripleChoice $menInabilityFeed
     */
    private $menInabilityFeed;

    /**
     * @var TripleChoice $menStridor
     */
    private $menStridor;

    /**
     * @var TripleChoice $menNeckStiff
     */
    private $menNeckStiff;

    /**
     * @var TripleChoice $menRash
     */
    private $menRash;

    /**
     * @var TripleChoice $menFontanelleBulge
     */
    private $menFontanelleBulge;

    /**
     * @var TripleChoice $menLethargy
     */
    private $menLethargy;

    /**
     * @var TripleChoice $menPoorSucking
     */
    private $menPoorSucking;

    /**
     * @var TripleChoice $menIrritability
     */
    private $menIrritability;

    /**
     * @var text $menSymptomOther
     */
    private $menSymptomOther;

//PNEUMONIA / SEPSIS
    /**
     * @var TripleChoice $pneuDiffBreathe
     */
    private $pneuDiffBreathe;

    /**
     * @var TripleChoice $pneuChestIndraw
     */
    private $pneuChestIndraw;

    /**
     * @var TripleChoice $pneuCough
     */
    private $pneuCough;

    /**
     * @var TripleChoice $pneuCyanosis
     */
    private $pneuCyanosis;

    /**
     * @var integer $pneuRespRate
     */
    private $pneuRespRate;

    /**
     * @var text $pneuSymptomOther
     */
    private $pneuSymptomOther;

//Case-based Specimen Collection Data
    /**
     * @var DateTime $csfCollected
     */
    private $csfCollected;
    private $csfId;

    /**
     * @var DateTime $csfCollectDateTime
     */
    private $csfCollectDateTime;

    /**
     * @var DateTime $csfAppearance
     */
    private $csfAppearance;

    /**
     * @var boolean $bloodCollected
     */
    private $bloodCollected;
    private $bloodId;

//Case-based Outcome Data
    /**
     * @var DishchargeOutcome $dischOutcome
     */
    private $dischOutcome;

    /**
     * @var Diagnosis $dischDx
     */
    private $dischDx;
    
    /**
     *
     * @var $dischDxOther
     */
    private $dischDxOther;

    /**
     * @var TripleChoice $dischSequelae
     */
    private $dischSequelae;

    /**
     * @var string $comment
     */
    private $comment;

    public function __construct()
    {
        $this->dob                = new \DateTime();
        $this->admDate            = new \DateTime();
        $this->csfCollectDateTime = new \DateTime();
        $this->csfLabDateTime     = new \DateTime();
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
    public function setRegion($region = null)
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
    public function setCountry( $country = null)
    {
        $this->country = $country;

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
    public function setSite($site = null)
    {
        $this->site = $site;

        return $this;
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
    public function setSiteLab($lab = null)
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
    public function setReferenceLab($lab = null)
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
}