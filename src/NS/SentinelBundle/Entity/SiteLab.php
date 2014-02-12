<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\SampleType;
use NS\SentinelBundle\Form\Types\Volume;
use NS\SentinelBundle\Form\Types\IsolateType;

use Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

/**
 * Description of SiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\SiteLab")
 * @ORM\Table(name="meningitis_site_labs")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 */
class SiteLab
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Meningitis",inversedBy="lab")
     * @ORM\JoinColumn(nullable=false,unique=true)
     */
    private $case;
    
    //Case-based Laboratory Data
    /**
     * @var DateTime $csfLabDateTime
     * @ORM\Column(name="csfLabDateTime",type="datetime",nullable=true)
     */
    private $csfLabDateTime;

    /**
     * @var boolean $csfWcc
     * @ORM\Column(name="csfWcc", type="integer",nullable=true)
     */
    private $csfWcc;

    /**
     * @var boolean $csfGlucose
     * @ORM\Column(name="csfGlucose", type="integer",nullable=true)
     */
    private $csfGlucose;

    /**
     * @var boolean $csfProtein
     * @ORM\Column(name="csfProtein", type="integer",nullable=true)
     */
    private $csfProtein;

    /**
     * @var TripleChoice $csfCultDone
     * @ORM\Column(name="csfCultDone",type="TripleChoice",nullable=true)
     */
    private $csfCultDone;

    /**
     * @var TripleChoice $csfGramDone
     * @ORM\Column(name="csfGramDone",type="TripleChoice",nullable=true)
     */
    private $csfGramDone;

    /**
     * @var TripleChoice $csfBinaxDone
     * @ORM\Column(name="csfBinaxDone",type="TripleChoice",nullable=true)
     */
    private $csfBinaxDone;

    /**
     * @var TripleChoice $csfLatDone
     * @ORM\Column(name="csfLatDone",type="TripleChoice",nullable=true)
     */
    private $csfLatDone;

    /**
     * @var TripleChoice $csfPcrDone
     * @ORM\Column(name="csfPcrDone",type="TripleChoice",nullable=true)
     */
    private $csfPcrDone;

    /**
     * @var TripleChoice $bloodCultDone
     * @ORM\Column(name="bloodCultDone",type="TripleChoice",nullable=true)
     */
    private $bloodCultDone;

    /**
     * @var TripleChoice $bloodGramDone
     * @ORM\Column(name="bloodGramDone",type="TripleChoice",nullable=true)
     */
    private $bloodGramDone;

    /**
     * @var TripleChoice $bloodPcrDone
     * @ORM\Column(name="bloodPcrDone",type="TripleChoice",nullable=true)
     */
    private $bloodPcrDone;

    /**
     * @var TripleChoice $otherCultDone
     * @ORM\Column(name="otherCultDone",type="TripleChoice",nullable=true)
     */
    private $otherCultDone;

    /**
     * @var TripleChoice $otherTestDone
     * @ORM\Column(name="otherTestDone",type="TripleChoice",nullable=true)
     */
    private $otherTestDone;

    /**
     * @var LatResult $csfCultResult
     * @ORM\Column(name="csfCultResult",type="LatResult",nullable=true)
     */
    private $csfCultResult;

    /**
     * @var string $csfCultOther
     * @ORM\Column(name="csfCultOther",type="string",nullable=true)
     */
    private $csfCultOther;

    /**
     * @var GramStain
     * @ORM\Column(name="csfGramResult",type="GramStain",nullable=true)
     */
    private $csfGramResult;
    
    /**
     * @var BinaxResult
     * @ORM\Column(name="csfBinaxResult",type="BinaxResult",nullable=true)
     */
    private $csfBinaxResult;
    
    /**
     * @var LatResult
     * @ORM\Column(name="csfLatResult",type="LatResult",nullable=true)
     */
    private $csfLatResult;
    
    /**
     * @var string
     * @ORM\Column(name="csfLatOther",type="string",nullable=true)
     */
    private $csfLatOther;
    
    /**
     * @var PCRResult
     * @ORM\Column(name="csfPcrResult",type="PCRResult",nullable=true)
     */
    private $csfPcrResult;

    /**
     * @var LatResult
     * @ORM\Column(name="bloodCultResult",type="LatResult",nullable=true)
     */
    private $bloodCultResult;

    /**
     * @var string
     * @ORM\Column(name="bloodCultOther",type="string",nullable=true)
     */
    private $bloodCultOther;

    /**
     * @var GramStain
     * @ORM\Column(name="bloodGramResult",type="GramStain",nullable=true)
     */
    private $bloodGramResult;
    /**
     * @var PCRResult
     * @ORM\Column(name="bloodPcrResult",type="PCRResult",nullable=true)
     */
    private $bloodPcrResult;

    /**
     * @var LatResult
     * @ORM\Column(name="otherCultResult",type="LatResult",nullable=true)
     */
    private $otherCultResult;

    /**
     * @var string
     * @ORM\Column(name="otherCultOther",type="string",nullable=true)
     */
    private $otherCultOther;

    /**
     * @var PCRResult
     * @ORM\Column(name="otherTestResult",type="PCRResult",nullable=true)
     */    
    private $otherTestResult;
    
    /**
     * @var string
     * @ORM\Column(name="otherTestOther",type="string",nullable=true)
     */
    private $otherTestOther;

    /**
     * @var DateTime $rrlCsfDate
     * @ORM\Column(name="rrlCsfDate",type="date",nullable=true)
     */
    private $rrlCsfDate;

    /**
     * @var DateTime $rrlIsoDate
     * @ORM\Column(name="rrlIsoDate",type="date",nullable=true)
     */
    private $rrlIsolDate;

    /**
     * @var TripleChoice $csfStore
     * @ORM\Column(name="csfStore",type="TripleChoice",nullable=true)
     */
    private $csfStore;
    
    /**
     * @var TripleChoice $csfStore
     * @ORM\Column(name="isolStore",type="TripleChoice",nullable=true)
     */
    private $isolStore;

    /**
     * @var string $rrlName
     * @ORM\Column(name="rrlName",type="string",nullable=true)
     */
    private $rrlName;

    /**
     * @var string $spnSerotype
     * @ORM\Column(name="spnSerotype",type="string",nullable=true)
     */
    private $spnSerotype;

    /**
     * @var string $hiSerotyoe
     * @ORM\Column(name="hiSerotyoe",type="string",nullable=true)
     */
    private $hiSerotype;

    /**
     * @var string $nmSerogroup
     * @ORM\Column(name="nmSerogroup",type="string",nullable=true)
     */
    private $nmSerogroup;
