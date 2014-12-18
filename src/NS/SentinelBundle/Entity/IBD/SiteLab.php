<?php

namespace NS\SentinelBundle\Entity\IBD;

use \Doctrine\ORM\Mapping as ORM;
use \Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
use \NS\SentinelBundle\Entity\BaseSiteLab;
use \NS\SentinelBundle\Form\Types\BinaxResult;
use \NS\SentinelBundle\Form\Types\CaseStatus;
use \NS\SentinelBundle\Form\Types\CultureResult;
use \NS\SentinelBundle\Form\Types\GramStain;
use \NS\SentinelBundle\Form\Types\GramStainOrganism;
use \NS\SentinelBundle\Form\Types\HiSerotype;
use \NS\SentinelBundle\Form\Types\LatResult;
use \NS\SentinelBundle\Form\Types\NmSerogroup;
use \NS\SentinelBundle\Form\Types\PCRResult;
use \NS\SentinelBundle\Form\Types\SpnSerotype;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\UtilBundle\Form\Types\ArrayChoice;
use \Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\Validator\Constraints\DateTime;
use \Symfony\Component\Validator\ExecutionContextInterface;
use \JMS\Serializer\Annotation\Groups;
use \NS\SentinelBundle\Validators as NSValidators;


/**
 *
 * Description of SiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD\SiteLab")
 * @ORM\Table(name="ibd_site_labs")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @NSValidators\AllOther( {
 *                      @NSValidators\Other(field="csfCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfCultResult"),
 *                      @NSValidators\Other(field="csfCultResult",value="\NS\SentinelBundle\Form\Types\CultureResult::OTHER",otherField="csfCultOther"),
 *
 *                      @NSValidators\Other(field="csfLatDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfLatResult"),
 *                      @NSValidators\Other(field="csfLatResult",value="\NS\SentinelBundle\Form\Types\LatResult::OTHER",otherField="csfLatOther"),
 * 
 *                      @NSValidators\Other(field="csfPcrDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfPcrResult"),
 *                      @NSValidators\Other(field="csfPcrResult",value="\NS\SentinelBundle\Form\Types\PCRResult::OTHER",otherField="csfPcrOther"),
 *
 *                      @NSValidators\Other(field="bloodCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="bloodCultResult"),
 *                      @NSValidators\Other(field="bloodCultResult",value="\NS\SentinelBundle\Form\Types\CultureResult::OTHER",otherField="bloodCultOther"),
 *
 *                      @NSValidators\Other(field="otherCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherCultResult"),
 *                      @NSValidators\Other(field="otherCultResult",value="\NS\SentinelBundle\Form\Types\CultureResult::OTHER",otherField="otherCultOther"),
 *
 *                      @NSValidators\Other(field="csfBinaxDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfBinaxResult"),
 *                      @NSValidators\Other(field="spnSerotype",value="\NS\SentinelBundle\Form\Types\SpnSerotype::OTHER",otherField="spnSerotypeOther"),
 *                      @NSValidators\Other(field="hiSerotype",value="\NS\SentinelBundle\Form\Types\HiSerotype::OTHER",otherField="hiSerotypeOther"),
 *                      @NSValidators\Other(field="nmSerogroup",value="\NS\SentinelBundle\Form\Types\NmSerogroup::OTHER",otherField="nmSerogroupOther"),
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
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD",inversedBy="siteLab")
     * @ORM\JoinColumn(nullable=false,unique=true)
     */
    protected $case;
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
     * @var boolean $csfWcc
     * @ORM\Column(name="csfWcc", type="integer",nullable=true)
     * @Assert\Range(min=0,max=9999,minMessage="You cannot have a negative white blood cell count",maxMessage="Invalid value")
     * @Groups({"api"})
     */
    private $csfWcc;

    /**
     * @var boolean $csfGlucose
     * @ORM\Column(name="csfGlucose", type="integer",nullable=true)
     * @Assert\GreaterThanOrEqual(value=0,message="Invalid value - value must be greater than 0")
     * @Groups({"api"})
     */
    private $csfGlucose;

    /**
     * @var boolean $csfProtein
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
     * @var GramStain
     * @ORM\Column(name="csfGramResult",type="GramStain",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramResult;

    /**
     * @var GramStainOrganism $csfGramResultOrganism
     * @ORM\Column(name="csfGramResultOrganism",type="GramStainOrganism",nullable=true)
     * @Groups({"api"})
     */
    private $csfGramResultOrganism;

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
     * @var TripleChoice $otherTestDone
     * @ORM\Column(name="otherTestDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $otherTestDone;

    /**
     * @var string $otherTest
     * @ORM\Column(name="otherTest",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $otherTest;

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
     * @ORM\Column(name="bloodGramResult",type="GramStain",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramResult;

    /**
     * @var GramStainOrganism $bloodGramResultOrganism
     * @ORM\Column(name="bloodGramResultOrganism",type="GramStainOrganism",nullable=true)
     * @Groups({"api"})
     */
    private $bloodGramResultOrganism;

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

    public function __construct($case = null)
    {
        if ($case instanceof IBD)
            $this->case = $case;

        $this->updatedAt = new \DateTime();
        $this->status    = new CaseStatus(CaseStatus::OPEN);

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(CaseStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCase()
    {
        return $this->case;
    }

    public function hasCase()
    {
        return ($this->case instanceof IBD);
    }

    public function getCsfDateTime()
    {
        return $this->csfDateTime;
    }

    public function getCsfWcc()
    {
        return $this->csfWcc;
    }

    public function getCsfGlucose()
    {
        return $this->csfGlucose;
    }

    public function getCsfProtein()
    {
        return $this->csfProtein;
    }

    public function getCsfCultDone()
    {
        return $this->csfCultDone;
    }

    public function getCsfGramDone()
    {
        return $this->csfGramDone;
    }

    public function getCsfBinaxDone()
    {
        return $this->csfBinaxDone;
    }

    public function getCsfLatDone()
    {
        return $this->csfLatDone;
    }

    public function getCsfPcrDone()
    {
        return $this->csfPcrDone;
    }

    public function getCsfCultResult()
    {
        return $this->csfCultResult;
    }

    public function getCsfCultOther()
    {
        return $this->csfCultOther;
    }

    public function getCsfGramResult()
    {
        return $this->csfGramResult;
    }

    public function getCsfGramResultOrganism()
    {
        return $this->csfGramResultOrganism;
    }

    public function getCsfGramOther()
    {
        return $this->csfGramOther;
    }

    public function getCsfBinaxResult()
    {
        return $this->csfBinaxResult;
    }

    public function getCsfLatResult()
    {
        return $this->csfLatResult;
    }

    public function getCsfLatOther()
    {
        return $this->csfLatOther;
    }

    public function getCsfPcrResult()
    {
        return $this->csfPcrResult;
    }

    public function getCsfPcrOther()
    {
        return $this->csfPcrOther;
    }

    public function getCsfStore()
    {
        return $this->csfStore;
    }

    public function getIsolStore()
    {
        return $this->isolStore;
    }

    public function getSpnSerotype()
    {
        return $this->spnSerotype;
    }

    public function getHiSerotype()
    {
        return $this->hiSerotype;
    }

    public function getNmSerogroup()
    {
        return $this->nmSerogroup;
    }

    public function getBloodCultDone()
    {
        return $this->bloodCultDone;
    }

    public function getBloodGramDone()
    {
        return $this->bloodGramDone;
    }

    public function getBloodPcrDone()
    {
        return $this->bloodPcrDone;
    }

    public function getOtherCultDone()
    {
        return $this->otherCultDone;
    }

    public function getBloodCultResult()
    {
        return $this->bloodCultResult;
    }

    public function getBloodCultOther()
    {
        return $this->bloodCultOther;
    }

    public function getBloodGramResult()
    {
        return $this->bloodGramResult;
    }

    public function getBloodGramResultOrganism()
    {
        return $this->bloodGramResultOrganism;
    }

    public function getBloodGramOther()
    {
        return $this->bloodGramOther;
    }

    public function getBloodPcrResult()
    {
        return $this->bloodPcrResult;
    }

    public function getBloodPcrOther()
    {
        return $this->bloodPcrOther;
    }

    public function getOtherCultResult()
    {
        return $this->otherCultResult;
    }

    public function getOtherCultOther()
    {
        return $this->otherCultOther;
    }

    public function getSpnSerotypeOther()
    {
        return $this->spnSerotypeOther;
    }

    public function getHiSerotypeOther()
    {
        return $this->hiSerotypeOther;
    }

    public function getNmSerogroupOther()
    {
        return $this->nmSerogroupOther;
    }

    public function getCsfId()
    {
        return $this->csfId;
    }

    public function getBloodId()
    {
        return $this->bloodId;
    }

    public function getOtherTestDone()
    {
        return $this->otherTestDone;
    }

    public function getOtherTest()
    {
        return $this->otherTest;
    }

    public function setOtherTestDone(TripleChoice $otherTestDone)
    {
        $this->otherTestDone = $otherTestDone;
    }

    public function setOtherTest($otherTest)
    {
        $this->otherTest = $otherTest;
    }

    public function setCsfId($csfId)
    {
        $this->csfId = $csfId;
    }

    public function setBloodId($bloodId)
    {
        $this->bloodId = $bloodId;
    }

    public function setSpnSerotypeOther($spnSerotypeOther)
    {
        $this->spnSerotypeOther = $spnSerotypeOther;
    }

    public function setHiSerotypeOther($hiSerotypeOther)
    {
        $this->hiSerotypeOther = $hiSerotypeOther;
    }

    public function setNmSerogroupOther($nmSerogroupOther)
    {
        $this->nmSerogroupOther = $nmSerogroupOther;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCase($case)
    {
        $this->case = $case;
        return $this;
    }

    public function setCsfDateTime($csfDateTime)
    {
        $this->csfDateTime = $csfDateTime;
        return $this;
    }

    public function setCsfWcc($csfWcc)
    {
        $this->csfWcc = $csfWcc;
        return $this;
    }

    public function setCsfGlucose($csfGlucose)
    {
        $this->csfGlucose = $csfGlucose;
        return $this;
    }

    public function setCsfProtein($csfProtein)
    {
        $this->csfProtein = $csfProtein;
        return $this;
    }

    public function setCsfCultDone(TripleChoice $csfCultDone)
    {
        $this->csfCultDone = $csfCultDone;
        return $this;
    }

    public function setCsfGramDone(TripleChoice $csfGramDone)
    {
        $this->csfGramDone = $csfGramDone;
        return $this;
    }

    public function setCsfBinaxDone(TripleChoice $csfBinaxDone)
    {
        $this->csfBinaxDone = $csfBinaxDone;
        return $this;
    }

    public function setCsfLatDone(TripleChoice $csfLatDone)
    {
        $this->csfLatDone = $csfLatDone;
        return $this;
    }

    public function setCsfPcrDone(TripleChoice $csfPcrDone)
    {
        $this->csfPcrDone = $csfPcrDone;
        return $this;
    }

    public function setCsfCultResult(CultureResult $csfCultResult)
    {
        $this->csfCultResult = $csfCultResult;
        return $this;
    }

    public function setCsfCultOther($csfCultOther)
    {
        $this->csfCultOther = $csfCultOther;
        return $this;
    }

    public function setCsfGramResult(GramStain $csfGramResult)
    {
        $this->csfGramResult = $csfGramResult;
        return $this;
    }

    public function setCsfGramResultOrganism(GramStainOrganism $csfGramResultOrganism)
    {
        $this->csfGramResultOrganism = $csfGramResultOrganism;
        return $this;
    }

    public function setCsfGramOther($csfGramOther)
    {
        $this->csfGramOther = $csfGramOther;
        return $this;
    }

    public function setCsfBinaxResult(BinaxResult $csfBinaxResult)
    {
        $this->csfBinaxResult = $csfBinaxResult;
        return $this;
    }

    public function setCsfLatResult(LatResult $csfLatResult)
    {
        $this->csfLatResult = $csfLatResult;
        return $this;
    }

    public function setCsfLatOther($csfLatOther)
    {
        $this->csfLatOther = $csfLatOther;
        return $this;
    }

    public function setCsfPcrResult(PCRResult $csfPcrResult)
    {
        $this->csfPcrResult = $csfPcrResult;
        return $this;
    }

    public function setCsfPcrOther($csfPcrOther)
    {
        $this->csfPcrOther = $csfPcrOther;
        return $this;
    }

    public function setCsfStore(TripleChoice $csfStore)
    {
        $this->csfStore = $csfStore;
        return $this;
    }

    public function setIsolStore(TripleChoice $isolStore)
    {
        $this->isolStore = $isolStore;
        return $this;
    }

    public function setSpnSerotype($spnSerotype)
    {
        $this->spnSerotype = $spnSerotype;
        return $this;
    }

    public function setHiSerotype($hiSerotype)
    {
        $this->hiSerotype = $hiSerotype;
        return $this;
    }

    public function setNmSerogroup($nmSerogroup)
    {
        $this->nmSerogroup = $nmSerogroup;
        return $this;
    }

    public function setBloodCultDone(TripleChoice $bloodCultDone)
    {
        $this->bloodCultDone = $bloodCultDone;
        return $this;
    }

    public function setBloodGramDone(TripleChoice $bloodGramDone)
    {
        $this->bloodGramDone = $bloodGramDone;
        return $this;
    }

    public function setBloodPcrDone(TripleChoice $bloodPcrDone)
    {
        $this->bloodPcrDone = $bloodPcrDone;
        return $this;
    }

    public function setOtherCultDone(TripleChoice $otherCultDone)
    {
        $this->otherCultDone = $otherCultDone;
        return $this;
    }

    public function setBloodCultResult(CultureResult $bloodCultResult)
    {
        $this->bloodCultResult = $bloodCultResult;
        return $this;
    }

    public function setBloodCultOther($bloodCultOther)
    {
        $this->bloodCultOther = $bloodCultOther;
        return $this;
    }

    public function setBloodGramResult(GramStain $bloodGramResult)
    {
        $this->bloodGramResult = $bloodGramResult;
        return $this;
    }

    public function setBloodGramResultOrganism(GramStainOrganism $bloodGramResultOrganism)
    {
        $this->bloodGramResultOrganism = $bloodGramResultOrganism;
        return $this;
    }

    public function setBloodGramOther($bloodGramOther)
    {
        $this->bloodGramOther = $bloodGramOther;
        return $this;
    }

    public function setBloodPcrResult(PCRResult $bloodPcrResult)
    {
        $this->bloodPcrResult = $bloodPcrResult;
        return $this;
    }

    public function setBloodPcrOther($bloodPcrOther)
    {
        $this->bloodPcrOther = $bloodPcrOther;
        return $this;
    }

    public function setOtherCultResult(CultureResult $otherCultResult)
    {
        $this->otherCultResult = $otherCultResult;
        return $this;
    }

    public function setOtherCultOther($otherCultOther)
    {
        $this->otherCultOther = $otherCultOther;
        return $this;
    }

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

    private function _calculateStatus()
    {
        // Don't adjust cancelled or deleted records
        if ($this->status->getValue() >= CaseStatus::CANCELLED)
            return;

        if ($this->getIncompleteField())
            $this->status->setValue(CaseStatus::OPEN);
        else
            $this->status->setValue(CaseStatus::COMPLETE);

        return;
    }

    public function getIncompleteField()
    {
        foreach ($this->getMinimumRequiredFields() as $field)
        {
            if (is_null($this->$field) || empty($this->$field) || ($this->$field instanceof ArrayChoice && $this->$field->equal(-1)))
                return $field;
        }

        //Additional Tests as needed (result=other && other fields etc)

        return null;
    }

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
