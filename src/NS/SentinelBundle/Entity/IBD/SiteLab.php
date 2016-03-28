<?php

namespace NS\SentinelBundle\Entity\IBD;

use \Doctrine\ORM\Mapping as ORM;
use \Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation as Security;
use \NS\SentinelBundle\Entity\BaseSiteLab;
use \NS\SentinelBundle\Entity\IBD;
use \NS\SentinelBundle\Form\Types\BinaxResult;
use \NS\SentinelBundle\Form\Types\CaseStatus;
use \NS\SentinelBundle\Form\Types\CultureResult;
use \NS\SentinelBundle\Form\Types\GramStain;
use \NS\SentinelBundle\Form\Types\GramStainResult;
use \NS\SentinelBundle\Form\Types\HiSerotype;
use \NS\SentinelBundle\Form\Types\LatResult;
use \NS\SentinelBundle\Form\Types\NmSerogroup;
use \NS\SentinelBundle\Form\Types\PCRResult;
use \NS\SentinelBundle\Form\Types\SpnSerotype;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\UtilBundle\Form\Types\ArrayChoice;
use \Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Validator\Constraints\DateTime;
use \JMS\Serializer\Annotation\Groups;
use \NS\SentinelBundle\Validators as NSValidators;

/**
 *
 * Description of SiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD\SiteLabRepository")
 * @ORM\Table(name="ibd_site_labs")
 * @Gedmo\Loggable
 * @Security\Secured(conditions={
 *      @Security\SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @Security\SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @Security\SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @NSValidators\AllOther( {
 *                      @NSValidators\Other(field="csfCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfCultResult",message="form.validation.ibd-sitelab-csfCult-was-done-without-result"),
 *                      @NSValidators\Other(field="csfCultResult",value="\NS\SentinelBundle\Form\Types\CultureResult::OTHER",otherField="csfCultOther",message="form.validation.ibd-sitelab-csfCult-was-done-without-result-other"),
 *
 *                      @NSValidators\Other(field="csfLatDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfLatResult",message="form.validation.ibd-sitelab-csfLat-was-done-without-result"),
 *                      @NSValidators\Other(field="csfLatResult",value="\NS\SentinelBundle\Form\Types\LatResult::OTHER",otherField="csfLatOther",message="form.validation.ibd-sitelab-csfLat-was-done-without-result-other"),
 * 
 *                      @NSValidators\Other(field="csfPcrDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfPcrResult",message="form.validation.ibd-sitelab-csfPcr-was-done-without-result"),
 *                      @NSValidators\Other(field="csfPcrResult",value="\NS\SentinelBundle\Form\Types\PCRResult::OTHER",otherField="csfPcrOther",message="form.validation.ibd-sitelab-csfPcr-was-done-without-result-other"),
 *
 *                      @NSValidators\Other(field="bloodCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="bloodCultResult",message="form.validation.ibd-sitelab-bloodCult-was-done-without-result"),
 *                      @NSValidators\Other(field="bloodCultResult",value="\NS\SentinelBundle\Form\Types\CultureResult::OTHER",otherField="bloodCultOther",message="form.validation.ibd-sitelab-bloodCult-was-done-without-result-other"),
 *
 *                      @NSValidators\Other(field="otherCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherCultResult",message="form.validation.ibd-sitelab-otherCult-was-done-without-result"),
 *                      @NSValidators\Other(field="otherCultResult",value="\NS\SentinelBundle\Form\Types\CultureResult::OTHER",otherField="otherCultOther",message="form.validation.ibd-sitelab-otherCult-was-done-without-result-other"),
 *
 *                      @NSValidators\Other(field="otherTestDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherTestResult",message="form.validation.ibd-sitelab-otherTest-was-done-without-result"),
 *                      @NSValidators\Other(field="otherTestResult",value="\NS\SentinelBundle\Form\Types\CultureResult::OTHER",otherField="otherTestOther",message="form.validation.ibd-sitelab-otherTest-was-done-without-result-other"),
 *
 *                      @NSValidators\Other(field="csfBinaxDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfBinaxResult",message="form.validation.ibd-sitelab-csfBinax-was-done-without-result"),
 *
 *                      @NSValidators\Other(field="spnSerotype",value="\NS\SentinelBundle\Form\Types\SpnSerotype::OTHER",otherField="spnSerotypeOther",message="form.validation.ibd-sitelab-spnSerotype-other-without-data"),
 *                      @NSValidators\Other(field="hiSerotype",value="\NS\SentinelBundle\Form\Types\HiSerotype::OTHER",otherField="hiSerotypeOther",message="form.validation.ibd-sitelab-hiSerotype-other-without-data"),
 *                      @NSValidators\Other(field="nmSerogroup",value="\NS\SentinelBundle\Form\Types\NmSerogroup::OTHER",otherField="nmSerogroupOther",message="form.validation.ibd-sitelab-nmSerotype-other-without-data"),
 *                      } )
 */
