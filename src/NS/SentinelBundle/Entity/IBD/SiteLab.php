<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;
use NS\SecurityBundle\Annotation as Security;
use NS\SentinelBundle\Entity\BaseSiteLab;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Form\IBD\Types\BinaxResult;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\CaseStatus;

use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\LatResult;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;

use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Validators as LocalAssert;
use JMS\Serializer\Annotation\Groups;

/**
 *
 * Description of SiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\IBD\SiteLabRepository")
 * @ORM\Table(name="ibd_site_labs")
 * @Security\Secured(conditions={
 *      @Security\SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @Security\SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @Security\SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @LocalAssert\AllOther( {
 *                      @LocalAssert\Other(field="csfCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfCultResult",message="form.validation.ibd-sitelab-csfCult-was-done-without-result"),
 *                      @LocalAssert\Other(field="csfCultResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="csfCultOther",message="form.validation.ibd-sitelab-csfCult-was-done-without-result-other"),
 *
 *                      @LocalAssert\Other(field="csfLatDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfLatResult",message="form.validation.ibd-sitelab-csfLat-was-done-without-result"),
 *                      @LocalAssert\Other(field="csfLatResult",value="\NS\SentinelBundle\Form\IBD\Types\LatResult::OTHER",otherField="csfLatOther",message="form.validation.ibd-sitelab-csfLat-was-done-without-result-other"),
 * 
 *                      @LocalAssert\Other(field="csfPcrDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfPcrResult",message="form.validation.ibd-sitelab-csfPcr-was-done-without-result"),
 *                      @LocalAssert\Other(field="csfPcrResult",value="\NS\SentinelBundle\Form\IBD\Types\PCRResult::OTHER",otherField="csfPcrOther",message="form.validation.ibd-sitelab-csfPcr-was-done-without-result-other"),
 *
 *                      @LocalAssert\Other(field="bloodCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="bloodCultResult",message="form.validation.ibd-sitelab-bloodCult-was-done-without-result"),
 *                      @LocalAssert\Other(field="bloodCultResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="bloodCultOther",message="form.validation.ibd-sitelab-bloodCult-was-done-without-result-other"),
 *
 *                      @LocalAssert\Other(field="otherCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherCultResult",message="form.validation.ibd-sitelab-otherCult-was-done-without-result"),
 *                      @LocalAssert\Other(field="otherCultResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="otherCultOther",message="form.validation.ibd-sitelab-otherCult-was-done-without-result-other"),
 *
 *                      @LocalAssert\Other(field="otherTestDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherTestResult",message="form.validation.ibd-sitelab-otherTest-was-done-without-result"),
 *                      @LocalAssert\Other(field="otherTestResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="otherTestOther",message="form.validation.ibd-sitelab-otherTest-was-done-without-result-other"),
 *
 *                      @LocalAssert\Other(field="csfBinaxDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfBinaxResult",message="form.validation.ibd-sitelab-csfBinax-was-done-without-result"),
 *                      } )
 */
class SiteLab
{
    /**
     * @var IBD
     *
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\IBD",inversedBy="siteLab",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,unique=true,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    //Case-based Laboratory Data

    /**
     * @var string $csf_id
     * @ORM\Column(name="csf_id",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csf_id;

    /**
     * @var \DateTime $csfLabTime
     * @ORM\Column(name="csf_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Groups({"api"})
     */
    private $csf_lab_date;

    /**
     * @var \DateTime $csfLabTime
     * @ORM\Column(name="csf_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Groups({"api"})
     */
    private $csf_lab_time;

    /**
     * @var integer $csfWcc
     * @ORM\Column(name="csf_wcc", type="integer", nullable=true)
     *
     * @Assert\Range(min=0,max=9999,minMessage="You cannot have a negative white blood cell count",maxMessage="Invalid value")
     * @Groups({"api"})
     */
    private $csf_wcc;

    /**
     * @var integer $csfGlucose
     * @ORM\Column(name="csf_glucose", type="integer", nullable=true)
     *
     * @Assert\Type(type="numeric", message="Invalid value. Must be a number")
     * @Assert\GreaterThanOrEqual(value=0, message="Invalid value - value must be greater than 0")
     * @Groups({"api"})
     */
    private $csf_glucose;

    /**
     * @var integer $csfProtein
     * @ORM\Column(name="csf_protein", type="integer",nullable=true)
     *
     * @Assert\Type(type="numeric", message="Invalid value. Must be a number")
     * @Assert\GreaterThanOrEqual(value=0, message="Invalid value - value must be greater than 0")
     * @Groups({"api"})
     */
    private $csf_protein;

