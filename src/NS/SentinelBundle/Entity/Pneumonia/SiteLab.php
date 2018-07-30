<?php

namespace NS\SentinelBundle\Entity\Pneumonia;

use Doctrine\ORM\Mapping as ORM;
use NS\SecurityBundle\Annotation as Security;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\BaseSiteLabInterface;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\UtilBundle\Form\Types\ArrayChoice;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Validators as LocalAssert;
use JMS\Serializer\Annotation as Serializer;

/**
 *
 * Description of SiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Pneumonia\SiteLabRepository")
 * @ORM\Table(name="pneu_site_labs")
 * @Security\Secured(conditions={
 *      @Security\SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @Security\SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @Security\SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @LocalAssert\AllOther( {
 *                      @LocalAssert\Other(field="bloodCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="bloodCultResult",message="form.validation.ibd-sitelab-bloodCult-was-done-without-result"),
 *                      @LocalAssert\Other(field="bloodCultResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="bloodCultOther",message="form.validation.ibd-sitelab-bloodCult-was-done-without-result-other"),
 *
 *                      @LocalAssert\Other(field="otherCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherCultResult",message="form.validation.ibd-sitelab-otherCult-was-done-without-result"),
 *                      @LocalAssert\Other(field="otherCultResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="otherCultOther",message="form.validation.ibd-sitelab-otherCult-was-done-without-result-other"),
 *
 *                      @LocalAssert\Other(field="otherTestDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherTestResult",message="form.validation.ibd-sitelab-otherTest-was-done-without-result"),
 *                      @LocalAssert\Other(field="otherTestResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="otherTestOther",message="form.validation.ibd-sitelab-otherTest-was-done-without-result-other"),
 *                      } )
 */
class SiteLab implements BaseSiteLabInterface
{
    /**
     * @var Pneumonia
     *
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Pneumonia\Pneumonia",inversedBy="siteLab",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,unique=true,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    //Case-based Laboratory Data

//==================
    //PNEUMONIA / SEPSIS (In addition to above)
    /**
     * @var string $bloodId
     * @ORM\Column(name="blood_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_id;

    /**
     * @var \DateTime $bloodLabTime
     * @ORM\Column(name="blood_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $blood_lab_date;

    /**
     * @var \DateTime $bloodLabTime
     * @ORM\Column(name="blood_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     */
    private $blood_lab_time;

    /**
     * @var TripleChoice $bloodCultDone
     * @ORM\Column(name="blood_cult_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_cult_done;

    /**
     * @var TripleChoice $bloodGramDone
     * @ORM\Column(name="blood_gram_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_gram_done;

    /**
     * @var TripleChoice $bloodPcrDone
     * @ORM\Column(name="blood_pcr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_pcr_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="blood_cult_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_cult_result;

    /**
     * @var string
     * @ORM\Column(name="blood_cult_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_cult_other;

    /**
     * @var GramStain
     * @ORM\Column(name="blood_gram_stain",type="GramStain",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_gram_stain;

    /**
     * @var GramStainResult $bloodGramResult
     * @ORM\Column(name="blood_gram_result",type="GramStainResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_gram_result;

    /**
     * @var string $bloodGramOther
     * @ORM\Column(name="blood_gram_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_gram_other;

    /**
     * @var PCRResult
     * @ORM\Column(name="blood_pcr_result",type="PCRResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_pcr_result;

    /**
     * @var string $bloodPcrOther
     * @ORM\Column(name="blood_pcr_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_pcr_other;

    //============
    /**
     * @var string
     * @ORM\Column(name="blood_second_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_id;

    /**
     * @var \DateTime $blood_secondLabTime
     * @ORM\Column(name="blood_second_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $blood_second_lab_date;

    /**
     * @var \DateTime $blood_secondLabTime
     * @ORM\Column(name="blood_second_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     */
    private $blood_second_lab_time;