//PNEUMONIA / SEPSIS (In addition to above)
    /**
     * @var TripleChoice $cxrDone
     * @ORM\Column(name="cxrDone",type="TripleChoice",nullable=true)
     */
    private $cxrDone;

    /**
     * @var CXRResult $cxrResult
     * @ORM\Column(name="cxrResult",type="CXRResult",nullable=true)
     */
    private $cxrResult;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set csfLabDateTime
     *
     * @param \DateTime $csfLabDateTime
     * @return SiteLab
     */
    public function setCsfLabDateTime($csfLabDateTime)
    {
        $this->csfLabDateTime = $csfLabDateTime;
    
        return $this;
    }

    /**
     * Get csfLabDateTime
     *
     * @return \DateTime 
     */
    public function getCsfLabDateTime()
    {
        return $this->csfLabDateTime;
    }

    /**
     * Set csfWcc
     *
     * @param integer $csfWcc
     * @return SiteLab
     */
    public function setCsfWcc($csfWcc)
    {
        $this->csfWcc = $csfWcc;
    
        return $this;
    }

    /**
     * Get csfWcc
     *
     * @return integer 
     */
    public function getCsfWcc()
    {
        return $this->csfWcc;
    }

    /**
     * Set csfGlucose
     *
     * @param integer $csfGlucose
     * @return SiteLab
     */
    public function setCsfGlucose($csfGlucose)
    {
        $this->csfGlucose = $csfGlucose;
    
        return $this;
    }

    /**
     * Get csfGlucose
     *
     * @return integer 
     */
    public function getCsfGlucose()
    {
        return $this->csfGlucose;
    }

    /**
     * Set csfProtein
     *
     * @param integer $csfProtein
     * @return SiteLab
     */
    public function setCsfProtein($csfProtein)
    {
        $this->csfProtein = $csfProtein;
    
        return $this;
    }

    /**
     * Get csfProtein
     *
     * @return integer 
     */
    public function getCsfProtein()
    {
        return $this->csfProtein;
    }

    /**
     * Set csfCultDone
     *
     * @param TripleChoice $csfCultDone
     * @return SiteLab
     */
    public function setCsfCultDone($csfCultDone)
    {
        $this->csfCultDone = $csfCultDone;
    
        return $this;
    }

    /**
     * Get csfCultDone
     *
     * @return TripleChoice 
     */
    public function getCsfCultDone()
    {
        return $this->csfCultDone;
    }

    /**
     * Set csfGramDone
     *
     * @param TripleChoice $csfGramDone
     * @return SiteLab
     */
    public function setCsfGramDone($csfGramDone)
    {
        $this->csfGramDone = $csfGramDone;
    
        return $this;
    }

    /**
     * Get csfGramDone
     *
     * @return TripleChoice 
     */
    public function getCsfGramDone()
    {
        return $this->csfGramDone;
    }

    /**
     * Set csfBinaxDone
     *
     * @param TripleChoice $csfBinaxDone
     * @return SiteLab
     */
    public function setCsfBinaxDone($csfBinaxDone)
    {
        $this->csfBinaxDone = $csfBinaxDone;
    
        return $this;
    }

    /**
     * Get csfBinaxDone
     *
     * @return TripleChoice 
     */
    public function getCsfBinaxDone()
    {
        return $this->csfBinaxDone;
    }

    /**
     * Set csfLatDone
     *
     * @param TripleChoice $csfLatDone
     * @return SiteLab
     */
    public function setCsfLatDone($csfLatDone)
    {
        $this->csfLatDone = $csfLatDone;
    
        return $this;
    }

    /**
     * Get csfLatDone
     *
     * @return TripleChoice 
     */
    public function getCsfLatDone()
    {
        return $this->csfLatDone;
    }

    /**
     * Set csfPcrDone
     *
     * @param TripleChoice $csfPcrDone
     * @return SiteLab
     */
    public function setCsfPcrDone($csfPcrDone)
    {
        $this->csfPcrDone = $csfPcrDone;
    
        return $this;
    }

    /**
     * Get csfPcrDone
     *
     * @return TripleChoice 
     */
    public function getCsfPcrDone()
    {
        return $this->csfPcrDone;
    }

    /**
     * Set bloodCultDone
     *
     * @param TripleChoice $bloodCultDone
     * @return SiteLab
     */
    public function setBloodCultDone($bloodCultDone)
    {
        $this->bloodCultDone = $bloodCultDone;
    
        return $this;
    }

    /**
     * Get bloodCultDone
     *
     * @return TripleChoice 
     */
    public function getBloodCultDone()
    {
        return $this->bloodCultDone;
    }

    /**
     * Set bloodGramDone
     *
     * @param TripleChoice $bloodGramDone
     * @return SiteLab
     */
    public function setBloodGramDone($bloodGramDone)
    {
        $this->bloodGramDone = $bloodGramDone;
    
        return $this;
    }

    /**
     * Get bloodGramDone
     *
     * @return TripleChoice 
     */
    public function getBloodGramDone()
    {
        return $this->bloodGramDone;
    }

    /**
     * Set bloodPcrDone
     *
     * @param TripleChoice $bloodPcrDone
     * @return SiteLab
     */
    public function setBloodPcrDone($bloodPcrDone)
    {
        $this->bloodPcrDone = $bloodPcrDone;
    
        return $this;
    }

    /**
     * Get bloodPcrDone
     *
     * @return TripleChoice 
     */
    public function getBloodPcrDone()
    {
        return $this->bloodPcrDone;
    }

    /**
     * Set otherCultDone
     *
     * @param TripleChoice $otherCultDone
     * @return SiteLab
     */
    public function setOtherCultDone($otherCultDone)
    {
        $this->otherCultDone = $otherCultDone;
    
        return $this;
    }

    /**
     * Get otherCultDone
     *
     * @return TripleChoice 
     */
    public function getOtherCultDone()
    {
        return $this->otherCultDone;
    }

    /**
     * Set otherTestDone
     *
     * @param TripleChoice $otherTestDone
     * @return SiteLab
     */
    public function setOtherTestDone($otherTestDone)
    {
        $this->otherTestDone = $otherTestDone;
    
        return $this;
    }

    /**
     * Get otherTestDone
     *
     * @return TripleChoice 
     */
    public function getOtherTestDone()
    {
        return $this->otherTestDone;
    }

    /**
     * Set csfCultResult
     *
     * @param LatResult $csfCultResult
     * @return SiteLab
     */
    public function setCsfCultResult($csfCultResult)
    {
        $this->csfCultResult = $csfCultResult;
    
        return $this;
    }

    /**
     * Get csfCultResult
     *
     * @return LatResult 
     */
    public function getCsfCultResult()
    {
        return $this->csfCultResult;
    }

    /**
     * Set csfCultOther
     *
     * @param string $csfCultOther
     * @return SiteLab
     */
    public function setCsfCultOther($csfCultOther)
    {
        $this->csfCultOther = $csfCultOther;
    
        return $this;
    }

    /**
     * Get csfCultOther
     *
     * @return string 
     */
    public function getCsfCultOther()
    {
        return $this->csfCultOther;
    }

    /**
     * Set csfGramResult
     *
     * @param GramStain $csfGramResult
     * @return SiteLab
     */
    public function setCsfGramResult($csfGramResult)
    {
        $this->csfGramResult = $csfGramResult;
    
        return $this;
    }

    /**
     * Get csfGramResult
     *
     * @return GramStain 
     */
    public function getCsfGramResult()
    {
        return $this->csfGramResult;
    }

    /**
     * Set csfBinaxResult
     *
     * @param BinaxResult $csfBinaxResult
     * @return SiteLab
     */
    public function setCsfBinaxResult($csfBinaxResult)
    {
        $this->csfBinaxResult = $csfBinaxResult;
    
        return $this;
    }

    /**
     * Get csfBinaxResult
     *
     * @return BinaxResult 
     */
    public function getCsfBinaxResult()
    {
        return $this->csfBinaxResult;
    }

    /**
     * Set csfLatResult
     *
     * @param LatResult $csfLatResult
     * @return SiteLab
     */
    public function setCsfLatResult($csfLatResult)
    {
        $this->csfLatResult = $csfLatResult;
    
        return $this;
    }

    /**
     * Get csfLatResult
     *
     * @return LatResult 
     */
    public function getCsfLatResult()
    {
        return $this->csfLatResult;
    }

    /**
     * Set csfLatOther
     *
     * @param string $csfLatOther
     * @return SiteLab
     */
    public function setCsfLatOther($csfLatOther)
    {
        $this->csfLatOther = $csfLatOther;
    
        return $this;
    }

    /**
     * Get csfLatOther
     *
     * @return string 
     */
    public function getCsfLatOther()
    {
        return $this->csfLatOther;
    }

    /**
     * Set csfPcrResult
     *
     * @param PCRResult $csfPcrResult
     * @return SiteLab
     */
    public function setCsfPcrResult($csfPcrResult)
    {
        $this->csfPcrResult = $csfPcrResult;
    
        return $this;
    }

    /**
     * Get csfPcrResult
     *
     * @return PCRResult 
     */
    public function getCsfPcrResult()
    {
        return $this->csfPcrResult;
    }

    /**
     * Set bloodCultResult
     *
     * @param LatResult $bloodCultResult
     * @return SiteLab
     */
    public function setBloodCultResult($bloodCultResult)
    {
        $this->bloodCultResult = $bloodCultResult;
    
        return $this;
    }

    /**
     * Get bloodCultResult
     *
     * @return LatResult 
     */
    public function getBloodCultResult()
    {
        return $this->bloodCultResult;
    }

    /**
     * Set bloodCultOther
     *
     * @param string $bloodCultOther
     * @return SiteLab
     */
    public function setBloodCultOther($bloodCultOther)
    {
        $this->bloodCultOther = $bloodCultOther;
    
        return $this;
    }

    /**
     * Get bloodCultOther
     *
     * @return string 
     */
    public function getBloodCultOther()
    {
        return $this->bloodCultOther;
    }

    /**
     * Set bloodGramResult
     *
     * @param GramStain $bloodGramResult
     * @return SiteLab
     */
    public function setBloodGramResult($bloodGramResult)
    {
        $this->bloodGramResult = $bloodGramResult;
    
        return $this;
    }

    /**
     * Get bloodGramResult
     *
     * @return GramStain 
     */
    public function getBloodGramResult()
    {
        return $this->bloodGramResult;
    }

    /**
     * Set bloodPcrResult
     *
     * @param PCRResult $bloodPcrResult
     * @return SiteLab
     */
    public function setBloodPcrResult($bloodPcrResult)
    {
        $this->bloodPcrResult = $bloodPcrResult;
    
        return $this;
    }

    /**
     * Get bloodPcrResult
     *
     * @return PCRResult 
     */
    public function getBloodPcrResult()
    {
        return $this->bloodPcrResult;
    }

    /**
     * Set otherCultResult
     *
     * @param LatResult $otherCultResult
     * @return SiteLab
     */
    public function setOtherCultResult($otherCultResult)
    {
        $this->otherCultResult = $otherCultResult;
    
        return $this;
    }

    /**
     * Get otherCultResult
     *
     * @return LatResult 
     */
    public function getOtherCultResult()
    {
        return $this->otherCultResult;
    }

    /**
     * Set otherCultOther
     *
     * @param string $otherCultOther
     * @return SiteLab
     */
    public function setOtherCultOther($otherCultOther)
    {
        $this->otherCultOther = $otherCultOther;
    
        return $this;
    }

    /**
     * Get otherCultOther
     *
     * @return string 
     */
    public function getOtherCultOther()
    {
        return $this->otherCultOther;
    }

    /**
     * Set otherTestResult
     *
     * @param PCRResult $otherTestResult
     * @return SiteLab
     */
    public function setOtherTestResult($otherTestResult)
    {
        $this->otherTestResult = $otherTestResult;
    
        return $this;
    }

    /**
     * Get otherTestResult
     *
     * @return PCRResult 
     */
    public function getOtherTestResult()
    {
        return $this->otherTestResult;
    }

    /**
     * Set otherTestOther
     *
     * @param string $otherTestOther
     * @return SiteLab
     */
    public function setOtherTestOther($otherTestOther)
    {
        $this->otherTestOther = $otherTestOther;
    
        return $this;
    }

    /**
     * Get otherTestOther
     *
     * @return string 
     */
    public function getOtherTestOther()
    {
        return $this->otherTestOther;
    }

    /**
     * Set rrlCsfDate
     *
     * @param \DateTime $rrlCsfDate
     * @return SiteLab
     */
    public function setRrlCsfDate($rrlCsfDate)
    {
        $this->rrlCsfDate = $rrlCsfDate;
    
        return $this;
    }

    /**
     * Get rrlCsfDate
     *
     * @return \DateTime 
     */
    public function getRrlCsfDate()
    {
        return $this->rrlCsfDate;
    }

    /**
     * Set rrlIsolDate
     *
     * @param \DateTime $rrlIsolDate
     * @return SiteLab
     */
    public function setRrlIsolDate($rrlIsolDate)
    {
        $this->rrlIsolDate = $rrlIsolDate;
    
        return $this;
    }

    /**
     * Get rrlIsolDate
     *
     * @return \DateTime 
     */
    public function getRrlIsolDate()
    {
        return $this->rrlIsolDate;
    }

    /**
     * Set csfStore
     *
     * @param TripleChoice $csfStore
     * @return SiteLab
     */
    public function setCsfStore($csfStore)
    {
        $this->csfStore = $csfStore;
    
        return $this;
    }

    /**
     * Get csfStore
     *
     * @return TripleChoice 
     */
    public function getCsfStore()
    {
        return $this->csfStore;
    }

    /**
     * Set isolStore
     *
     * @param TripleChoice $isolStore
     * @return SiteLab
     */
    public function setIsolStore($isolStore)
    {
        $this->isolStore = $isolStore;
    
        return $this;
    }

    /**
     * Get isolStore
     *
     * @return TripleChoice 
     */
    public function getIsolStore()
    {
        return $this->isolStore;
    }

    /**
     * Set rrlName
     *
     * @param string $rrlName
     * @return SiteLab
     */
    public function setRrlName($rrlName)
    {
        $this->rrlName = $rrlName;
    
        return $this;
    }

    /**
     * Get rrlName
     *
     * @return string 
     */
    public function getRrlName()
    {
        return $this->rrlName;
    }

    /**
     * Set spnSerotype
     *
     * @param string $spnSerotype
     * @return SiteLab
     */
    public function setSpnSerotype($spnSerotype)
    {
        $this->spnSerotype = $spnSerotype;
    
        return $this;
    }

    /**
     * Get spnSerotype
     *
     * @return string 
     */
    public function getSpnSerotype()
    {
        return $this->spnSerotype;
    }

    /**
     * Set hiSerotype
     *
     * @param string $hiSerotype
     * @return SiteLab
     */
    public function setHiSerotype($hiSerotype)
    {
        $this->hiSerotype = $hiSerotype;
    
        return $this;
    }

    /**
     * Get hiSerotype
     *
     * @return string 
     */
    public function getHiSerotype()
    {
        return $this->hiSerotype;
    }

    /**
     * Set nmSerogroup
     *
     * @param string $nmSerogroup
     * @return SiteLab
     */
    public function setNmSerogroup($nmSerogroup)
    {
        $this->nmSerogroup = $nmSerogroup;
    
        return $this;
    }

    /**
     * Get nmSerogroup
     *
     * @return string 
     */
    public function getNmSerogroup()
    {
        return $this->nmSerogroup;
    }

    /**
     * Set cxrDone
     *
     * @param TripleChoice $cxrDone
     * @return SiteLab
     */
    public function setCxrDone($cxrDone)
    {
        $this->cxrDone = $cxrDone;
    
        return $this;
    }

    /**
     * Get cxrDone
     *
     * @return TripleChoice 
     */
    public function getCxrDone()
    {
        return $this->cxrDone;
    }

    /**
     * Set cxrResult
     *
     * @param CXRResult $cxrResult
     * @return SiteLab
     */
    public function setCxrResult($cxrResult)
    {
        $this->cxrResult = $cxrResult;
    
        return $this;
    }

    /**
     * Get cxrResult
     *
     * @return CXRResult 
     */
    public function getCxrResult()
    {
        return $this->cxrResult;
    }

    /**
     * Set case
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $case
     * @return SiteLab
     */
    public function setCase(\NS\SentinelBundle\Entity\Meningitis $case)
    {
        $this->case = $case;
    
        return $this;
    }

    /**
     * Get case
     *
     * @return \NS\SentinelBundle\Entity\Meningitis 
     */
    public function getCase()
    {
        return $this->case;
    }
}
