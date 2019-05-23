<?php

namespace NS\SentinelBundle\Entity\Pneumonia;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SecurityBundle\Annotation as Security;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\BaseSiteLabInterface;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators as LocalAssert;
use Symfony\Component\Validator\Constraints as Assert;
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Pneumonia\SiteLabRepository")
 * @ORM\Table(name="pneu_site_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseStatusListener"})
 *
 * @Security\Secured(conditions={
 *      @Security\SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @Security\SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @Security\SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @LocalAssert\Other(groups={"Completeness"},field="bloodCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="bloodCultResult")
 * @LocalAssert\Other(groups={"Completeness"},field="bloodCultResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="bloodCultOther")
 *
 * @LocalAssert\Other(groups={"Completeness"},field="bloodGramDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="bloodGramStain")
 * @LocalAssert\Other(groups={"Completeness"},field="bloodGramStain",value={"\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_POSITIVE","\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_NEGATIVE","\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_VARIABLE"},otherField="bloodGramResult")
 * @LocalAssert\Other(groups={"Completeness"},field="bloodGramResult",value="\NS\SentinelBundle\Form\IBD\Types\GramStainResult::OTHER",otherField="bloodGramOther")
 *
 * @LocalAssert\Other(groups={"Completeness"},field="bloodPcrDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="bloodPcrResult")
 * @LocalAssert\Other(groups={"Completeness"},field="bloodPcrResult",value="\NS\SentinelBundle\Form\IBD\Types\PCRResult::OTHER",otherField="bloodPcrOther")
 *
 * @LocalAssert\Other(groups={"Completeness"},field="otherCultDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherCultResult")
 * @LocalAssert\Other(groups={"Completeness"},field="otherCultResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="otherCultOther")
 *
 * @LocalAssert\Other(groups={"Completeness"},field="otherTestDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="otherTestResult")
 * @LocalAssert\Other(groups={"Completeness"},field="otherTestResult",value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",otherField="otherTestOther")
 *
 * @LocalAssert\Other(groups={"AMR+Completeness"},field="pleuralFluidCultureDone", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES", otherField="pleuralFluidCultureResult")
 * @LocalAssert\Other(groups={"AMR+Completeness"},field="pleuralFluidCultureResult", value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER", otherField="pleuralFluidCultureOther")
 *
 * @LocalAssert\Other(groups={"AMR+Completeness"},field="pleuralFluidGramDone", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES", otherField="pleuralFluidGramResult")
 * @LocalAssert\Other(groups={"AMR+Completeness"},field="pleuralFluidGramResult", value={"\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_POSITIVE","\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_NEGATIVE","\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_VARIABLE"}, otherField="pleuralFluidGramResultOrganism")
 *
 * @LocalAssert\Other(groups={"AMR+Completeness"},field="pleuralFluidPcrDone", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES", otherField="pleuralFluidPcrResult")
 * @LocalAssert\Other(groups={"AMR+Completeness"},field="pleuralFluidPcrResult", value="\NS\SentinelBundle\Form\IBD\Types\PCRResult::OTHER", otherField="pleuralFluidPcrOther")
 */
class SiteLab implements BaseSiteLabInterface
{
    /**
     * @var Pneumonia|BaseCase
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
     * @var string|null
     * @ORM\Column(name="blood_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="blood_collected", caseFieldValue="1", message="Blood was collected, so this field is expected")
     */
    private $blood_id;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="blood_collected", caseFieldValue="1", message="Blood was collected, so this field is expected")
     */
    private $blood_lab_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="blood_collected", caseFieldValue="1", message="Blood was collected, so this field is expected")
     */
    private $blood_lab_time;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_cult_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="blood_collected", caseFieldValue="1", message="Blood was collected, so this field is expected")
     */
    private $blood_cult_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_gram_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="blood_collected", caseFieldValue="1", message="Blood was collected, so this field is expected")
     */
    private $blood_gram_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_pcr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="blood_collected", caseFieldValue="1", message="Blood was collected, so this field is expected")
     */
    private $blood_pcr_done;

    /**
     * @var CultureResult|null
     * @ORM\Column(name="blood_cult_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_cult_result;

    /**
     * @var string|null
     * @ORM\Column(name="blood_cult_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_cult_other;

    /**
     * @var GramStain|null
     * @ORM\Column(name="blood_gram_stain",type="GramStain",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_gram_stain;

    /**
     * @var GramStainResult|null
     * @ORM\Column(name="blood_gram_result",type="GramStainResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_gram_result;

    /**
     * @var string|null
     * @ORM\Column(name="blood_gram_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_gram_other;

    /**
     * @var PCRResult|null
     * @ORM\Column(name="blood_pcr_result",type="PCRResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_pcr_result;

    /**
     * @var string|null
     * @ORM\Column(name="blood_pcr_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_pcr_other;

    //============
    /**
     * @var string|null
     * @ORM\Column(name="blood_second_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\SecondBlood(groups={"AMR+Completeness"})
     */
    private $blood_second_id;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_second_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\SecondBlood(groups={"AMR+Completeness"})
     */
    private $blood_second_lab_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_second_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     * @LocalAssert\SecondBlood(groups={"AMR+Completeness"})
     */
    private $blood_second_lab_time;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_second_cult_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\SecondBlood(groups={"AMR+Completeness"})
     */
    private $blood_second_cult_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_second_gram_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\SecondBlood(groups={"AMR+Completeness"})
     */
    private $blood_second_gram_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_second_pcr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\SecondBlood(groups={"AMR+Completeness"})
     */
    private $blood_second_pcr_done;

    /**
     * @var CultureResult|null
     * @ORM\Column(name="blood_second_cult_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_cult_result;

    /**
     * @var string|null
     * @ORM\Column(name="blood_second_cult_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_cult_other;

    /**
     * @var GramStain|null
     * @ORM\Column(name="blood_second_gram_stain",type="GramStain",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_gram_stain;

    /**
     * @var GramStainResult|null
     * @ORM\Column(name="blood_second_gram_result",type="GramStainResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_gram_result;

    /**
     * @var string|null
     * @ORM\Column(name="blood_second_gram_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_gram_other;

    /**
     * @var PCRResult|null
     * @ORM\Column(name="blood_second_pcr_result",type="PCRResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_pcr_result;

    /**
     * @var string|null
     * @ORM\Column(name="blood_second_pcr_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $blood_second_pcr_other;

    //============
    /**
     * @var string|null
     * @ORM\Column(name="other_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"}, caseField="other_specimen_collected", caseFieldValue={1,2,3}, message="Other samples were collected, so this field is expected")
     */
    private $other_id;

    /**
     * PAHO request
     * @var string|null
     * @ORM\Column(name="other_type",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"}, caseField="other_specimen_collected", caseFieldValue={1,2,3}, message="Other samples were collected, so this field is expected")
     */
    private $other_type;

    /**
     * @var DateTime|null
     * @ORM\Column(name="other_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\CaseRelated(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"}, caseField="other_specimen_collected", caseFieldValue={1,2,3}, message="Other samples were collected, so this field is expected")
     */
    private $other_lab_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="other_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     * @LocalAssert\CaseRelated(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"}, caseField="other_specimen_collected", caseFieldValue={1,2,3}, message="Other samples were collected, so this field is expected")
     */
    private $other_lab_time;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="other_cult_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"}, caseField="other_specimen_collected", caseFieldValue={1,2,3}, message="Other samples were collected, so this field is expected")
     */
    private $other_cult_done;

    /**
     * @var CultureResult|null
     * @ORM\Column(name="other_cult_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_cult_result;

    /**
     * @var string|null
     * @ORM\Column(name="other_cult_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_cult_other;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="other_test_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"}, caseField="other_specimen_collected", caseFieldValue={1,2,3}, message="Other samples were collected, so this field is expected")
     */
    private $other_test_done;

    /**
     * @var CultureResult|null
     * @ORM\Column(name="other_test_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_test_result;

    /**
     * @var string|null
     * @ORM\Column(name="other_test_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $other_test_other;
//==================================
// TODO - all the rl_X fields aren't exposed in forms but are only partially included in the NationalLab...

    /**
     * @var boolean|null
     * @ORM\Column(name="rl_isol_blood_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_isol_blood_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rl_isol_blood_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_isol_blood_date;

    /**
     * @var boolean|null
     * @ORM\Column(name="rl_broth_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_broth_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rl_broth_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_broth_date;

    /**
     * @var boolean|null
     * @ORM\Column(name="rl_other_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     */
    private $rl_other_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="rl_other_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $rl_other_date;

//=================================
// NL
    /**
     * @var boolean|null
     * @ORM\Column(name="nl_isol_blood_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Assert\NotNull(groups={"Completeness"})
     */
    private $nl_isol_blood_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="nl_isol_blood_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $nl_isol_blood_date;

    /**
     * @var boolean|null
     * @ORM\Column(name="nl_broth_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Assert\NotNull(groups={"Completeness"})
     */
    private $nl_broth_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="nl_broth_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $nl_broth_date;

    /**
     * @var boolean|null
     * @ORM\Column(name="nl_other_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Assert\NotNull(groups={"Completeness"})
     */
    private $nl_other_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="nl_other_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $nl_other_date;

//==================================
    /**
     * @var DateTime
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d H:i:s'>")
     */
    private $updatedAt;

    /**
     * @var CaseStatus
     * @ORM\Column(name="status",type="CaseStatus")
     * @Serializer\Groups({"api","export"})
     */
    private $status;

//=================================
// PAHO
    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pleural_fluid_culture_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="pleural_fluid_collected", caseFieldValue="1", message="Pleural fluid was collected, so this field is expected")
     */
    private $pleural_fluid_culture_done;

    /**
     * @var CultureResult|null
     * @ORM\Column(name="pleural_fluid_culture_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_culture_result;

    /**
     * @var string|null
     * @ORM\Column(name="pleural_fluid_culture_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_culture_other;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pleural_fluid_gram_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="pleural_fluid_collected", caseFieldValue="1", message="Pleural fluid was collected, so this field is expected")
     */
    private $pleural_fluid_gram_done;

    /**
     * @var GramStain|null
     * @ORM\Column(name="pleural_fluid_gram_result",type="GramStain",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_gram_result;

    /**
     * @var GramStainResult|null
     * @ORM\Column(name="pleural_fluid_gram_result_organism",type="GramStainResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_gram_result_organism;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="pleural_fluid_pcr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="pleural_fluid_collected", caseFieldValue="1", message="Pleural fluid was collected, so this field is expected")
     */
    private $pleural_fluid_pcr_done;

    /**
     * @var PCRResult|null
     * @ORM\Column(name="pleural_fluid_pcr_result",type="PCRResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_pcr_result;

    /**
     * @var string|null
     * @ORM\Column(name="pleural_fluid_pcr_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $pleural_fluid_pcr_other;

    public function __construct(Pneumonia $case = null)
    {
        if ($case) {
            $this->caseFile = $case;
        }

        $this->updatedAt = new DateTime();
        $this->status    = new CaseStatus(CaseStatus::OPEN);
    }

    /**
     * @return Pneumonia|BaseCase
     */
    public function getCaseFile(): BaseCase
    {
        return $this->caseFile;
    }

    /**
     * @param BaseCase|Pneumonia $caseFile
     */
    public function setCaseFile(BaseCase $caseFile): void
    {
        $this->caseFile = $caseFile;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return CaseStatus
     */
    public function getStatus(): CaseStatus
    {
        return $this->status;
    }

    /**
     * @param CaseStatus $status
     */
    public function setStatus(CaseStatus $status): void
    {
        $this->status = $status;
    }

    public function hasCase(): bool
    {
        return ($this->caseFile instanceof Pneumonia);
    }

    public function getBloodCultDone(): ?TripleChoice
    {
        return $this->blood_cult_done;
    }

    public function getBloodGramDone(): ?TripleChoice
    {
        return $this->blood_gram_done;
    }

    public function getBloodPcrDone(): ?TripleChoice
    {
        return $this->blood_pcr_done;
    }

    public function getOtherCultDone(): ?TripleChoice
    {
        return $this->other_cult_done;
    }

    public function getBloodCultResult(): ?CultureResult
    {
        return $this->blood_cult_result;
    }

    public function getBloodCultOther(): ?string
    {
        return $this->blood_cult_other;
    }

    public function getBloodGramResult(): ?GramStainResult
    {
        return $this->blood_gram_result;
    }

    public function getBloodGramStain(): ?GramStain
    {
        return $this->blood_gram_stain;
    }

    public function getBloodGramOther(): ?string
    {
        return $this->blood_gram_other;
    }

    public function getBloodPcrResult(): ?PCRResult
    {
        return $this->blood_pcr_result;
    }

    public function getBloodPcrOther(): ?string
    {
        return $this->blood_pcr_other;
    }

    public function getOtherCultResult(): ?CultureResult
    {
        return $this->other_cult_result;
    }

    public function getOtherCultOther(): ?string
    {
        return $this->other_cult_other;
    }

    public function getBloodId(): ?string
    {
        return $this->blood_id;
    }

    public function getOtherTestDone(): ?TripleChoice
    {
        return $this->other_test_done;
    }

    public function setOtherTestDone(?TripleChoice $otherTestDone): void
    {
        $this->other_test_done = $otherTestDone;
    }

    public function getOtherTestResult(): ?CultureResult
    {
        return $this->other_test_result;
    }

    public function setOtherTestResult(?CultureResult $otherTestResult): void
    {
        $this->other_test_result = $otherTestResult;
    }

    public function getOtherTestOther(): ?string
    {
        return $this->other_test_other;
    }

    public function setOtherTestOther(?string $otherTestOther): void
    {
        $this->other_test_other = $otherTestOther;
    }

    public function setBloodId(?string $bloodId): void
    {
        $this->blood_id = $bloodId;
    }

    public function setBloodCultDone(?TripleChoice $bloodCultDone): void
    {
        $this->blood_cult_done = $bloodCultDone;
    }

    public function setBloodGramDone(?TripleChoice $bloodGramDone): void
    {
        $this->blood_gram_done = $bloodGramDone;
    }

    public function setBloodPcrDone(?TripleChoice $bloodPcrDone): void
    {
        $this->blood_pcr_done = $bloodPcrDone;
    }

    public function setOtherCultDone(?TripleChoice $otherCultDone): void
    {
        $this->other_cult_done = $otherCultDone;
    }

    public function setBloodCultResult(?CultureResult $bloodCultResult): void
    {
        $this->blood_cult_result = $bloodCultResult;
    }

    public function setBloodCultOther(?string $bloodCultOther): void
    {
        $this->blood_cult_other = $bloodCultOther;
    }

    public function setBloodGramResult(?GramStainResult $bloodGramResult): void
    {
        $this->blood_gram_result = $bloodGramResult;
    }

    public function setBloodGramStain(?GramStain $bloodGramStain): void
    {
        $this->blood_gram_stain = $bloodGramStain;
    }

    public function setBloodGramOther(?string $bloodGramOther): void
    {
        $this->blood_gram_other = $bloodGramOther;
    }

    public function setBloodPcrResult(?PCRResult $bloodPcrResult): void
    {
        $this->blood_pcr_result = $bloodPcrResult;
    }

    public function setBloodPcrOther(?string $bloodPcrOther): void
    {
        $this->blood_pcr_other = $bloodPcrOther;
    }

    public function setOtherCultResult(?CultureResult $otherCultResult): void
    {
        $this->other_cult_result = $otherCultResult;
    }

    public function setOtherCultOther(?string $otherCultOther): void
    {
        $this->other_cult_other = $otherCultOther;
    }

    public function getRlIsolBloodSent(): ?bool
    {
        return $this->rl_isol_blood_sent;
    }

    public function setRlIsolBloodSent(?bool $rl_isol_blood_sent = null): void
    {
        $this->rl_isol_blood_sent = $rl_isol_blood_sent;
    }

    public function getRlBrothSent(): ?bool
    {
        return $this->rl_broth_sent;
    }

    public function setRlBrothSent(?bool $rl_broth_sent = null): void
    {
        $this->rl_broth_sent = $rl_broth_sent;
    }

    public function getRlOtherSent(): ?bool
    {
        return $this->rl_other_sent;
    }

    public function setRlOtherSent(?bool $rl_other_sent = null): void
    {
        $this->rl_other_sent = $rl_other_sent;
    }

    public function getRlOtherDate(): ?DateTime
    {
        return $this->rl_other_date;
    }

    public function setRlOtherDate(?DateTime $rl_other_date = null): void
    {
        $this->rl_other_date = $rl_other_date;
    }

    public function getNlIsolBloodSent(): ?bool
    {
        return $this->nl_isol_blood_sent;
    }

    public function setNlIsolBloodSent(?bool $nl_isol_blood_sent = null): void
    {
        $this->nl_isol_blood_sent = $nl_isol_blood_sent;
    }

    public function getNlIsolBloodDate(): ?DateTime
    {
        return $this->nl_isol_blood_date;
    }

    public function setNlIsolBloodDate(?DateTime $nl_isol_blood_date = null): void
    {
        $this->nl_isol_blood_date = $nl_isol_blood_date;
    }

    public function getNlBrothSent(): ?bool
    {
        return $this->nl_broth_sent;
    }

    public function setNlBrothSent(?bool $nl_broth_sent = null): void
    {
        $this->nl_broth_sent = $nl_broth_sent;
    }

    public function getNlBrothDate(): ?DateTime
    {
        return $this->nl_broth_date;
    }

    public function setNlBrothDate(?DateTime $nl_broth_date = null): void
    {
        $this->nl_broth_date = $nl_broth_date;
    }

    public function getNlOtherSent(): ?bool
    {
        return $this->nl_other_sent;
    }

    public function setNlOtherSent(?bool $nl_other_sent = null): void
    {
        $this->nl_other_sent = $nl_other_sent;
    }

    public function getNlOtherDate(): ?DateTime
    {
        return $this->nl_other_date;
    }

    public function setNlOtherDate(?DateTime $nl_other_date = null): void
    {
        $this->nl_other_date = $nl_other_date;
    }

    public function getRlIsolBloodDate(): ?DateTime
    {
        return $this->rl_isol_blood_date;
    }

    public function setRlIsolBloodDate(?DateTime $rl_isol_blood_date = null): void
    {
        $this->rl_isol_blood_date = $rl_isol_blood_date;
    }

    public function getRlBrothDate(): ?DateTime
    {
        return $this->rl_broth_date;
    }

    public function setRlBrothDate(?DateTime $rl_broth_date = null): void
    {
        $this->rl_broth_date = $rl_broth_date;
    }

    public function isComplete(): bool
    {
        return $this->status->equal(CaseStatus::COMPLETE);
    }

    public function getPleuralFluidCultureDone(): ?TripleChoice
    {
        return $this->pleural_fluid_culture_done;
    }

    public function setPleuralFluidCultureDone(?TripleChoice $pleural_fluid_culture_done): void
    {
        $this->pleural_fluid_culture_done = $pleural_fluid_culture_done;
    }

    public function getPleuralFluidCultureResult(): ?CultureResult
    {
        return $this->pleural_fluid_culture_result;
    }

    public function setPleuralFluidCultureResult(?CultureResult $pleural_fluid_culture_result): void
    {
        $this->pleural_fluid_culture_result = $pleural_fluid_culture_result;
    }

    public function getPleuralFluidCultureOther(): ?string
    {
        return $this->pleural_fluid_culture_other;
    }

    public function setPleuralFluidCultureOther(?string $pleural_fluid_culture_other): void
    {
        $this->pleural_fluid_culture_other = $pleural_fluid_culture_other;
    }

    public function getPleuralFluidGramDone(): ?TripleChoice
    {
        return $this->pleural_fluid_gram_done;
    }

    public function setPleuralFluidGramDone(?TripleChoice $pleural_fluid_gram_done): void
    {
        $this->pleural_fluid_gram_done = $pleural_fluid_gram_done;
    }

    public function getPleuralFluidGramResult(): ?GramStain
    {
        return $this->pleural_fluid_gram_result;
    }

    public function setPleuralFluidGramResult(?GramStain $pleural_fluid_gram_result): void
    {
        $this->pleural_fluid_gram_result = $pleural_fluid_gram_result;
    }

    public function getPleuralFluidGramResultOrganism(): ?GramStainResult
    {
        return $this->pleural_fluid_gram_result_organism;
    }

    public function setPleuralFluidGramResultOrganism(?GramStainResult $pleural_fluid_gram_result_organism): void
    {
        $this->pleural_fluid_gram_result_organism = $pleural_fluid_gram_result_organism;
    }

    public function getPleuralFluidPcrDone(): ?TripleChoice
    {
        return $this->pleural_fluid_pcr_done;
    }

    public function setPleuralFluidPcrDone(?TripleChoice $pleural_fluid_pcr_done): void
    {
        $this->pleural_fluid_pcr_done = $pleural_fluid_pcr_done;
    }

    public function getPleuralFluidPcrResult(): ?PCRResult
    {
        return $this->pleural_fluid_pcr_result;
    }

    public function setPleuralFluidPcrResult(?PCRResult $pleural_fluid_pcr_result): void
    {
        $this->pleural_fluid_pcr_result = $pleural_fluid_pcr_result;
    }

    public function getPleuralFluidPcrOther(): ?string
    {
        return $this->pleural_fluid_pcr_other;
    }

    public function setPleuralFluidPcrOther(?string $pleural_fluid_pcr_other): void
    {
        $this->pleural_fluid_pcr_other = $pleural_fluid_pcr_other;
    }

    public function getBloodLabDate(): ?DateTime
    {
        return $this->blood_lab_date;
    }

    public function setBloodLabDate(?DateTime $blood_lab_date = null): void
    {
        $this->blood_lab_date = $blood_lab_date;
    }

    public function getBloodLabTime(): ?DateTime
    {
        return $this->blood_lab_time;
    }

    public function setBloodLabTime(?DateTime $blood_lab_time = null): void
    {
        $this->blood_lab_time = $blood_lab_time;
    }

    public function getOtherId(): ?string
    {
        return $this->other_id;
    }

    public function setOtherId(?string $other_id): void
    {
        $this->other_id = $other_id;
    }

    public function getOtherType(): ?string
    {
        return $this->other_type;
    }

    public function setOtherType(?string $other_type): void
    {
        $this->other_type = $other_type;
    }

    public function getOtherLabDate(): ?DateTime
    {
        return $this->other_lab_date;
    }

    public function setOtherLabDate(?DateTime $other_lab_date = null): void
    {
        $this->other_lab_date = $other_lab_date;
    }

    public function getOtherLabTime(): ?DateTime
    {
        return $this->other_lab_time;
    }

    public function setOtherLabTime(?DateTime $other_lab_time = null): void
    {
        $this->other_lab_time = $other_lab_time;
    }

    public function getSentToReferenceLab(): ?bool
    {
        return ($this->rl_isol_blood_sent || $this->rl_broth_sent || $this->rl_other_sent);
    }

    public function getSentToNationalLab(): bool
    {
        return ($this->nl_isol_blood_sent || $this->nl_broth_sent || $this->nl_other_sent);
    }

    // Second blood sample results
    public function getBloodSecondId(): ?string
    {
        return $this->blood_second_id;
    }

    public function setBloodSecondId(?string $blood_second_id): void
    {
        $this->blood_second_id = $blood_second_id;
    }

    public function getBloodSecondLabDate(): ?DateTime
    {
        return $this->blood_second_lab_date;
    }

    public function setBloodSecondLabDate(?DateTime $blood_second_lab_date = null): void
    {
        $this->blood_second_lab_date = $blood_second_lab_date;
    }

    public function getBloodSecondLabTime(): ?DateTime
    {
        return $this->blood_second_lab_time;
    }

    public function setBloodSecondLabTime(?DateTime $blood_second_lab_time = null): void
    {
        $this->blood_second_lab_time = $blood_second_lab_time;
    }

    public function getBloodSecondCultDone(): ?TripleChoice
    {
        return $this->blood_second_cult_done;
    }

    public function setBloodSecondCultDone(?TripleChoice $blood_second_cult_done): void
    {
        $this->blood_second_cult_done = $blood_second_cult_done;
    }

    public function getBloodSecondGramDone(): ?TripleChoice
    {
        return $this->blood_second_gram_done;
    }

    public function setBloodSecondGramDone(?TripleChoice $blood_second_gram_done): void
    {
        $this->blood_second_gram_done = $blood_second_gram_done;
    }

    public function getBloodSecondPcrDone(): ?TripleChoice
    {
        return $this->blood_second_pcr_done;
    }

    public function setBloodSecondPcrDone(?TripleChoice $blood_second_pcr_done): void
    {
        $this->blood_second_pcr_done = $blood_second_pcr_done;
    }

    public function getBloodSecondCultResult(): ?CultureResult
    {
        return $this->blood_second_cult_result;
    }

    public function setBloodSecondCultResult(?CultureResult $blood_second_cult_result): void
    {
        $this->blood_second_cult_result = $blood_second_cult_result;
    }

    public function getBloodSecondCultOther(): ?string
    {
        return $this->blood_second_cult_other;
    }

    public function setBloodSecondCultOther(?string $blood_second_cult_other): void
    {
        $this->blood_second_cult_other = $blood_second_cult_other;
    }

    public function getBloodSecondGramStain(): ?GramStain
    {
        return $this->blood_second_gram_stain;
    }

    public function setBloodSecondGramStain(?GramStain $blood_second_gram_stain): void
    {
        $this->blood_second_gram_stain = $blood_second_gram_stain;
    }

    public function getBloodSecondGramResult(): ?GramStainResult
    {
        return $this->blood_second_gram_result;
    }

    public function setBloodSecondGramResult(?GramStainResult $blood_second_gram_result): void
    {
        $this->blood_second_gram_result = $blood_second_gram_result;
    }

    public function getBloodSecondGramOther(): ?string
    {
        return $this->blood_second_gram_other;
    }

    public function setBloodSecondGramOther(?string $blood_second_gram_other): void
    {
        $this->blood_second_gram_other = $blood_second_gram_other;
    }

    public function getBloodSecondPcrResult(): ?PCRResult
    {
        return $this->blood_second_pcr_result;
    }

    public function setBloodSecondPcrResult(?PCRResult $blood_second_pcr_result): void
    {
        $this->blood_second_pcr_result = $blood_second_pcr_result;
    }

    public function getBloodSecondPcrOther(): ?string
    {
        return $this->blood_second_pcr_other;
    }

    public function setBloodSecondPcrOther(?string $blood_second_pcr_other): void
    {
        $this->blood_second_pcr_other = $blood_second_pcr_other;
    }
}
