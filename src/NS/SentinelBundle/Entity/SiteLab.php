<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Form\Types\LatResult;
use \NS\SentinelBundle\Form\Types\GramStain;
use \NS\SentinelBundle\Form\Types\GramStainOrganism;
use \NS\SentinelBundle\Form\Types\BinaxResult;
use \NS\SentinelBundle\Form\Types\PCRResult;
use \NS\SentinelBundle\Form\Types\CXRResult;

use Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\DateTime
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
     * @var TripleChoice $cxrDone
     * @ORM\Column(name="cxrDone",type="TripleChoice",nullable=true)
     */
    private $cxrDone;

    /**
     * @var CXRResult $cxrResult
     * @ORM\Column(name="cxrResult",type="CXRResult",nullable=true)
     */
    private $cxrResult;

    public function __construct($case = null)
    {
        if($case instanceof Meningitis)
            $this->case = $case;

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

    public function getOtherTestDone()
    {
        return $this->otherTestDone;
    }

    public function getOtherTest()
    {
        return $this->otherTest;
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

    public function getOtherTestResult()
    {
        return $this->otherTestResult;
    }

    public function getOtherTestOther()
    {
        return $this->otherTestOther;
    }

    public function getCxrDone()
    {
        return $this->cxrDone;
    }

    public function getCxrResult()
    {
        return $this->cxrResult;
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

    public function setCsfCultResult(LatResult $csfCultResult)
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

    public function setOtherTestDone(TripleChoice $otherTestDone)
    {
        $this->otherTestDone = $otherTestDone;
        return $this;
    }

    public function setOtherTest($otherTest)
    {
        $this->otherTest = $otherTest;
        return $this;
    }

    public function setBloodCultResult(LatResult $bloodCultResult)
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

    public function setOtherCultResult(LatResult $otherCultResult)
    {
        $this->otherCultResult = $otherCultResult;
        return $this;
    }

    public function setOtherCultOther($otherCultOther)
    {
        $this->otherCultOther = $otherCultOther;
        return $this;
    }

    public function setOtherTestResult(PCRResult $otherTestResult)
    {
        $this->otherTestResult = $otherTestResult;
        return $this;
    }

    public function setOtherTestOther($otherTestOther)
    {
        $this->otherTestOther = $otherTestOther;
        return $this;
    }

    public function setCxrDone(TripleChoice $cxrDone)
    {
        $this->cxrDone = $cxrDone;
        return $this;
    }

    public function setCxrResult(CXRResult $cxrResult)
    {
        $this->cxrResult = $cxrResult;
        return $this;
    }
}
