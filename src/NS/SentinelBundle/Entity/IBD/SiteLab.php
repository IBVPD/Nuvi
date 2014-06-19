<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\BaseSiteLab;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\LatResult;
use NS\SentinelBundle\Form\Types\CultureResult;
use NS\SentinelBundle\Form\Types\GramStain;
use NS\SentinelBundle\Form\Types\GramStainOrganism;
use NS\SentinelBundle\Form\Types\BinaxResult;
use NS\SentinelBundle\Form\Types\PCRResult;
use NS\SentinelBundle\Form\Types\CaseStatus;
use Symfony\Component\Validator\ExecutionContextInterface;
use NS\SentinelBundle\Form\Types\SpnSerotype;
use NS\SentinelBundle\Form\Types\HiSerotype;
use NS\SentinelBundle\Form\Types\NmSerogroup;

use Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of SiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD\SiteLab")
 * @ORM\Table(name="ibd_site_labs")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB","ROLE_NL_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @Assert\Callback(methods={"validate"})
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
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis",inversedBy="siteLab")
     * @ORM\JoinColumn(nullable=false,unique=true)
     */
    private $case;

    //Case-based Laboratory Data
    /**
     * @var DateTime $csfLabDateTime
     * @ORM\Column(name="csfLabDateTime",type="datetime",nullable=true)
     * @Assert\DateTime
     */
    private $csfLabDateTime;

    /**
     * @var string $csfId
     * @ORM\Column(name="csfId",type="string",nullable=true)
     */
    private $csfId;

    /**
     * @var string $bloodId
     * @ORM\Column(name="bloodId",type="string",nullable=true)
     */
    private $bloodId;

    /**
     * @var boolean $csfWcc
     * @ORM\Column(name="csfWcc", type="integer",nullable=true)
     * @Assert\Range(min=0,max=9999,minMessage="You cannot have a negative white blood cell count",maxMessage="Invalid value")
     */
    private $csfWcc;

    /**
     * @var boolean $csfGlucose
     * @ORM\Column(name="csfGlucose", type="integer",nullable=true)
     * @Assert\GreaterThanOrEqual(value=0,message="Invalid value - value must be greater than 0")
     *
     */
    private $csfGlucose;

    /**
     * @var boolean $csfProtein
     * @ORM\Column(name="csfProtein", type="integer",nullable=true)
     * @Assert\GreaterThanOrEqual(value=0,message="Invalid value - value must be greater than 0")
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
     * @var CultureResult $csfCultResult
     * @ORM\Column(name="csfCultResult",type="CultureResult",nullable=true)
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
     * @var GramStainOrganism $csfGramResultOrganism
     * @ORM\Column(name="csfGramResultOrganism",type="GramStainOrganism",nullable=true)
     */
    private $csfGramResultOrganism;

    /**
     * @var string $csfGramOther
     * @ORM\Column(name="csfGramOther",type="string",nullable=true)
     */
    private $csfGramOther;

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
     * @var string $csfPcrOther
     * @ORM\Column(name="csfPcrOther",type="string",nullable=true)
     */
    private $csfPcrOther;

    /**
     * @var DateTime $rrlCsfDate
     * @ORM\Column(name="rrlCsfDate",type="date",nullable=true)
     * @Assert\Date
     */
    private $rrlCsfDate;

    /**
     * @var DateTime $rrlIsoDate
     * @ORM\Column(name="rrlIsoDate",type="date",nullable=true)
     * @Assert\Date
     */
    private $rrlIsolDate;

    /**
     * @var DateTime $rrlIsolBloodDate
     * @ORM\Column(name="rrlIsolBloodDate",type="date",nullable=true)
     * @Assert\Date
     */
    private $rrlIsolBloodDate;

    /**
     * @var DateTime $rrlBrothDate
     * @ORM\Column(name="rrlBrothDate",type="date",nullable=true)
     * @Assert\Date
     */
    private $rrlBrothDate;

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
     * @var SpnSerotype $spnSerotype
     * @ORM\Column(name="spnSerotype",type="SpnSerotype",nullable=true)
     */
    private $spnSerotype;

    /**
     * @var string $spnSerotypeOther
     * @ORM\Column(name="spnSerotypeOther",type="string",nullable=true)
     */
    private $spnSerotypeOther;

    /**
     * @var HiSerotype $hiSerotyoe
     * @ORM\Column(name="hiSerotyoe",type="HiSerotype",nullable=true)
     */
    private $hiSerotype;

    /**
     * @var string $hiSerotypeOther
     * @ORM\Column(name="hiSerotypeOther",type="string",nullable=true)
     */
    private $hiSerotypeOther;

    /**
     * @var NmSerogroup $nmSerogroup
     * @ORM\Column(name="nmSerogroup",type="NmSerogroup",nullable=true)
     */
    private $nmSerogroup;

    /**
     * @var string $nmSerogroupOther
     * @ORM\Column(name="nmSerogroupOther",type="string",nullable=true)
     */
    private $nmSerogroupOther;