class SiteLab extends BaseSiteLab
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD",inversedBy="siteLab",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,unique=true)
     */
    protected $caseFile;

    //Case-based Laboratory Data
    /**
     * @var DateTime $csfDateTime
     * @ORM\Column(name="csfDateTime",type="datetime",nullable=true)
     * @Assert\DateTime
     * @Groups({"api"})
     */
    private $csfDateTime;

    /**
     * @var string $csfId
     * @ORM\Column(name="csfId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfId;

    /**
     * @var string $bloodId
     * @ORM\Column(name="bloodId",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodId;

    /**
     * @var integer $csfWcc
     * @ORM\Column(name="csfWcc", type="integer",nullable=true)
     * @Assert\Range(min=0,max=9999,minMessage="You cannot have a negative white blood cell count",maxMessage="Invalid value")
     * @Groups({"api"})
     */
    private $csfWcc;

    /**
     * @var integer $csfGlucose
     * @ORM\Column(name="csfGlucose", type="integer",nullable=true)
     * @Assert\GreaterThanOrEqual(value=0,message="Invalid value - value must be greater than 0")
     * @Groups({"api"})
     */
    private $csfGlucose;

    /**
     * @var integer $csfProtein
     * @ORM\Column(name="csfProtein", type="integer",nullable=true)
     * @Assert\GreaterThanOrEqual(value=0,message="Invalid value - value must be greater than 0")
     * @Groups({"api"})
     */
    private $csfProtein;

    /**
     * @var TripleChoice $csfCultDone
     * @ORM\Column(name="csfCultDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfCultDone;

    /**
     * @var TripleChoice $csfGramDone
     * @ORM\Column(name="csfGramDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramDone;

    /**
     * @var TripleChoice $csfBinaxDone
     * @ORM\Column(name="csfBinaxDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfBinaxDone;

    /**
     * @var TripleChoice $csfLatDone
     * @ORM\Column(name="csfLatDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfLatDone;

    /**
     * @var TripleChoice $csfPcrDone
     * @ORM\Column(name="csfPcrDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfPcrDone;

    /**
     * @var CultureResult $csfCultResult
     * @ORM\Column(name="csfCultResult",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfCultResult;

    /**
     * @var string $csfCultOther
     * @ORM\Column(name="csfCultOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfCultOther;

    /**
     * @var string $csfCultContaminant
     * @ORM\Column(name="csfCultContaminant",type="string",nullable=true)
     */
    private $csfCultContaminant;

    /**
     * @var GramStain
     * @ORM\Column(name="csfGramStain",type="GramStain",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramStain;

    /**
     * @var GramStainResult $csfGramResult
     * @ORM\Column(name="csfGramResult",type="GramStainResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramResult;

    /**
     * @var string $csfGramOther
     * @ORM\Column(name="csfGramOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramOther;

    /**
     * @var BinaxResult
     * @ORM\Column(name="csfBinaxResult",type="BinaxResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfBinaxResult;

    /**
     * @var LatResult
     * @ORM\Column(name="csfLatResult",type="LatResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfLatResult;

    /**
     * @var string
     * @ORM\Column(name="csfLatOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfLatOther;

    /**
     * @var PCRResult
     * @ORM\Column(name="csfPcrResult",type="PCRResult",nullable=true)
     * @Groups({"api"})
     */
    private $csfPcrResult;

    /**
     * @var string $csfPcrOther
     * @ORM\Column(name="csfPcrOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csfPcrOther;

    /**
     * @var TripleChoice $csfStore
     * @ORM\Column(name="csfStore",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csfStore;

    /**
     * @var TripleChoice $csfStore
     * @ORM\Column(name="isolStore",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $isolStore;

    /**
     * @var SpnSerotype $spnSerotype
     * @ORM\Column(name="spnSerotype",type="SpnSerotype",nullable=true)
     * @Groups({"api"})
     */
    private $spnSerotype;

    /**
     * @var string $spnSerotypeOther
     * @ORM\Column(name="spnSerotypeOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $spnSerotypeOther;

    /**
     * @var HiSerotype $hiSerotype
     * @ORM\Column(name="hiSerotype",type="HiSerotype",nullable=true)
     * @Groups({"api"})
     */
    private $hiSerotype;

    /**
     * @var string $hiSerotypeOther
     * @ORM\Column(name="hiSerotypeOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $hiSerotypeOther;

    /**
     * @var NmSerogroup $nmSerogroup
     * @ORM\Column(name="nmSerogroup",type="NmSerogroup",nullable=true)
     * @Groups({"api"})
     */
    private $nmSerogroup;

    /**
     * @var string $nmSerogroupOther
     * @ORM\Column(name="nmSerogroupOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $nmSerogroupOther;

//==================
    //PNEUMONIA / SEPSIS (In addition to above)

    /**
     * @var TripleChoice $bloodCultDone
     * @ORM\Column(name="bloodCultDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodCultDone;

    /**
     * @var TripleChoice $bloodGramDone
     * @ORM\Column(name="bloodGramDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramDone;

    /**
     * @var TripleChoice $bloodPcrDone
     * @ORM\Column(name="bloodPcrDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $bloodPcrDone;

    /**
     * @var TripleChoice $otherCultDone
     * @ORM\Column(name="otherCultDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $otherCultDone;

    /**
     * @var CultureResult
     * @ORM\Column(name="otherCultResult",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $otherCultResult;

    /**
     * @var string
     * @ORM\Column(name="otherCultOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $otherCultOther;

    /**
     * @var TripleChoice $otherTestDone
     * @ORM\Column(name="otherTestDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $otherTestDone;

    /**
     * @var CultureResult
     * @ORM\Column(name="otherTestResult",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $otherTestResult;

    /**
     * @var string $otherTestOther
     * @ORM\Column(name="otherTestOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $otherTestOther;

    /**
     * @var CultureResult
     * @ORM\Column(name="bloodCultResult",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $bloodCultResult;

    /**
     * @var string
     * @ORM\Column(name="bloodCultOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodCultOther;

    /**
     * @var GramStain
     * @ORM\Column(name="bloodGramStain",type="GramStain",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramStain;

    /**
     * @var GramStainResult $bloodGramResult
     * @ORM\Column(name="bloodGramResult",type="GramStainResult",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramResult;

    /**
     * @var string $bloodGramOther
     * @ORM\Column(name="bloodGramOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramOther;

    /**
     * @var PCRResult
     * @ORM\Column(name="bloodPcrResult",type="PCRResult",nullable=true)
     * @Groups({"api"})
     */
    private $bloodPcrResult;

    /**
     * @var string $bloodPcrOther
     * @ORM\Column(name="bloodPcrOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $bloodPcrOther;

//==================================
    /**
     * RRL_CSF_date     Date when CSF sample was sent to RRL
     *
     * @var \DateTime
     * @ORM\Column(name="csfSentToRRLDate",type="date",nullable=true)
     */
    private $csfSentToRRLDate;

    /**
     * RRL_isol_CSF_date	Date when isolate from CSF was sent to RRL
     *
     * @var \DateTime
     * @ORM\Column(name="csfIsolSentToRRLDate",type="date",nullable=true)
     */
    private $csfIsolSentToRRLDate;

    /**
     * RRL_isol_blood_date	Date when isolate from blood was sent to RRL
     *
     * @var \DateTime
     * @ORM\Column(name="bloodIsolSentToRRLDate",type="date",nullable=true)
     */
    private $bloodIsolSentToRRLDate;

    /**
     * RRL_broth_date	Date when blood broth was sent to RRL
     *
     * @var \DateTime
     * @ORM\Column(name="brothSentToRRLDate",type="date",nullable=true)
     */
    private $brothSentToRRLDate;