    /**
     * @var TripleChoice $blood_secondCultDone
     * @ORM\Column(name="blood_second_cult_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_cult_done;

    /**
     * @var TripleChoice $blood_secondGramDone
     * @ORM\Column(name="blood_second_gram_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_gram_done;

    /**
     * @var TripleChoice $blood_secondPcrDone
     * @ORM\Column(name="blood_second_pcr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_pcr_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="blood_second_cult_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_cult_result;

    /**
     * @var string
     * @ORM\Column(name="blood_second_cult_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_cult_other;

    /**
     * @var GramStain
     * @ORM\Column(name="blood_second_gram_stain",type="GramStain",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_gram_stain;

    /**
     * @var GramStainResult $blood_secondGramResult
     * @ORM\Column(name="blood_second_gram_result",type="GramStainResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_gram_result;

    /**
     * @var string $blood_secondGramOther
     * @ORM\Column(name="blood_second_gram_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_gram_other;

    /**
     * @var PCRResult
     * @ORM\Column(name="blood_second_pcr_result",type="PCRResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_pcr_result;

    /**
     * @var string $blood_secondPcrOther
     * @ORM\Column(name="blood_second_pcr_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_pcr_other;

    //============
    /**
     * @var string $other_id
     * @ORM\Column(name="other_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_id;

    /**
     * @var \DateTime $otherLabTime
     * @ORM\Column(name="other_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $other_lab_date;

    /**
     * @var \DateTime $otherLabTime
     * @ORM\Column(name="other_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     */
    private $other_lab_time;

    /**
     * @var TripleChoice $otherCultDone
     * @ORM\Column(name="other_cult_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_cult_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="other_cult_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_cult_result;

    /**
     * @var string
     * @ORM\Column(name="other_cult_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_cult_other;

    /**
     * @var TripleChoice $otherTestDone
     * @ORM\Column(name="other_test_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_test_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="other_test_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_test_result;

    /**
     * @var string $otherTestOther
     * @ORM\Column(name="other_test_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_test_other;
//==================================
    /**
     * @var boolean
     * @ORM\Column(name="rl_isol_blood_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_isol_blood_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_isol_blood_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_isol_blood_date;

    /**
     * @var boolean
     * @ORM\Column(name="rl_broth_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_broth_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_broth_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_broth_date;

    /**
     * @var boolean
     * @ORM\Column(name="rl_other_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_other_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="rl_other_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_other_date;

//=================================
// NL
    /**
     * @var boolean
     * @ORM\Column(name="nl_isol_blood_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $nl_isol_blood_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="nl_isol_blood_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $nl_isol_blood_date;

    /**
     * @var boolean
     * @ORM\Column(name="nl_broth_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $nl_broth_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="nl_broth_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $nl_broth_date;

    /**
     * @var boolean
     * @ORM\Column(name="nl_other_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $nl_other_sent;

    /**
     * @var \DateTime
     * @ORM\Column(name="nl_other_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $nl_other_date;

//==================================
    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d H:i:s'>")
     */
    private $updatedAt;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     * @Serializer\Groups({"api","export"})
     */
    private $status;

//=================================
// PAHO
    /**
     * @var TripleChoice
     * @ORM\Column(name="pleural_fluid_culture_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_culture_done;

    /**
     * @var CultureResult
     * @ORM\Column(name="pleural_fluid_culture_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_culture_result;

    /**
     * @var string
     * @ORM\Column(name="pleural_fluid_culture_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_culture_other;

    /**
     * @var TripleChoice
     * @ORM\Column(name="pleural_fluid_gram_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_gram_done;

    /**
     * @var GramStain
     * @ORM\Column(name="pleural_fluid_gram_result",type="GramStain",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_gram_result;

    /**
     * @var GramStainResult
     * @ORM\Column(name="pleural_fluid_gram_result_organism",type="GramStainResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_gram_result_organism;

    /**
     * @var TripleChoice
     * @ORM\Column(name="pleural_fluid_pcr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_pcr_done;

    /**
     * @var PCRResult
     * @ORM\Column(name="pleural_fluid_pcr_result",type="PCRResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_pcr_result;

    /**
     * @var string
     * @ORM\Column(name="pleural_fluid_pcr_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_pcr_other;

    /**
     * @param Pneumonia $case
     */
    public function __construct(Pneumonia $case = null)
    {
        if ($case) {
            $this->caseFile = $case;
        }

        $this->updatedAt = new \DateTime();
        $this->status    = new CaseStatus(CaseStatus::OPEN);

        return $this;
    }

    /**
     * @return Pneumonia
     */
    public function getCaseFile()
    {
        return $this->caseFile;
    }