    /**
     * @var TripleChoice $csfCultDone
     * @ORM\Column(name="csf_cult_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csf_cult_done;

    /**
     * @var TripleChoice $csfGramDone
     * @ORM\Column(name="csf_gram_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csf_gram_done;

    /**
     * @var TripleChoice $csfBinaxDone
     * @ORM\Column(name="csf_binax_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csf_binax_done;

    /**
     * @var TripleChoice $csfLatDone
     * @ORM\Column(name="csf_lat_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csf_lat_done;

    /**
     * @var TripleChoice $csfPcrDone
     * @ORM\Column(name="csf_pcr_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csf_pcr_done;

    /**
     * @var CultureResult $csfCultResult
     * @ORM\Column(name="csf_cult_result",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $csf_cult_result;

    /**
     * @var string $csfCultOther
     * @ORM\Column(name="csf_cult_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csf_cult_other;

    /**
     * TODO WHERE DOES THIS COME FROM??
     * @var string $csfCultContaminant
     * @ORM\Column(name="csf_cult_contaminant",type="string",nullable=true)
     */
    private $csf_cult_contaminant;

    /**
     * @var GramStain
     * @ORM\Column(name="csf_gram_stain",type="GramStain",nullable=true)
     * @Groups({"api"})
     */
    private $csf_gram_stain;

    /**
     * @var GramStainResult $csfGramResult
     * @ORM\Column(name="csf_gram_result",type="GramStainResult",nullable=true)
     * @Groups({"api"})
     */
    private $csf_gram_result;

    /**
     * @var string $csfGramOther
     * @ORM\Column(name="csf_gram_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csf_gram_other;

    /**
     * @var BinaxResult
     * @ORM\Column(name="csf_binax_result",type="BinaxResult",nullable=true)
     * @Groups({"api"})
     */
    private $csf_binax_result;

    /**
     * @var LatResult
     * @ORM\Column(name="csf_lat_result",type="LatResult",nullable=true)
     * @Groups({"api"})
     */
    private $csf_lat_result;

    /**
     * @var string
     * @ORM\Column(name="csf_lat_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csf_lat_other;

    /**
     * @var PCRResult
     * @ORM\Column(name="csf_pcr_result",type="PCRResult",nullable=true)
     * @Groups({"api"})
     */
    private $csf_pcr_result;

    /**
     * @var string $csfPcrOther
     * @ORM\Column(name="csf_pcr_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $csf_pcr_other;

    /**
     * @var TripleChoice $csfStore
     * @ORM\Column(name="csf_store",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $csf_store;

    /**
     * @var TripleChoice $csfStore
     * @ORM\Column(name="isol_store",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $isol_store;

//==================
    //PNEUMONIA / SEPSIS (In addition to above)
    /**
     * @var string $bloodId
     * @ORM\Column(name="blood_id",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $blood_id;

    /**
     * @var \DateTime $bloodLabTime
     * @ORM\Column(name="blood_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Groups({"api"})
     */
    private $blood_lab_date;

    /**
     * @var \DateTime $bloodLabTime
     * @ORM\Column(name="blood_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Groups({"api"})
     */
    private $blood_lab_time;

    /**
     * @var TripleChoice $bloodCultDone
     * @ORM\Column(name="blood_cult_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $blood_cult_done;

    /**
     * @var TripleChoice $bloodGramDone
     * @ORM\Column(name="blood_gram_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $blood_gram_done;

    /**
     * @var TripleChoice $bloodPcrDone
     * @ORM\Column(name="blood_pcr_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $blood_pcr_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="blood_cult_result",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $blood_cult_result;

    /**
     * @var string
     * @ORM\Column(name="blood_cult_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $blood_cult_other;

    /**
     * @var GramStain
     * @ORM\Column(name="blood_gram_stain",type="GramStain",nullable=true)
     * @Groups({"api"})
     */
    private $blood_gram_stain;

    /**
     * @var GramStainResult $bloodGramResult
     * @ORM\Column(name="blood_gram_result",type="GramStainResult",nullable=true)
     * @Groups({"api"})
     */
    private $blood_gram_result;

    /**
     * @var string $bloodGramOther
     * @ORM\Column(name="blood_gram_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $blood_gram_other;

    /**
     * @var PCRResult
     * @ORM\Column(name="blood_pcr_result",type="PCRResult",nullable=true)
     * @Groups({"api"})
     */
    private $blood_pcr_result;

    /**
     * @var string $bloodPcrOther
     * @ORM\Column(name="blood_pcr_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $blood_pcr_other;

    /**
     * @var string $other_id
     * @ORM\Column(name="other_id",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $other_id;

    /**
     * @var \DateTime $otherLabTime
     * @ORM\Column(name="other_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Groups({"api"})
     */
    private $other_lab_date;

    /**
     * @var \DateTime $otherLabTime
     * @ORM\Column(name="other_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Groups({"api"})
     */
    private $other_lab_time;

    /**
     * @var TripleChoice $otherCultDone
     * @ORM\Column(name="other_cult_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $other_cult_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="other_cult_result",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $other_cult_result;

    /**
     * @var string
     * @ORM\Column(name="other_cult_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $other_cult_other;

    /**
     * @var TripleChoice $otherTestDone
     * @ORM\Column(name="other_test_done",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $other_test_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="other_test_result",type="CultureResult",nullable=true)
     * @Groups({"api"})
     */
    private $other_test_result;