//==================
    //PNEUMONIA / SEPSIS (In addition to above)

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
     * @var string $otherTest
     * @ORM\Column(name="otherTest",type="string",nullable=true)
     */
    private $otherTest;

    /**
     * @var CultureResult
     * @ORM\Column(name="bloodCultResult",type="CultureResult",nullable=true)
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
     * @var GramStainOrganism $bloodGramResultOrganism
     * @ORM\Column(name="bloodGramResultOrganism",type="GramStainOrganism",nullable=true)
     */
    private $bloodGramResultOrganism;

    /**
     * @var string $bloodGramOther
     * @ORM\Column(name="bloodGramOther",type="string",nullable=true)
     */
    private $bloodGramOther;

    /**
     * @var PCRResult
     * @ORM\Column(name="bloodPcrResult",type="PCRResult",nullable=true)
     */
    private $bloodPcrResult;

    /**
     * @var string $bloodPcrOther
     * @ORM\Column(name="bloodPcrOther",type="string",nullable=true)
     */
    private $bloodPcrOther;

    /**
     * @var CultureResult
     * @ORM\Column(name="otherCultResult",type="CultureResult",nullable=true)
     */
    private $otherCultResult;

    /**
     * @var string
     * @ORM\Column(name="otherCultOther",type="string",nullable=true)
     */
    private $otherCultOther;

    /**
     * @var DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     */
    private $updatedAt;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     */
    private $status;

    public function __construct($case = null)
    {
        if($case instanceof Meningitis)
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
        return ($this->case instanceof Meningitis);
    }

    public function getCsfLabDateTime()
    {
        return $this->csfLabDateTime;
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

    public function getRrlCsfDate()
    {
        return $this->rrlCsfDate;
    }

    public function getRrlIsolDate()
    {
        return $this->rrlIsolDate;
    }

    public function getRrlIsolBloodDate()
    {
        return $this->rrlIsolBloodDate;
    }

    public function getRrlBrothDate()
    {
        return $this->rrlBrothDate;
    }

    public function getCsfStore()
    {
        return $this->csfStore;
    }

    public function getIsolStore()
    {
        return $this->isolStore;
    }

    public function getRrlName()
    {
        return $this->rrlName;
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

    public function setCsfLabDateTime($csfLabDateTime)
    {
        $this->csfLabDateTime = $csfLabDateTime;
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

    public function setRrlCsfDate($rrlCsfDate)
    {
        $this->rrlCsfDate = $rrlCsfDate;
        return $this;
    }

    public function setRrlIsolDate($rrlIsolDate)
    {
        $this->rrlIsolDate = $rrlIsolDate;
        return $this;
    }

    public function setRrlIsolBloodDate($rrlIsolBloodDate)
    {
        $this->rrlIsolBloodDate = $rrlIsolBloodDate;
        return $this;
    }

    public function setRrlBrothDate($rrlBrothDate)
    {
        $this->rrlBrothDate = $rrlBrothDate;
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

    public function setRrlName($rrlName)
    {
        $this->rrlName = $rrlName;
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

    public function validate(ExecutionContextInterface $context)
    {
        if($this->csfCultDone && $this->csfCultDone->equal(TripleChoice::YES) && !$this->csfCultResult)
            $context->addViolationAt('csfCultDone',"form.validation.meningitis-sitelab-csfCult-was-done-without-result");

        if($this->csfCultDone && $this->csfCultDone->equal(TripleChoice::YES) && $this->csfCultResult && $this->csfCultResult->equal(CultureResult::OTHER) && empty($this->csfCultOther))
            $context->addViolationAt('csfCultDone',"form.validation.meningitis-sitelab-csfCult-was-done-without-result-other");

        if($this->csfBinaxDone && $this->csfBinaxDone->equal(TripleChoice::YES) && !$this->csfBinaxResult)
            $context->addViolationAt('csfBinaxDone',"form.validation.meningitis-sitelab-csfBinax-was-done-without-result");

        if($this->csfLatDone && $this->csfLatDone->equal(TripleChoice::YES) && !$this->csfLatResult)
            $context->addViolationAt('csfLatDone',"form.validation.meningitis-sitelab-csfLat-was-done-without-result");

        if($this->csfLatDone && $this->csfLatDone->equal(TripleChoice::YES) && $this->csfLatResult && $this->csfLatResult->equal(LatResult::OTHER) && empty($this->csfLatOther))
            $context->addViolationAt('csfLatDone',"form.validation.meningitis-sitelab-csfLat-was-done-without-result-other");

        if($this->csfPcrDone && $this->csfPcrDone->equal(TripleChoice::YES) && !$this->csfPcrResult)
            $context->addViolationAt('csfPcrDone',"form.validation.meningitis-sitelab-csfPcr-was-done-without-result");

        if($this->csfPcrDone && $this->csfPcrDone->equal(TripleChoice::YES) && $this->csfPcrResult && $this->csfPcrResult->equal(PCRResult::OTHER) && empty($this->csfPcrOther))
            $context->addViolationAt('csfPcrDone',"form.validation.meningitis-sitelab-csfPcr-was-done-without-result");

        if($this->spnSerotype && $this->spnSerotype->equal(SpnSerotype::OTHER) && (!$this->spnSerotypeOther || empty($this->spnSerotypeOther)))
            $context->addViolationAt('spnSerotype',"form.validation.meningitis-sitelab-spnSerotype-other-without-data");

        if($this->hiSerotype && $this->hiSerotype->equal(HiSerotype::OTHER) && (!$this->hiSerotypeOther || empty($this->hiSerotypeOther)))
            $context->addViolationAt('hiSerotype',"form.validation.meningitis-sitelab-hiSerotype-other-without-data");

        if($this->nmSerogroup && $this->nmSerogroup->equal(NmSerogroup::OTHER) && (!$this->nmSerogroupOther || empty($this->nmSerogroupOther)))
            $context->addViolationAt('nmSerogroup',"form.validation.meningitis-sitelab-nmSerogroup-other-without-data");

        if($this->bloodCultDone && $this->bloodCultDone->equal(TripleChoice::YES) && !$this->bloodCultResult)
            $context->addViolationAt('csfCultDone',"form.validation.meningitis-sitelab-bloodCult-was-done-without-result");

        if($this->bloodCultDone && $this->bloodCultDone->equal(TripleChoice::YES) && $this->bloodCultResult && $this->bloodCultResult->equal(CultureResult::OTHER) && empty($this->bloodCultOther))
            $context->addViolationAt('bloodCultDone',"form.validation.meningitis-sitelab-bloodCult-was-done-without-result");

        if($this->otherCultDone && $this->otherCultDone->equal(TripleChoice::YES) && !$this->otherCultResult)
            $context->addViolationAt('csfCultDone',"form.validation.meningitis-sitelab-otherCult-was-done-without-result");

        if($this->otherCultDone && $this->otherCultDone->equal(TripleChoice::YES) && $this->otherCultResult && $this->otherCultResult->equal(CultureResult::OTHER) && empty($this->otherCultOther))
            $context->addViolationAt('otherCultDone',"form.validation.meningitis-sitelab-otherCult-was-done-without-result");
    }

    private function _calculateStatus()
    {
        // Don't adjust cancelled or deleted records
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
                    'rrlCsfDate',
                    'rrlIsolDate',
                    'csfStore',
                    'isolStore',
                    'bloodCultDone',
                    'bloodGramDone',
                    'bloodPcrDone',
                    'rrlBrothDate',
                    'rrlIsolBloodDate',
                    'otherCultDone',
                    'spnSerotype',
                    'hiSerotype',
                    'nmSerogroup',
                    );
    }
}