    /**
     * @param BaseCase|Pneumonia $caseFile
     * @return \NS\SentinelBundle\Entity\BaseSiteLabInterface|void
     */
    public function setCaseFile(BaseCase $caseFile)
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
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
     */
    public function setStatus(CaseStatus $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
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
        return ($this->caseFile instanceof Pneumonia);
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
     */
    public function setOtherTestResult($otherTestResult)
    {
        $this->other_test_result = $otherTestResult;
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
     */
    public function setOtherTestOther($otherTestOther)
    {
        $this->other_test_other = $otherTestOther;
    }

    /**
     * @param $bloodId
     */
    public function setBloodId($bloodId)
    {
        $this->blood_id = $bloodId;
    }

    /**
     * @param TripleChoice $isolStore
     */
    public function setIsolStore(TripleChoice $isolStore)
    {
        $this->isol_store = $isolStore;
    }

    /**
     * @param TripleChoice $bloodCultDone
     */
    public function setBloodCultDone(TripleChoice $bloodCultDone)
    {
        $this->blood_cult_done = $bloodCultDone;
    }

    /**
     * @param TripleChoice $bloodGramDone
     */
    public function setBloodGramDone(TripleChoice $bloodGramDone)
    {
        $this->blood_gram_done = $bloodGramDone;
    }

    /**
     * @param TripleChoice $bloodPcrDone
     */
    public function setBloodPcrDone(TripleChoice $bloodPcrDone)
    {
        $this->blood_pcr_done = $bloodPcrDone;
    }

    /**
     * @param TripleChoice $otherCultDone
     */
    public function setOtherCultDone(TripleChoice $otherCultDone)
    {
        $this->other_cult_done = $otherCultDone;
    }

    /**
     * @param CultureResult $bloodCultResult
     */
    public function setBloodCultResult(CultureResult $bloodCultResult)
    {
        $this->blood_cult_result = $bloodCultResult;
    }

    /**
     * @param $bloodCultOther
     */
    public function setBloodCultOther($bloodCultOther)
    {
        $this->blood_cult_other = $bloodCultOther;
    }

    /**
     * @param GramStainResult $bloodGramResult
     */
    public function setBloodGramResult(GramStainResult $bloodGramResult)
    {
        $this->blood_gram_result = $bloodGramResult;
    }

    /**
     * @param GramStain $bloodGramStain
     */
    public function setBloodGramStain(GramStain $bloodGramStain)
    {
        $this->blood_gram_stain = $bloodGramStain;
    }

    /**
     * @param $bloodGramOther
     */
    public function setBloodGramOther($bloodGramOther)
    {
        $this->blood_gram_other = $bloodGramOther;
    }

    /**
     * @param PCRResult $bloodPcrResult
     */
    public function setBloodPcrResult(PCRResult $bloodPcrResult)
    {
        $this->blood_pcr_result = $bloodPcrResult;
    }

    /**
     * @param $bloodPcrOther
     */
    public function setBloodPcrOther($bloodPcrOther)
    {
        $this->blood_pcr_other = $bloodPcrOther;
    }

    /**
     * @param CultureResult $otherCultResult
     */
    public function setOtherCultResult(CultureResult $otherCultResult)
    {
        $this->other_cult_result = $otherCultResult;
    }

    /**
     * @param $otherCultOther
     */
    public function setOtherCultOther($otherCultOther)
    {
        $this->other_cult_other = $otherCultOther;
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
    public function setPleuralFluidCultureDone(TripleChoice $pleural_fluid_culture_done)
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
    public function setPleuralFluidCultureResult(CultureResult $pleural_fluid_culture_result)
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
    public function setPleuralFluidGramDone(TripleChoice $pleural_fluid_gram_done)
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
    public function setPleuralFluidGramResult(GramStain $pleural_fluid_gram_result)
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
    public function setPleuralFluidGramResultOrganism(GramStainResult $pleural_fluid_gram_result_organism)
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
    public function setPleuralFluidPcrDone(TripleChoice $pleural_fluid_pcr_done)
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
    public function setPleuralFluidPcrResult(PCRResult $pleural_fluid_pcr_result)
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
    public function setBloodLabDate(\DateTime $blood_lab_date = null)
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
    public function setBloodLabTime(\DateTime $blood_lab_time = null)
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
    public function setOtherLabDate(\DateTime $other_lab_date = null)
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
    public function setOtherLabTime(\DateTime $other_lab_time = null)
    {
        $this->other_lab_time = $other_lab_time;
    }

    /**
     * @return bool
     */
    public function getSentToReferenceLab()
    {
        return ($this->rl_isol_blood_sent || $this->rl_broth_sent || $this->rl_other_sent);
    }

    /**
     * @return bool
     */
    public function getSentToNationalLab()
    {
        return ($this->nl_isol_blood_sent || $this->nl_broth_sent || $this->nl_other_sent);
    }

    // Second blood sample results
    /**
     * @return string
     */
    public function getBloodSecondId()
    {
        return $this->blood_second_id;
    }

    /**
     * @param string $blood_second_id
     */
    public function setBloodSecondId($blood_second_id)
    {
        $this->blood_second_id = $blood_second_id;
    }

    /**
     * @return \DateTime
     */
    public function getBloodSecondLabDate()
    {
        return $this->blood_second_lab_date;
    }

    /**
     * @param \DateTime $blood_second_lab_date
     */
    public function setBloodSecondLabDate(\DateTime $blood_second_lab_date = null)
    {
        $this->blood_second_lab_date = $blood_second_lab_date;
    }

    /**
     * @return \DateTime
     */
    public function getBloodSecondLabTime()
    {
        return $this->blood_second_lab_time;
    }

    /**
     * @param \DateTime $blood_second_lab_time
     */
    public function setBloodSecondLabTime(\DateTime $blood_second_lab_time = null)
    {
        $this->blood_second_lab_time = $blood_second_lab_time;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodSecondCultDone()
    {
        return $this->blood_second_cult_done;
    }

    /**
     * @param TripleChoice $blood_second_cult_done
     */
    public function setBloodSecondCultDone(TripleChoice $blood_second_cult_done)
    {
        $this->blood_second_cult_done = $blood_second_cult_done;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodSecondGramDone()
    {
        return $this->blood_second_gram_done;
    }

    /**
     * @param TripleChoice $blood_second_gram_done
     */
    public function setBloodSecondGramDone(TripleChoice $blood_second_gram_done)
    {
        $this->blood_second_gram_done = $blood_second_gram_done;
    }

    /**
     * @return TripleChoice
     */
    public function getBloodSecondPcrDone()
    {
        return $this->blood_second_pcr_done;
    }

    /**
     * @param TripleChoice $blood_second_pcr_done
     */
    public function setBloodSecondPcrDone(TripleChoice $blood_second_pcr_done)
    {
        $this->blood_second_pcr_done = $blood_second_pcr_done;
    }

    /**
     * @return CultureResult
     */
    public function getBloodSecondCultResult()
    {
        return $this->blood_second_cult_result;
    }

    /**
     * @param CultureResult $blood_second_cult_result
     */
    public function setBloodSecondCultResult(CultureResult $blood_second_cult_result)
    {
        $this->blood_second_cult_result = $blood_second_cult_result;
    }

    /**
     * @return string
     */
    public function getBloodSecondCultOther()
    {
        return $this->blood_second_cult_other;
    }

    /**
     * @param string $blood_second_cult_other
     */
    public function setBloodSecondCultOther($blood_second_cult_other)
    {
        $this->blood_second_cult_other = $blood_second_cult_other;
    }

    /**
     * @return GramStain
     */
    public function getBloodSecondGramStain()
    {
        return $this->blood_second_gram_stain;
    }

    /**
     * @param GramStain $blood_second_gram_stain
     */
    public function setBloodSecondGramStain(GramStain $blood_second_gram_stain)
    {
        $this->blood_second_gram_stain = $blood_second_gram_stain;
    }

    /**
     * @return GramStainResult
     */
    public function getBloodSecondGramResult()
    {
        return $this->blood_second_gram_result;
    }

    /**
     * @param GramStainResult $blood_second_gram_result
     */
    public function setBloodSecondGramResult(GramStainResult $blood_second_gram_result)
    {
        $this->blood_second_gram_result = $blood_second_gram_result;
    }

    /**
     * @return string
     */
    public function getBloodSecondGramOther()
    {
        return $this->blood_second_gram_other;
    }

    /**
     * @param string $blood_second_gram_other
     */
    public function setBloodSecondGramOther($blood_second_gram_other)
    {
        $this->blood_second_gram_other = $blood_second_gram_other;
    }

    /**
     * @return PCRResult
     */
    public function getBloodSecondPcrResult()
    {
        return $this->blood_second_pcr_result;
    }

    /**
     * @param PCRResult $blood_second_pcr_result
     */
    public function setBloodSecondPcrResult(PCRResult $blood_second_pcr_result)
    {
        $this->blood_second_pcr_result = $blood_second_pcr_result;
    }

    /**
     * @return string
     */
    public function getBloodSecondPcrOther()
    {
        return $this->blood_second_pcr_other;
    }

    /**
     * @param string $blood_second_pcr_other
     */
    public function setBloodSecondPcrOther($blood_second_pcr_other)
    {
        $this->blood_second_pcr_other = $blood_second_pcr_other;
    }
}