    /**
     * @var string $otherTestOther
     * @ORM\Column(name="other_test_other",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $other_test_other;
//==================================
    /**
     * @var boolean
     * @ORM\Column(name="rl_csf_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_csf_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_csf_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_csf_date;

    /**
     * @var boolean
     * @ORM\Column(name="rl_isol_csf_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_isol_csf_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_isol_csf_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_isol_csf_date;

    /**
     * @var boolean
     * @ORM\Column(name="rl_isol_blood_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_isol_blood_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_isol_blood_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_isol_blood_date;

    /**
     * @var boolean
     * @ORM\Column(name="rl_broth_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_broth_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_broth_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_broth_date;

    /**
     * @var boolean
     * @ORM\Column(name="rl_other_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_other_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_other_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $rl_other_date;

//=================================
// NL

    /**
     * @var boolean
     * @ORM\Column(name="nl_csf_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_csf_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="nl_csf_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_csf_date;

    /**
     * @var boolean
     * @ORM\Column(name="nl_isol_csf_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_isol_csf_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="nl_isol_csf_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_isol_csf_date;

    /**
     * @var boolean
     * @ORM\Column(name="nl_isol_blood_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_isol_blood_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="nl_isol_blood_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_isol_blood_date;

    /**
     * @var boolean
     * @ORM\Column(name="nl_broth_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_broth_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="nl_broth_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_broth_date;

    /**
     * @var boolean
     * @ORM\Column(name="nl_other_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_other_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="nl_other_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     */
    private $nl_other_date;

//==================================
    /**
     * @var \DateTime $updatedAt
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

//=================================
// PAHO
    /**
     * @var TripleChoice
     * @ORM\Column(name="pleural_fluid_culture_done",type="TripleChoice",nullable=true)
     */
    private $pleural_fluid_culture_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="pleural_fluid_culture_result",type="CultureResult",nullable=true)
     */
    private $pleural_fluid_culture_result;

    /**
     * @var string
     * @ORM\Column(name="pleural_fluid_culture_other",type="string",nullable=true)
     */
    private $pleural_fluid_culture_other;

    /**
     * @var TripleChoice
     * @ORM\Column(name="pleural_fluid_gram_done",type="TripleChoice",nullable=true)
     */
    private $pleural_fluid_gram_done;

    /**
     * @var GramStain
     * @ORM\Column(name="pleural_fluid_gram_result",type="GramStain",nullable=true)
     */
    private $pleural_fluid_gram_result;

    /**
     * @var GramStainResult
     * @ORM\Column(name="pleural_fluid_gram_result_organism",type="GramStainResult",nullable=true)
     */
    private $pleural_fluid_gram_result_organism;

    /**
     * @var TripleChoice
     * @ORM\Column(name="pleural_fluid_pcr_done",type="TripleChoice",nullable=true)
     */
    private $pleural_fluid_pcr_done;

    /**
     * @var PCRResult
     * @ORM\Column(name="pleural_fluid_pcr_result",type="PCRResult",nullable=true)
     */
    private $pleural_fluid_pcr_result;

    /**
     * @var string
     * @ORM\Column(name="pleural_fluid_pcr_other",type="string",nullable=true)
     */
    private $pleural_fluid_pcr_other;

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
     * @return IBD
     */
    public function getCaseFile()
    {
        return $this->caseFile;
    }

    /**
     * @param IBD $caseFile
     */
    public function setCaseFile(IBD $caseFile)
    {
        $this->caseFile = $caseFile;
    }

    /**
     * @return \DateTime
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
     * @return \DateTime
     */
    public function getCsfLabDate()
    {
        return $this->csf_lab_date;
    }

    /**
     * @return \DateTime
     */
    public function getCsfLabTime()
    {
        return $this->csf_lab_time;
    }

    /**
     * @return integer
     */
    public function getCsfWcc()
    {
        return $this->csf_wcc;
    }

    /**
     * @return integer
     */
    public function getCsfGlucose()
    {
        return $this->csf_glucose;
    }

    /**
     * @return integer
     */
    public function getCsfProtein()
    {
        return $this->csf_protein;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfCultDone()
    {
        return $this->csf_cult_done;
    }

    /**
     *
     * @return string
     */
    public function getCsfCultContaminant()
    {
        return $this->csf_cult_contaminant;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfGramDone()
    {
        return $this->csf_gram_done;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfBinaxDone()
    {
        return $this->csf_binax_done;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfLatDone()
    {
        return $this->csf_lat_done;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfPcrDone()
    {
        return $this->csf_pcr_done;
    }

    /**
     * @return CultureResult
     */
    public function getCsfCultResult()
    {
        return $this->csf_cult_result;
    }

    /**
     * @return string
     */
    public function getCsfCultOther()
    {
        return $this->csf_cult_other;
    }

    /**
     * @return GramStainResult
     */
    public function getCsfGramResult()
    {
        return $this->csf_gram_result;
    }

    /**
     * @return GramStain
     */
    public function getCsfGramStain()
    {
        return $this->csf_gram_stain;
    }

    /**
     * @return string
     */
    public function getCsfGramOther()
    {
        return $this->csf_gram_other;
    }

    /**
     * @return BinaxResult
     */
    public function getCsfBinaxResult()
    {
        return $this->csf_binax_result;
    }

    /**
     * @return LatResult
     */
    public function getCsfLatResult()
    {
        return $this->csf_lat_result;
    }

    /**
     * @return string
     */
    public function getCsfLatOther()
    {
        return $this->csf_lat_other;
    }

    /**
     * @return PCRResult
     */
    public function getCsfPcrResult()
    {
        return $this->csf_pcr_result;
    }

    /**
     * @return string
     */
    public function getCsfPcrOther()
    {
        return $this->csf_pcr_other;
    }

    /**
     * @return TripleChoice
     */
    public function getCsfStore()
    {
        return $this->csf_store;
    }

    /**
     * @return TripleChoice
     */
    public function getIsolStore()
    {
        return $this->isol_store;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodCultDone()
    {
        return $this->blood_cult_done;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodGramDone()
    {
        return $this->blood_gram_done;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodPcrDone()
    {
        return $this->blood_pcr_done;
    }

    /**
     * @return TripleChoice
     */
    public function getOtherCultDone()
    {
        return $this->other_cult_done;
    }

    /**
     * @return CultureResult
     */
    public function getBloodCultResult()
    {
        return $this->blood_cult_result;
    }

    /**
     * @return string
     */
    public function getBloodCultOther()
    {
        return $this->blood_cult_other;
    }

    /**
     * @return GramStainResult
     */
    public function getBloodGramResult()
    {
        return $this->blood_gram_result;
    }

    /**
     * @return GramStain
     */
    public function getBloodGramStain()
    {
        return $this->blood_gram_stain;
    }

    /**
     * @return string
     */
    public function getBloodGramOther()
    {
        return $this->blood_gram_other;
    }

    /**
     * @return PCRResult
     */
    public function getBloodPcrResult()
    {
        return $this->blood_pcr_result;
    }

    /**
     * @return string
     */
    public function getBloodPcrOther()
    {
        return $this->blood_pcr_other;
    }

    /**
     * @return CultureResult
     */
    public function getOtherCultResult()
    {
        return $this->other_cult_result;
    }

    /**
     * @return string
     */
    public function getOtherCultOther()
    {
        return $this->other_cult_other;
    }

    /**
     * @return string
     */
    public function getCsfId()
    {
        return $this->csf_id;
    }

    /**
     * @return string
     */
    public function getBloodId()
    {
        return $this->blood_id;
    }

    /**
     * @return TripleChoice
     */
    public function getOtherTestDone()
    {
        return $this->other_test_done;
    }

    /**
     * @param TripleChoice $otherTestDone
     */
    public function setOtherTestDone(TripleChoice $otherTestDone)
    {
        $this->other_test_done = $otherTestDone;
    }

    /**
     * @return CultureResult
     */
    public function getOtherTestResult()
    {
        return $this->other_test_result;
    }

    /**
     * @param CultureResult $otherTestResult
     * @return SiteLab
     */
    public function setOtherTestResult($otherTestResult)
    {
        $this->other_test_result = $otherTestResult;
        return $this;
    }

    /**
     * @return string
     */
    public function getOtherTestOther()
    {
        return $this->other_test_other;
    }

    /**
     * @param string $otherTestOther
     * @return SiteLab
     */
    public function setOtherTestOther($otherTestOther)
    {
        $this->other_test_other = $otherTestOther;
        return $this;
    }

    /**
     * @param $csfId
     */
    public function setCsfId($csfId)
    {
        $this->csf_id = $csfId;
    }

    /**
     * @param $bloodId
     */
    public function setBloodId($bloodId)
    {
        $this->blood_id = $bloodId;
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
     * @param \DateTime $csfLabDate
     * @return SiteLab
     */
    public function setCsfLabDate($csfLabDate)
    {
        $this->csf_lab_date = $csfLabDate;
        return $this;
    }

    /**
     * @param \DateTime $csfLabTime
     * @return SiteLab
     */
    public function setCsfLabTime($csfLabTime)
    {
        $this->csf_lab_time= $csfLabTime;
        return $this;
    }

    /**
     * @param $csfWcc
     * @return $this
     */
    public function setCsfWcc($csfWcc)
    {
        $this->csf_wcc = $csfWcc;
        return $this;
    }

    /**
     * @param $csfGlucose
     * @return $this
     */
    public function setCsfGlucose($csfGlucose)
    {
        $this->csf_glucose = $csfGlucose;
        return $this;
    }

    /**
     * @param $csfProtein
     * @return $this
     */
    public function setCsfProtein($csfProtein)
    {
        $this->csf_protein = $csfProtein;
        return $this;
    }

    /**
     *
     * @param string $csfCultContaminant
     * @return \NS\SentinelBundle\Entity\IBD\SiteLab
     */
    public function setCsfCultContaminant($csfCultContaminant)
    {
        $this->csf_cult_contaminant = $csfCultContaminant;
        return $this;
    }

    /**
     * @param TripleChoice $csfCultDone
     * @return $this
     */
    public function setCsfCultDone(TripleChoice $csfCultDone)
    {
        $this->csf_cult_done = $csfCultDone;
        return $this;
    }

    /**
     * @param TripleChoice $csfGramDone
     * @return $this
     */
    public function setCsfGramDone(TripleChoice $csfGramDone)
    {
        $this->csf_gram_done = $csfGramDone;
        return $this;
    }

    /**
     * @param TripleChoice $csfBinaxDone
     * @return $this
     */
    public function setCsfBinaxDone(TripleChoice $csfBinaxDone)
    {
        $this->csf_binax_done = $csfBinaxDone;
        return $this;
    }

    /**
     * @param TripleChoice $csfLatDone
     * @return $this
     */
    public function setCsfLatDone(TripleChoice $csfLatDone)
    {
        $this->csf_lat_done = $csfLatDone;
        return $this;
    }

    /**
     * @param TripleChoice $csfPcrDone
     * @return $this
     */
    public function setCsfPcrDone(TripleChoice $csfPcrDone)
    {
        $this->csf_pcr_done = $csfPcrDone;
        return $this;
    }

    /**
     * @param CultureResult $csfCultResult
     * @return $this
     */
    public function setCsfCultResult(CultureResult $csfCultResult)
    {
        $this->csf_cult_result = $csfCultResult;
        return $this;
    }

    /**
     * @param $csfCultOther
     * @return $this
     */
    public function setCsfCultOther($csfCultOther)
    {
        $this->csf_cult_other = $csfCultOther;
        return $this;
    }

    /**
     * @param GramStainResult $csfGramResult
     * @return $this
     */
    public function setCsfGramResult(GramStainResult $csfGramResult)
    {
        $this->csf_gram_result = $csfGramResult;
        return $this;
    }

    /**
     * @param GramStain $csfGramStain
     * @return $this
     */
    public function setCsfGramStain(GramStain $csfGramStain)
    {
        $this->csf_gram_stain = $csfGramStain;
        return $this;
    }

    /**
     * @param $csfGramOther
     * @return $this
     */
    public function setCsfGramOther($csfGramOther)
    {
        $this->csf_gram_other = $csfGramOther;
        return $this;
    }

    /**
     * @param BinaxResult $csfBinaxResult
     * @return $this
     */
    public function setCsfBinaxResult(BinaxResult $csfBinaxResult)
    {
        $this->csf_binax_result = $csfBinaxResult;
        return $this;
    }

    /**
     * @param LatResult $csfLatResult
     * @return $this
     */
    public function setCsfLatResult(LatResult $csfLatResult)
    {
        $this->csf_lat_result = $csfLatResult;
        return $this;
    }

    /**
     * @param $csfLatOther
     * @return $this
     */
    public function setCsfLatOther($csfLatOther)
    {
        $this->csf_lat_other = $csfLatOther;
        return $this;
    }

    /**
     * @param PCRResult $csfPcrResult
     * @return $this
     */
    public function setCsfPcrResult(PCRResult $csfPcrResult)
    {
        $this->csf_pcr_result= $csfPcrResult;
        return $this;
    }

    /**
     * @param $csfPcrOther
     * @return $this
     */
    public function setCsfPcrOther($csfPcrOther)
    {
        $this->csf_pcr_other = $csfPcrOther;
        return $this;
    }

    /**
     * @param TripleChoice $csfStore
     * @return $this
     */
    public function setCsfStore(TripleChoice $csfStore)
    {
        $this->csf_store = $csfStore;
        return $this;
    }

    /**
     * @param TripleChoice $isolStore
     * @return $this
     */
    public function setIsolStore(TripleChoice $isolStore)
    {
        $this->isol_store = $isolStore;
        return $this;
    }

    /**
     * @param TripleChoice $bloodCultDone
     * @return $this
     */
    public function setBloodCultDone(TripleChoice $bloodCultDone)
    {
        $this->blood_cult_done = $bloodCultDone;
        return $this;
    }

    /**
     * @param TripleChoice $bloodGramDone
     * @return $this
     */
    public function setBloodGramDone(TripleChoice $bloodGramDone)
    {
        $this->blood_gram_done = $bloodGramDone;
        return $this;
    }

    /**
     * @param TripleChoice $bloodPcrDone
     * @return $this
     */
    public function setBloodPcrDone(TripleChoice $bloodPcrDone)
    {
        $this->blood_pcr_done = $bloodPcrDone;
        return $this;
    }

    /**
     * @param TripleChoice $otherCultDone
     * @return $this
     */
    public function setOtherCultDone(TripleChoice $otherCultDone)
    {
        $this->other_cult_done = $otherCultDone;
        return $this;
    }

    /**
     * @param CultureResult $bloodCultResult
     * @return $this
     */
    public function setBloodCultResult(CultureResult $bloodCultResult)
    {
        $this->blood_cult_result = $bloodCultResult;
        return $this;
    }

    /**
     * @param $bloodCultOther
     * @return $this
     */
    public function setBloodCultOther($bloodCultOther)
    {
        $this->blood_cult_other = $bloodCultOther;
        return $this;
    }

    /**
     * @param GramStainResult $bloodGramResult
     * @return $this
     */
    public function setBloodGramResult(GramStainResult $bloodGramResult)
    {
        $this->blood_gram_result = $bloodGramResult;
        return $this;
    }

    /**
     * @param GramStain $bloodGramStain
     * @return $this
     */
    public function setBloodGramStain(GramStain $bloodGramStain)
    {
        $this->blood_gram_stain = $bloodGramStain;
        return $this;
    }

    /**
     * @param $bloodGramOther
     * @return $this
     */
    public function setBloodGramOther($bloodGramOther)
    {
        $this->blood_gram_other = $bloodGramOther;
        return $this;
    }

    /**
     * @param PCRResult $bloodPcrResult
     * @return $this
     */
    public function setBloodPcrResult(PCRResult $bloodPcrResult)
    {
        $this->blood_pcr_result = $bloodPcrResult;
        return $this;
    }

    /**
     * @param $bloodPcrOther
     * @return $this
     */
    public function setBloodPcrOther($bloodPcrOther)
    {
        $this->blood_pcr_other = $bloodPcrOther;
        return $this;
    }

    /**
     * @param CultureResult $otherCultResult
     * @return $this
     */
    public function setOtherCultResult(CultureResult $otherCultResult)
    {
        $this->other_cult_result = $otherCultResult;
        return $this;
    }

    /**
     * @param $otherCultOther
     * @return $this
     */
    public function setOtherCultOther($otherCultOther)
    {
        $this->other_cult_other = $otherCultOther;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getRlCsfSent()
    {
        return $this->rl_csf_sent;
    }

    /**
     * @param boolean $rl_csf_sent
     */
    public function setRlCsfSent($rl_csf_sent = null)
    {
        $this->rl_csf_sent = $rl_csf_sent;
    }

    /**
     * @return boolean
     */
    public function getRlIsolCsfSent()
    {
        return $this->rl_isol_csf_sent;
    }

    /**
     * @param boolean $rl_isol_csf_sent
     */
    public function setRlIsolCsfSent($rl_isol_csf_sent = null)
    {
        $this->rl_isol_csf_sent = $rl_isol_csf_sent;
    }

    /**
     * @return boolean
     */
    public function getRlIsolBloodSent()
    {
        return $this->rl_isol_blood_sent;
    }

    /**
     * @param boolean $rl_isol_blood_sent
     */
    public function setRlIsolBloodSent($rl_isol_blood_sent = null)
    {
        $this->rl_isol_blood_sent = $rl_isol_blood_sent;
    }

    /**
     * @return boolean
     */
    public function getRlBrothSent()
    {
        return $this->rl_broth_sent;
    }

    /**
     * @param boolean $rl_broth_sent
     */
    public function setRlBrothSent($rl_broth_sent = null)
    {
        $this->rl_broth_sent = $rl_broth_sent;
    }

    /**
     * @return boolean
     */
    public function getRlOtherSent()
    {
        return $this->rl_other_sent;
    }

    /**
     * @param boolean $rl_other_sent
     */
    public function setRlOtherSent($rl_other_sent = null)
    {
        $this->rl_other_sent = $rl_other_sent;
    }

    /**
     * @return \DateTime
     */
    public function getRlOtherDate()
    {
        return $this->rl_other_date;
    }

    /**
     * @param \DateTime $rl_other_date
     */
    public function setRlOtherDate(\DateTime $rl_other_date = null)
    {
        $this->rl_other_date = $rl_other_date;
    }

    /**
     * @return boolean
     */
    public function getNlCsfSent()
    {
        return $this->nl_csf_sent;
    }

    /**
     * @param boolean $nl_csf_sent
     */
    public function setNlCsfSent($nl_csf_sent = null)
    {
        $this->nl_csf_sent = $nl_csf_sent;
    }

    /**
     * @return \DateTime
     */
    public function getNlCsfDate()
    {
        return $this->nl_csf_date;
    }

    /**
     * @param \DateTime $nl_csf_date
     */
    public function setNlCsfDate(\DateTime $nl_csf_date = null)
    {
        $this->nl_csf_date = $nl_csf_date;
    }

    /**
     * @return boolean
     */
    public function getNlIsolCsfSent()
    {
        return $this->nl_isol_csf_sent;
    }

    /**
     * @param boolean $nl_isol_csf_sent
     */
    public function setNlIsolCsfSent($nl_isol_csf_sent = null)
    {
        $this->nl_isol_csf_sent = $nl_isol_csf_sent;
    }

    /**
     * @return \DateTime
     */
    public function getNlIsolCsfDate()
    {
        return $this->nl_isol_csf_date;
    }

    /**
     * @param \DateTime $nl_isol_csf_date
     */
    public function setNlIsolCsfDate(\DateTime $nl_isol_csf_date = null)
    {
        $this->nl_isol_csf_date = $nl_isol_csf_date;
    }

    /**
     * @return boolean
     */
    public function getNlIsolBloodSent()
    {
        return $this->nl_isol_blood_sent;
    }

    /**
     * @param boolean $nl_isol_blood_sent
     */
    public function setNlIsolBloodSent($nl_isol_blood_sent = null)
    {
        $this->nl_isol_blood_sent = $nl_isol_blood_sent;
    }

    /**
     * @return \DateTime
     */
    public function getNlIsolBloodDate()
    {
        return $this->nl_isol_blood_date;
    }

    /**
     * @param \DateTime $nl_isol_blood_date
     */
    public function setNlIsolBloodDate(\DateTime $nl_isol_blood_date = null)
    {
        $this->nl_isol_blood_date = $nl_isol_blood_date;
    }

    /**
     * @return boolean
     */
    public function getNlBrothSent()
    {
        return $this->nl_broth_sent;
    }

    /**
     * @param boolean $nl_broth_sent
     */
    public function setNlBrothSent($nl_broth_sent = null)
    {
        $this->nl_broth_sent = $nl_broth_sent;
    }

    /**
     * @return \DateTime
     */
    public function getNlBrothDate()
    {
        return $this->nl_broth_date;
    }

    /**
     * @param \DateTime $nl_broth_date
     */
    public function setNlBrothDate(\DateTime $nl_broth_date = null)
    {
        $this->nl_broth_date = $nl_broth_date;
    }

    /**
     * @return boolean
     */
    public function getNlOtherSent()
    {
        return $this->nl_other_sent;
    }

    /**
     * @param boolean $nl_other_sent
     */
    public function setNlOtherSent($nl_other_sent = null)
    {
        $this->nl_other_sent = $nl_other_sent;
    }

    /**
     * @return \DateTime
     */
    public function getNlOtherDate()
    {
        return $this->nl_other_date;
    }

    /**
     * @param \DateTime $nl_other_date
     */
    public function setNlOtherDate(\DateTime $nl_other_date = null)
    {
        $this->nl_other_date = $nl_other_date;
    }

    /**
     * @return \DateTime
     */
    public function getRlCsfDate()
    {
        return $this->rl_csf_date;
    }

    /**
     * @param \DateTime $rl_csf_date
     */
    public function setRlCsfDate(\DateTime $rl_csf_date = null)
    {
        $this->rl_csf_date = $rl_csf_date;
    }

    /**
     * @return \DateTime
     */
    public function getRlIsolCsfDate()
    {
        return $this->rl_isol_csf_date;
    }

    /**
     * @param \DateTime $rl_isol_csf_date
     */
    public function setRlIsolCsfDate(\DateTime $rl_isol_csf_date = null)
    {
        $this->rl_isol_csf_date = $rl_isol_csf_date;
    }

    /**
     * @return \DateTime
     */
    public function getRlIsolBloodDate()
    {
        return $this->rl_isol_blood_date;
    }

    /**
     * @param \DateTime $rl_isol_blood_date
     */
    public function setRlIsolBloodDate(\DateTime $rl_isol_blood_date = null)
    {
        $this->rl_isol_blood_date = $rl_isol_blood_date;
    }

    /**
     * @return \DateTime
     */
    public function getRlBrothDate()
    {
        return $this->rl_broth_date;
    }

    /**
     * @param \DateTime $rl_broth_date
     */
    public function setRlBrothDate(\DateTime $rl_broth_date = null)
    {
        $this->rl_broth_date = $rl_broth_date;
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
        foreach ($this->getMinimumRequiredFields() as $field) {
            if ($this->$field === null || empty($this->$field) || ($this->$field instanceof ArrayChoice && $this->$field->equal(-1))) {
                return $field;
            }
        }

        //Additional Tests as needed (result=other && other fields etc)

        return null;
    }

    /**
     * @return array
     */
    public function getMinimumRequiredFields()
    {
        return [
            'csfLabDate',
            'csfLabTime',
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
        ];
    }

    /**
     * @return TripleChoice
     */
    public function getPleuralFluidCultureDone()
    {
        return $this->pleural_fluid_culture_done;
    }

    /**
     * @param TripleChoice $pleural_fluid_culture_done
     */
    public function setPleuralFluidCultureDone($pleural_fluid_culture_done)
    {
        $this->pleural_fluid_culture_done = $pleural_fluid_culture_done;
    }

    /**
     * @return CultureResult
     */
    public function getPleuralFluidCultureResult()
    {
        return $this->pleural_fluid_culture_result;
    }

    /**
     * @param CultureResult $pleural_fluid_culture_result
     */
    public function setPleuralFluidCultureResult($pleural_fluid_culture_result)
    {
        $this->pleural_fluid_culture_result = $pleural_fluid_culture_result;
    }

    /**
     * @return string
     */
    public function getPleuralFluidCultureOther()
    {
        return $this->pleural_fluid_culture_other;
    }

    /**
     * @param string $pleural_fluid_culture_other
     */
    public function setPleuralFluidCultureOther($pleural_fluid_culture_other)
    {
        $this->pleural_fluid_culture_other = $pleural_fluid_culture_other;
    }

    /**
     * @return TripleChoice
     */
    public function getPleuralFluidGramDone()
    {
        return $this->pleural_fluid_gram_done;
    }

    /**
     * @param TripleChoice $pleural_fluid_gram_done
     */
    public function setPleuralFluidGramDone($pleural_fluid_gram_done)
    {
        $this->pleural_fluid_gram_done = $pleural_fluid_gram_done;
    }

    /**
     * @return GramStain
     */
    public function getPleuralFluidGramResult()
    {
        return $this->pleural_fluid_gram_result;
    }

    /**
     * @param GramStain $pleural_fluid_gram_result
     */
    public function setPleuralFluidGramResult($pleural_fluid_gram_result)
    {
        $this->pleural_fluid_gram_result = $pleural_fluid_gram_result;
    }

    /**
     * @return GramStainResult
     */
    public function getPleuralFluidGramResultOrganism()
    {
        return $this->pleural_fluid_gram_result_organism;
    }

    /**
     * @param GramStainResult $pleural_fluid_gram_result_organism
     */
    public function setPleuralFluidGramResultOrganism($pleural_fluid_gram_result_organism)
    {
        $this->pleural_fluid_gram_result_organism = $pleural_fluid_gram_result_organism;
    }

    /**
     * @return TripleChoice
     */
    public function getPleuralFluidPcrDone()
    {
        return $this->pleural_fluid_pcr_done;
    }

    /**
     * @param TripleChoice $pleural_fluid_pcr_done
     */
    public function setPleuralFluidPcrDone($pleural_fluid_pcr_done)
    {
        $this->pleural_fluid_pcr_done = $pleural_fluid_pcr_done;
    }

    /**
     * @return PCRResult
     */
    public function getPleuralFluidPcrResult()
    {
        return $this->pleural_fluid_pcr_result;
    }

    /**
     * @param PCRResult $pleural_fluid_pcr_result
     */
    public function setPleuralFluidPcrResult($pleural_fluid_pcr_result)
    {
        $this->pleural_fluid_pcr_result = $pleural_fluid_pcr_result;
    }

    /**
     * @return string
     */
    public function getPleuralFluidPcrOther()
    {
        return $this->pleural_fluid_pcr_other;
    }

    /**
     * @param string $pleural_fluid_pcr_other
     */
    public function setPleuralFluidPcrOther($pleural_fluid_pcr_other)
    {
        $this->pleural_fluid_pcr_other = $pleural_fluid_pcr_other;
    }

    /**
     * @return \DateTime
     */
    public function getBloodLabDate()
    {
        return $this->blood_lab_date;
    }

    /**
     * @param \DateTime $blood_lab_date
     */
    public function setBloodLabDate($blood_lab_date)
    {
        $this->blood_lab_date = $blood_lab_date;
    }

    /**
     * @return \DateTime
     */
    public function getBloodLabTime()
    {
        return $this->blood_lab_time;
    }

    /**
     * @param \DateTime $blood_lab_time
     */
    public function setBloodLabTime($blood_lab_time)
    {
        $this->blood_lab_time = $blood_lab_time;
    }

    /**
     * @return string
     */
    public function getOtherId()
    {
        return $this->other_id;
    }

    /**
     * @param string $other_id
     */
    public function setOtherId($other_id)
    {
        $this->other_id = $other_id;
    }

    /**
     * @return \DateTime
     */
    public function getOtherLabDate()
    {
        return $this->other_lab_date;
    }

    /**
     * @param \DateTime $other_lab_date
     */
    public function setOtherLabDate($other_lab_date)
    {
        $this->other_lab_date = $other_lab_date;
    }

    /**
     * @return \DateTime
     */
    public function getOtherLabTime()
    {
        return $this->other_lab_time;
    }

    /**
     * @param \DateTime $other_lab_time
     */
    public function setOtherLabTime($other_lab_time)
    {
        $this->other_lab_time = $other_lab_time;
    }

    /**
     * @return bool
     */
    public function getSentToReferenceLab()
    {
        return ($this->rl_csf_sent || $this->rl_isol_csf_sent || $this->rl_isol_blood_sent || $this->rl_broth_sent || $this->rl_other_sent);
    }

    /**
     * @return bool
     */
    public function getSentToNationalLab()
    {
        return ($this->nl_csf_sent || $this->nl_isol_csf_sent || $this->nl_isol_blood_sent || $this->nl_broth_sent || $this->nl_other_sent);
    }
}