//==================================
    /**
     * @var DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Groups({"api"})
     */
    private $updatedAt;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     * @Groups({"api"})
     */
    private $status;

    /**
     * @param null $case
     */
    public function __construct($case = null)
    {
        if ($case instanceof IBD) {
            $this->caseFile = $case;
        }

        $this->updatedAt = new \DateTime();
        $this->status    = new CaseStatus(CaseStatus::OPEN);

        return $this;
    }

    /**
     * @return \DateTime|DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return CaseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param CaseStatus $status
     * @return $this
     */
    public function setStatus(CaseStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function hasCase()
    {
        return ($this->caseFile instanceof IBD);
    }

    /**
     * @return DateTime
     */
    public function getCsfDateTime()
    {
        return $this->csfDateTime;
    }

    /**
     * @return integer
     */
    public function getCsfWcc()
    {
        return $this->csfWcc;
    }

    /**
     * @return integer
     */
    public function getCsfGlucose()
    {
        return $this->csfGlucose;
    }

    /**
     * @return integer
     */
    public function getCsfProtein()
    {
        return $this->csfProtein;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfCultDone()
    {
        return $this->csfCultDone;
    }

    /**
     * 
     * @return string
     */
    public function getCsfCultContaminant()
    {
        return $this->csfCultContaminant;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfGramDone()
    {
        return $this->csfGramDone;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfBinaxDone()
    {
        return $this->csfBinaxDone;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfLatDone()
    {
        return $this->csfLatDone;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfPcrDone()
    {
        return $this->csfPcrDone;
    }

    /**
     * @return CultureResult
     */
    public function getCsfCultResult()
    {
        return $this->csfCultResult;
    }

    /**
     * @return string
     */
    public function getCsfCultOther()
    {
        return $this->csfCultOther;
    }

    /**
     * @return GramStainResult
     */
    public function getCsfGramResult()
    {
        return $this->csfGramResult;
    }

    /**
     * @return GramStain
     */
    public function getCsfGramStain()
    {
        return $this->csfGramStain;
    }

    /**
     * @return string
     */
    public function getCsfGramOther()
    {
        return $this->csfGramOther;
    }

    /**
     * @return BinaxResult
     */
    public function getCsfBinaxResult()
    {
        return $this->csfBinaxResult;
    }

    /**
     * @return LatResult
     */
    public function getCsfLatResult()
    {
        return $this->csfLatResult;
    }

    /**
     * @return string
     */
    public function getCsfLatOther()
    {
        return $this->csfLatOther;
    }

    /**
     * @return PCRResult
     */
    public function getCsfPcrResult()
    {
        return $this->csfPcrResult;
    }

    /**
     * @return string
     */
    public function getCsfPcrOther()
    {
        return $this->csfPcrOther;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfStore()
    {
        return $this->csfStore;
    }

    /**
     * @return TripleChoice
     */
    public function getIsolStore()
    {
        return $this->isolStore;
    }

    /**
     * @return SpnSerotype
     */
    public function getSpnSerotype()
    {
        return $this->spnSerotype;
    }

    /**
     * @return HiSerotype
     */
    public function getHiSerotype()
    {
        return $this->hiSerotype;
    }

    /**
     * @return NmSerogroup
     */
    public function getNmSerogroup()
    {
        return $this->nmSerogroup;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodCultDone()
    {
        return $this->bloodCultDone;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodGramDone()
    {
        return $this->bloodGramDone;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodPcrDone()
    {
        return $this->bloodPcrDone;
    }

    /**
     * @return TripleChoice
     */
    public function getOtherCultDone()
    {
        return $this->otherCultDone;
    }

    /**
     * @return CultureResult
     */
    public function getBloodCultResult()
    {
        return $this->bloodCultResult;
    }

    /**
     * @return string
     */
    public function getBloodCultOther()
    {
        return $this->bloodCultOther;
    }

    /**
     * @return GramStainResult
     */
    public function getBloodGramResult()
    {
        return $this->bloodGramResult;
    }

    /**
     * @return GramStain
     */
    public function getBloodGramStain()
    {
        return $this->bloodGramStain;
    }

    /**
     * @return string
     */
    public function getBloodGramOther()
    {
        return $this->bloodGramOther;
    }

    /**
     * @return PCRResult
     */
    public function getBloodPcrResult()
    {
        return $this->bloodPcrResult;
    }

    /**
     * @return string
     */
    public function getBloodPcrOther()
    {
        return $this->bloodPcrOther;
    }

    /**
     * @return CultureResult
     */
    public function getOtherCultResult()
    {
        return $this->otherCultResult;
    }

    /**
     * @return string
     */
    public function getOtherCultOther()
    {
        return $this->otherCultOther;
    }

    /**
     * @return string
     */
    public function getSpnSerotypeOther()
    {
        return $this->spnSerotypeOther;
    }

    /**
     * @return string
     */
    public function getHiSerotypeOther()
    {
        return $this->hiSerotypeOther;
    }

    /**
     * @return string
     */
    public function getNmSerogroupOther()
    {
        return $this->nmSerogroupOther;
    }

    /**
     * @return string
     */
    public function getCsfId()
    {
        return $this->csfId;
    }

    /**
     * @return string
     */
    public function getBloodId()
    {
        return $this->bloodId;
    }

    /**
     * @return TripleChoice
     */
    public function getOtherTestDone()
    {
        return $this->otherTestDone;
    }

    /**
     * @param TripleChoice $otherTestDone
     */
    public function setOtherTestDone(TripleChoice $otherTestDone)
    {
        $this->otherTestDone = $otherTestDone;
    }

    /**
     * @return CultureResult
     */
    public function getOtherTestResult()
    {
        return $this->otherTestResult;
    }

    /**
     * @param CultureResult $otherTestResult
     * @return SiteLab
     */
    public function setOtherTestResult($otherTestResult)
    {
        $this->otherTestResult = $otherTestResult;
        return $this;
    }

    /**
     * @return string
     */
    public function getOtherTestOther()
    {
        return $this->otherTestOther;
    }

    /**
     * @param string $otherTestOther
     * @return SiteLab
     */
    public function setOtherTestOther($otherTestOther)
    {
        $this->otherTestOther = $otherTestOther;
        return $this;
    }

    /**
     * @param $csfId
     */
    public function setCsfId($csfId)
    {
        $this->csfId = $csfId;
    }

    /**
     * @param $bloodId
     */
    public function setBloodId($bloodId)
    {
        $this->bloodId = $bloodId;
    }

    /**
     * @param $spnSerotypeOther
     */
    public function setSpnSerotypeOther($spnSerotypeOther)
    {
        $this->spnSerotypeOther = $spnSerotypeOther;
    }

    /**
     * @param $hiSerotypeOther
     */
    public function setHiSerotypeOther($hiSerotypeOther)
    {
        $this->hiSerotypeOther = $hiSerotypeOther;
    }

    /**
     * @param $nmSerogroupOther
     */
    public function setNmSerogroupOther($nmSerogroupOther)
    {
        $this->nmSerogroupOther = $nmSerogroupOther;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param $csfDateTime
     * @return $this
     */
    public function setCsfDateTime($csfDateTime)
    {
        $this->csfDateTime = $csfDateTime;
        return $this;
    }

    /**
     * @param $csfWcc
     * @return $this
     */
    public function setCsfWcc($csfWcc)
    {
        $this->csfWcc = $csfWcc;
        return $this;
    }

    /**
     * @param $csfGlucose
     * @return $this
     */
    public function setCsfGlucose($csfGlucose)
    {
        $this->csfGlucose = $csfGlucose;
        return $this;
    }

    /**
     * @param $csfProtein
     * @return $this
     */
    public function setCsfProtein($csfProtein)
    {
        $this->csfProtein = $csfProtein;
        return $this;
    }

    /**
     *
     * @param string $csfCultContaminant
     * @return \NS\SentinelBundle\Entity\IBD\SiteLab
     */
    public function setCsfCultContaminant($csfCultContaminant)
    {
        $this->csfCultContaminant = $csfCultContaminant;
        return $this;
    }

    /**
     * @param TripleChoice $csfCultDone
     * @return $this
     */
    public function setCsfCultDone(TripleChoice $csfCultDone)
    {
        $this->csfCultDone = $csfCultDone;
        return $this;
    }

    /**
     * @param TripleChoice $csfGramDone
     * @return $this
     */
    public function setCsfGramDone(TripleChoice $csfGramDone)
    {
        $this->csfGramDone = $csfGramDone;
        return $this;
    }

    /**
     * @param TripleChoice $csfBinaxDone
     * @return $this
     */
    public function setCsfBinaxDone(TripleChoice $csfBinaxDone)
    {
        $this->csfBinaxDone = $csfBinaxDone;
        return $this;
    }

    /**
     * @param TripleChoice $csfLatDone
     * @return $this
     */
    public function setCsfLatDone(TripleChoice $csfLatDone)
    {
        $this->csfLatDone = $csfLatDone;
        return $this;
    }

    /**
     * @param TripleChoice $csfPcrDone
     * @return $this
     */
    public function setCsfPcrDone(TripleChoice $csfPcrDone)
    {
        $this->csfPcrDone = $csfPcrDone;
        return $this;
    }

    /**
     * @param CultureResult $csfCultResult
     * @return $this
     */
    public function setCsfCultResult(CultureResult $csfCultResult)
    {
        $this->csfCultResult = $csfCultResult;
        return $this;
    }

    /**
     * @param $csfCultOther
     * @return $this
     */
    public function setCsfCultOther($csfCultOther)
    {
        $this->csfCultOther = $csfCultOther;
        return $this;
    }

    /**
     * @param GramStainResult $csfGramResult
     * @return $this
     */
    public function setCsfGramResult(GramStainResult $csfGramResult)
    {
        $this->csfGramResult = $csfGramResult;
        return $this;
    }

    /**
     * @param GramStain $csfGramStain
     * @return $this
     */
    public function setCsfGramStain(GramStain $csfGramStain)
    {
        $this->csfGramStain = $csfGramStain;
        return $this;
    }

    /**
     * @param $csfGramOther
     * @return $this
     */
    public function setCsfGramOther($csfGramOther)
    {
        $this->csfGramOther = $csfGramOther;
        return $this;
    }

    /**
     * @param BinaxResult $csfBinaxResult
     * @return $this
     */
    public function setCsfBinaxResult(BinaxResult $csfBinaxResult)
    {
        $this->csfBinaxResult = $csfBinaxResult;
        return $this;
    }

    /**
     * @param LatResult $csfLatResult
     * @return $this
     */
    public function setCsfLatResult(LatResult $csfLatResult)
    {
        $this->csfLatResult = $csfLatResult;
        return $this;
    }

    /**
     * @param $csfLatOther
     * @return $this
     */
    public function setCsfLatOther($csfLatOther)
    {
        $this->csfLatOther = $csfLatOther;
        return $this;
    }

    /**
     * @param PCRResult $csfPcrResult
     * @return $this
     */
    public function setCsfPcrResult(PCRResult $csfPcrResult)
    {
        $this->csfPcrResult = $csfPcrResult;
        return $this;
    }

    /**
     * @param $csfPcrOther
     * @return $this
     */
    public function setCsfPcrOther($csfPcrOther)
    {
        $this->csfPcrOther = $csfPcrOther;
        return $this;
    }

    /**
     * @param TripleChoice $csfStore
     * @return $this
     */
    public function setCsfStore(TripleChoice $csfStore)
    {
        $this->csfStore = $csfStore;
        return $this;
    }

    /**
     * @param TripleChoice $isolStore
     * @return $this
     */
    public function setIsolStore(TripleChoice $isolStore)
    {
        $this->isolStore = $isolStore;
        return $this;
    }

    /**
     * @param $spnSerotype
     * @return $this
     */
    public function setSpnSerotype($spnSerotype)
    {
        $this->spnSerotype = $spnSerotype;
        return $this;
    }

    /**
     * @param $hiSerotype
     * @return $this
     */
    public function setHiSerotype($hiSerotype)
    {
        $this->hiSerotype = $hiSerotype;
        return $this;
    }

    /**
     * @param $nmSerogroup
     * @return $this
     */
    public function setNmSerogroup($nmSerogroup)
    {
        $this->nmSerogroup = $nmSerogroup;
        return $this;
    }

    /**
     * @param TripleChoice $bloodCultDone
     * @return $this
     */
    public function setBloodCultDone(TripleChoice $bloodCultDone)
    {
        $this->bloodCultDone = $bloodCultDone;
        return $this;
    }

    /**
     * @param TripleChoice $bloodGramDone
     * @return $this
     */
    public function setBloodGramDone(TripleChoice $bloodGramDone)
    {
        $this->bloodGramDone = $bloodGramDone;
        return $this;
    }

    /**
     * @param TripleChoice $bloodPcrDone
     * @return $this
     */
    public function setBloodPcrDone(TripleChoice $bloodPcrDone)
    {
        $this->bloodPcrDone = $bloodPcrDone;
        return $this;
    }

    /**
     * @param TripleChoice $otherCultDone
     * @return $this
     */
    public function setOtherCultDone(TripleChoice $otherCultDone)
    {
        $this->otherCultDone = $otherCultDone;
        return $this;
    }

    /**
     * @param CultureResult $bloodCultResult
     * @return $this
     */
    public function setBloodCultResult(CultureResult $bloodCultResult)
    {
        $this->bloodCultResult = $bloodCultResult;
        return $this;
    }

    /**
     * @param $bloodCultOther
     * @return $this
     */
    public function setBloodCultOther($bloodCultOther)
    {
        $this->bloodCultOther = $bloodCultOther;
        return $this;
    }

    /**
     * @param GramStainResult $bloodGramResult
     * @return $this
     */
    public function setBloodGramResult(GramStainResult $bloodGramResult)
    {
        $this->bloodGramResult = $bloodGramResult;
        return $this;
    }

    /**
     * @param GramStain $bloodGramStain
     * @return $this
     */
    public function setBloodGramStain(GramStain $bloodGramStain)
    {
        $this->bloodGramStain = $bloodGramStain;
        return $this;
    }

    /**
     * @param $bloodGramOther
     * @return $this
     */
    public function setBloodGramOther($bloodGramOther)
    {
        $this->bloodGramOther = $bloodGramOther;
        return $this;
    }

    /**
     * @param PCRResult $bloodPcrResult
     * @return $this
     */
    public function setBloodPcrResult(PCRResult $bloodPcrResult)
    {
        $this->bloodPcrResult = $bloodPcrResult;
        return $this;
    }

    /**
     * @param $bloodPcrOther
     * @return $this
     */
    public function setBloodPcrOther($bloodPcrOther)
    {
        $this->bloodPcrOther = $bloodPcrOther;
        return $this;
    }

    /**
     * @param CultureResult $otherCultResult
     * @return $this
     */
    public function setOtherCultResult(CultureResult $otherCultResult)
    {
        $this->otherCultResult = $otherCultResult;
        return $this;
    }

    /**
     * @param $otherCultOther
     * @return $this
     */
    public function setOtherCultOther($otherCultOther)
    {
        $this->otherCultOther = $otherCultOther;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCsfSentToRRLDate()
    {
        return $this->csfSentToRRLDate;
    }

    /**
     * @param \DateTime $csfSentToRRLDate
     * @return SiteLab
     */
    public function setCsfSentToRRLDate(\DateTime $csfSentToRRLDate = null)
    {
        $this->csfSentToRRLDate = $csfSentToRRLDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCsfIsolSentToRRLDate()
    {
        return $this->csfIsolSentToRRLDate;
    }

    /**
     * @param \DateTime $csfIsolSentToRRLDate
     * @return SiteLab
     */
    public function setCsfIsolSentToRRLDate(\DateTime $csfIsolSentToRRLDate = null)
    {
        $this->csfIsolSentToRRLDate = $csfIsolSentToRRLDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBloodIsolSentToRRLDate()
    {
        return $this->bloodIsolSentToRRLDate;
    }

    /**
     * @param \DateTime $bloodIsolSentToRRLDate
     * @return SiteLab
     */
    public function setBloodIsolSentToRRLDate(\DateTime $bloodIsolSentToRRLDate = null)
    {
        $this->bloodIsolSentToRRLDate = $bloodIsolSentToRRLDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBrothSentToRRLDate()
    {
        return $this->brothSentToRRLDate;
    }

    /**
     * @param \DateTime $brothSentToRRLDate
     * @return SiteLab
     */
    public function setBrothSentToRRLDate(\DateTime $brothSentToRRLDate = null)
    {
        $this->brothSentToRRLDate = $brothSentToRRLDate;
        return $this;
    }


    /**
     * @return bool
     */
    public function isComplete()
    {
        return $this->status->equal(CaseStatus::COMPLETE);
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->_calculateStatus();

        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->_calculateStatus();

        $this->updatedAt = new \DateTime();
    }

    /**
     *
     */
    private function _calculateStatus()
    {
        // Don't adjust cancelled or deleted records
        if ($this->status->getValue() >= CaseStatus::CANCELLED) {
            return;
        }

        if ($this->getIncompleteField()) {
            $this->status->setValue(CaseStatus::OPEN);
        } else {
            $this->status->setValue(CaseStatus::COMPLETE);
        }

        return;
    }

    /**
     * @return null
     */
    public function getIncompleteField()
    {
        foreach ($this->getMinimumRequiredFields() as $field)
        {
            if ($this->$field === null || empty($this->$field) || ($this->$field instanceof ArrayChoice && $this->$field->equal(-1)))
                return $field;
        }

        //Additional Tests as needed (result=other && other fields etc)

        return null;
    }

    /**
     * @return array
     */
    public function getMinimumRequiredFields()
    {
        return array(
            'csfLabDateTime',
            'csfWcc',
            'csfGlucose',
            'csfProtein',
            'csfCultDone',
            'csfGramDone',
            'csfBinaxDone',
            'csfLatDone',
            'csfPcrDone',
            'csfStore',
            'isolStore',
            'bloodCultDone',
            'bloodGramDone',
            'bloodPcrDone',
            'otherCultDone',
            'spnSerotype',
            'hiSerotype',
            'nmSerogroup',
        );
    }
}
