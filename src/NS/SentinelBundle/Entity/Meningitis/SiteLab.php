<?php

namespace NS\SentinelBundle\Entity\Meningitis;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SecurityBundle\Annotation as Security;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\BaseSiteLabInterface;
use NS\SentinelBundle\Form\IBD\Types\BinaxResult;
use NS\SentinelBundle\Form\IBD\Types\CultureResult;
use NS\SentinelBundle\Form\IBD\Types\GramStain;
use NS\SentinelBundle\Form\IBD\Types\GramStainResult;
use NS\SentinelBundle\Form\IBD\Types\LatResult;
use NS\SentinelBundle\Form\IBD\Types\PCRResult;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators as LocalAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Meningitis\SiteLabRepository")
 * @ORM\Table(name="mening_site_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseStatusListener"})
 *
 * @Security\Secured(conditions={
 *      @Security\SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @Security\SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @Security\SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="csfCultDone", otherField="csfCultResult", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"}, field="csfCultResult", otherField="csfCultOther", value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER",)
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="csfLatDone", otherField="csfLatResult", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",)
 * @LocalAssert\Other(groups={"Completeness"}, field="csfLatResult", otherField="csfLatOther", value="\NS\SentinelBundle\Form\IBD\Types\LatResult::OTHER")
 * 
 * @LocalAssert\Other(groups={"Completeness"}, field="csfPcrDone", otherField="csfPcrResult", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"}, field="csfPcrResult", otherField="csfPcrOther", value="\NS\SentinelBundle\Form\IBD\Types\PCRResult::OTHER")
 * 
 * @LocalAssert\Other(groups={"Completeness"},field="csfGramDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="csfGramStain")
 * @LocalAssert\Other(groups={"Completeness"},field="csfGramStain",value={"\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_POSITIVE","\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_NEGATIVE","\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_VARIABLE"},otherField="csfGramResult")
 * @LocalAssert\Other(groups={"Completeness"},field="csfGramResult",value="\NS\SentinelBundle\Form\IBD\Types\GramStainResult::OTHER",otherField="csfGramOther")
 *
 * @LocalAssert\Other(groups={"Completeness"},field="bloodGramDone",value="\NS\SentinelBundle\Form\Types\TripleChoice::YES",otherField="bloodGramStain")
 * @LocalAssert\Other(groups={"Completeness"},field="bloodGramStain",value={"\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_POSITIVE","\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_NEGATIVE","\NS\SentinelBundle\Form\IBD\Types\GramStain::GM_VARIABLE"},otherField="bloodGramResult")
 * @LocalAssert\Other(groups={"Completeness"},field="bloodGramResult",value="\NS\SentinelBundle\Form\IBD\Types\GramStainResult::OTHER",otherField="bloodGramOther")
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="bloodCultDone", otherField="bloodCultResult", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"}, field="bloodCultResult", otherField="bloodCultOther", value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER")
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="bloodPcrDone", otherField="bloodPcrResult", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"}, field="bloodPcrResult", otherField="bloodPcrOther", value="\NS\SentinelBundle\Form\IBD\Types\PCRResult::OTHER")
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="otherCultDone", otherField="otherCultResult", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"}, field="otherCultResult", otherField="otherCultOther", value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER")
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="otherTestDone", otherField="otherTestResult", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"}, field="otherTestResult", otherField="otherTestOther", value="\NS\SentinelBundle\Form\IBD\Types\CultureResult::OTHER")
 *
 * @LocalAssert\Other(groups={"Completeness"}, field="csfBinaxDone", otherField="csfBinaxResult", value="\NS\SentinelBundle\Form\Types\TripleChoice::YES")
 *
 * @LocalAssert\RelatedField(sourceField="nlCsfSent",sourceValue={true},fields={"nlCsfDate"}, groups={"Completeness"})
 * @LocalAssert\RelatedField(sourceField="nlIsolCsfSent",sourceValue={true},fields={"nlIsolCsfDate"}, groups={"Completeness"})
 * @LocalAssert\RelatedField(sourceField="nlIsolBloodSent",sourceValue={true},fields={"nlIsolBloodDate"}, groups={"Completeness"})
 */
class SiteLab implements BaseSiteLabInterface
{
    /**
     * @var Meningitis|BaseCase
     *
     * @ORM\OneToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis\Meningitis",inversedBy="siteLab",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false,unique=true,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;

    //Case-based Laboratory Data

    /**
     * @var string|null
     * @ORM\Column(name="csf_id",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_id;

    /**
     * @var DateTime|null
     * @ORM\Column(name="csf_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_lab_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="csf_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_lab_time;

    /**
     * @var int|null
     * @ORM\Column(name="csf_wcc", type="integer", nullable=true)
     *
     * @Assert\Range(min=0,max=9999,minMessage="You cannot have a negative white blood cell count",maxMessage="Invalid value")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_wcc;

    /**
     * @var int|null
     * @ORM\Column(name="csf_glucose", type="integer", nullable=true)
     *
     * @Assert\Type(type="numeric", message="Invalid value. Must be a number")
     * @Assert\GreaterThanOrEqual(value=0, message="Invalid value - value must be greater than 0")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_glucose;

    /**
     * @var int|null
     * @ORM\Column(name="csf_protein", type="integer",nullable=true)
     *
     * @Assert\Type(type="numeric", message="Invalid value. Must be a number")
     * @Assert\GreaterThanOrEqual(value=0, message="Invalid value - value must be greater than 0")
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_protein;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="csf_cult_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_cult_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="csf_gram_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_gram_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="csf_binax_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_binax_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="csf_lat_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_lat_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="csf_pcr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"Completeness"}, caseField="csf_collected", caseFieldValue="1", message="CSF was collected, so this field is expected")
     */
    private $csf_pcr_done;

    /**
     * @var CultureResult|null
     * @ORM\Column(name="csf_cult_result",type="CultureResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_cult_result;

    /**
     * @var string|null
     * @ORM\Column(name="csf_cult_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_cult_other;

    /**
     * TODO WHERE DOES THIS COME FROM??
     * @var string|null
     * @ORM\Column(name="csf_cult_contaminant",type="string",nullable=true)
     */
    private $csf_cult_contaminant;

    /**
     * @var GramStain|null
     * @ORM\Column(name="csf_gram_stain",type="GramStain",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_gram_stain;

    /**
     * @var GramStainResult|null
     * @ORM\Column(name="csf_gram_result",type="GramStainResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_gram_result;

    /**
     * @var string|null
     * @ORM\Column(name="csf_gram_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_gram_other;

    /**
     * @var BinaxResult|null
     * @ORM\Column(name="csf_binax_result",type="BinaxResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_binax_result;

    /**
     * @var LatResult|null
     * @ORM\Column(name="csf_lat_result",type="LatResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_lat_result;

    /**
     * @var string|null
     * @ORM\Column(name="csf_lat_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_lat_other;

    /**
     * @var PCRResult|null
     * @ORM\Column(name="csf_pcr_result",type="PCRResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_pcr_result;

    /**
     * @var string|null
     * @ORM\Column(name="csf_pcr_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_pcr_other;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="csf_store",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $csf_store;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="isol_store",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $isol_store;

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
     * @LocalAssert\CaseRelated(groups={"AMR+Completeness"}, caseField="blood_number_of_samples", caseFieldValue="2", message="Two blood samples were collected, so this field is expected")
     */
    private $blood_second_id;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_second_lab_date",type="date",nullable=true)
     * @Assert\DateTime
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\CaseRelated(groups={"AMR+Completeness"}, caseField="blood_number_of_samples", caseFieldValue="2", message="Two blood samples were collected, so this field is expected")
     */
    private $blood_second_lab_date;

    /**
     * @var DateTime|null
     * @ORM\Column(name="blood_second_lab_time",type="time",nullable=true)
     * @Assert\DateTime
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'H:i:s'>")
     * @LocalAssert\CaseRelated(groups={"AMR+Completeness"}, caseField="blood_number_of_samples", caseFieldValue="2", message="Two blood samples were collected, so this field is expected")
     */
    private $blood_second_lab_time;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_second_cult_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"AMR+Completeness"}, caseField="blood_number_of_samples", caseFieldValue="2", message="Two blood samples were collected, so this field is expected")
     */
    private $blood_second_cult_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_second_gram_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"AMR+Completeness"}, caseField="blood_number_of_samples", caseFieldValue="2", message="Two blood samples were collected, so this field is expected")
     */
    private $blood_second_gram_done;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="blood_second_pcr_done",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @LocalAssert\CaseRelated(groups={"AMR+Completeness"}, caseField="blood_number_of_samples", caseFieldValue="2", message="Two blood samples were collected, so this field is expected")
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

//=================================
// NL

    /**
     * @var boolean|null
     * @ORM\Column(name="nl_csf_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Assert\NotNull(groups={"Completeness"})
     */
    private $nl_csf_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="nl_csf_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $nl_csf_date;

    /**
     * @var boolean|null
     * @ORM\Column(name="nl_isol_csf_sent",type="boolean",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Assert\NotNull(groups={"Completeness"})
     */
    private $nl_isol_csf_sent;

    /**
     * @var DateTime|null
     * @ORM\Column(name="nl_isol_csf_date",type="date",nullable=true)
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $nl_isol_csf_date;

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
     * @TODO these aren't exposed... should they be??
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

    public function __construct(Meningitis $case = null)
    {
        if ($case) {
            $this->caseFile = $case;
        }

        $this->updatedAt = new DateTime();
        $this->status    = new CaseStatus(CaseStatus::OPEN);
    }

    /**
     * @return Meningitis|BaseCase
     */
    public function getCaseFile(): BaseCase
    {
        return $this->caseFile;
    }

    /**
     * @param BaseCase|Meningitis $caseFile
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

    public function getStatus(): CaseStatus
    {
        return $this->status;
    }

    public function setStatus(CaseStatus $status): void
    {
        $this->status = $status;
    }

    public function hasCase(): bool
    {
        return ($this->caseFile instanceof Meningitis);
    }

    public function getCsfLabDate(): ?DateTime
    {
        return $this->csf_lab_date;
    }

    public function getCsfLabTime(): ?DateTime
    {
        return $this->csf_lab_time;
    }

    public function getCsfWcc(): ?int
    {
        return $this->csf_wcc;
    }

    public function getCsfGlucose(): ?int
    {
        return $this->csf_glucose;
    }

    public function getCsfProtein(): ?int
    {
        return $this->csf_protein;
    }

    public function getCsfCultDone(): ?TripleChoice
    {
        return $this->csf_cult_done;
    }

    public function getCsfCultContaminant(): ?string
    {
        return $this->csf_cult_contaminant;
    }

    public function getCsfGramDone(): ?TripleChoice
    {
        return $this->csf_gram_done;
    }

    public function getCsfBinaxDone(): ?TripleChoice
    {
        return $this->csf_binax_done;
    }

    public function getCsfLatDone(): ?TripleChoice
    {
        return $this->csf_lat_done;
    }

    public function getCsfPcrDone(): ?TripleChoice
    {
        return $this->csf_pcr_done;
    }

    public function getCsfCultResult(): ?CultureResult
    {
        return $this->csf_cult_result;
    }

    public function getCsfCultOther(): ?string
    {
        return $this->csf_cult_other;
    }

    public function getCsfGramResult(): ?GramStainResult
    {
        return $this->csf_gram_result;
    }

    public function getCsfGramStain(): ?GramStain
    {
        return $this->csf_gram_stain;
    }

    public function getCsfGramOther(): ?string
    {
        return $this->csf_gram_other;
    }

    public function getCsfBinaxResult(): ?BinaxResult
    {
        return $this->csf_binax_result;
    }

    public function getCsfLatResult(): ?LatResult
    {
        return $this->csf_lat_result;
    }

    public function getCsfLatOther(): ?string
    {
        return $this->csf_lat_other;
    }

    public function getCsfPcrResult(): ?PCRResult
    {
        return $this->csf_pcr_result;
    }

    public function getCsfPcrOther(): ?string
    {
        return $this->csf_pcr_other;
    }

    public function getCsfStore(): ?TripleChoice
    {
        return $this->csf_store;
    }

    public function getIsolStore(): ?TripleChoice
    {
        return $this->isol_store;
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

    public function getCsfId(): ?string
    {
        return $this->csf_id;
    }

    public function getBloodId(): ?string
    {
        return $this->blood_id;
    }

    public function getOtherTestDone(): ?TripleChoice
    {
        return $this->other_test_done;
    }

    public function setOtherTestDone(TripleChoice $otherTestDone): void
    {
        $this->other_test_done = $otherTestDone;
    }

    public function getOtherTestResult(): ?CultureResult
    {
        return $this->other_test_result;
    }

    public function setOtherTestResult(?CultureResult $otherTestResult = null): void
    {
        $this->other_test_result = $otherTestResult;
    }

    public function getOtherTestOther(): ?string
    {
        return $this->other_test_other;
    }

    public function setOtherTestOther(?string $otherTestOther = null): void
    {
        $this->other_test_other = $otherTestOther;
    }

    public function setCsfId(?string $csfId = null): void
    {
        $this->csf_id = $csfId;
    }

    public function setBloodId(?string $bloodId = null): void
    {
        $this->blood_id = $bloodId;
    }

    public function setCsfLabDate(?DateTime $csfLabDate): void
    {
        $this->csf_lab_date = $csfLabDate;
    }

    public function setCsfLabTime(?DateTime $csfLabTime): void
    {
        $this->csf_lab_time= $csfLabTime;
    }

    public function setCsfWcc(?int $csfWcc = null): void
    {
        $this->csf_wcc = $csfWcc;
    }

    public function setCsfGlucose(?int $csfGlucose = null): void
    {
        $this->csf_glucose = $csfGlucose;
    }

    public function setCsfProtein(?int $csfProtein = null): void
    {
        $this->csf_protein = $csfProtein;
    }

    public function setCsfCultContaminant(?string $csfCultContaminant = null): void
    {
        $this->csf_cult_contaminant = $csfCultContaminant;
    }

    public function setCsfCultDone(?TripleChoice $csfCultDone): void
    {
        $this->csf_cult_done = $csfCultDone;
    }

    public function setCsfGramDone(?TripleChoice $csfGramDone): void
    {
        $this->csf_gram_done = $csfGramDone;
    }

    public function setCsfBinaxDone(?TripleChoice $csfBinaxDone): void
    {
        $this->csf_binax_done = $csfBinaxDone;
    }

    public function setCsfLatDone(?TripleChoice $csfLatDone): void
    {
        $this->csf_lat_done = $csfLatDone;
    }

    public function setCsfPcrDone(?TripleChoice $csfPcrDone): void
    {
        $this->csf_pcr_done = $csfPcrDone;
    }

    public function setCsfCultResult(?CultureResult $csfCultResult): void
    {
        $this->csf_cult_result = $csfCultResult;
    }

    public function setCsfCultOther(?string $csfCultOther = null): void
    {
        $this->csf_cult_other = $csfCultOther;
    }

    public function setCsfGramResult(?GramStainResult $csfGramResult): void
    {
        $this->csf_gram_result = $csfGramResult;
    }

    public function setCsfGramStain(?GramStain $csfGramStain): void
    {
        $this->csf_gram_stain = $csfGramStain;
    }

    public function setCsfGramOther(?string $csfGramOther = null): void
    {
        $this->csf_gram_other = $csfGramOther;
    }

    public function setCsfBinaxResult(?BinaxResult $csfBinaxResult): void
    {
        $this->csf_binax_result = $csfBinaxResult;
    }

    public function setCsfLatResult(?LatResult $csfLatResult): void
    {
        $this->csf_lat_result = $csfLatResult;
    }

    public function setCsfLatOther(?string $csfLatOther = null): void
    {
        $this->csf_lat_other = $csfLatOther;
    }

    public function setCsfPcrResult(?PCRResult $csfPcrResult): void
    {
        $this->csf_pcr_result= $csfPcrResult;
    }

    public function setCsfPcrOther(?string $csfPcrOther = null): void
    {
        $this->csf_pcr_other = $csfPcrOther;
    }

    public function setCsfStore(?TripleChoice $csfStore): void
    {
        $this->csf_store = $csfStore;
    }

    public function setIsolStore(?TripleChoice $isolStore): void
    {
        $this->isol_store = $isolStore;
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

    /**
     * @param PCRResult $bloodPcrResult
     */
    public function setBloodPcrResult(PCRResult $bloodPcrResult): void
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

    public function getNlCsfSent(): ?bool
    {
        return $this->nl_csf_sent;
    }

    public function setNlCsfSent(?bool $nl_csf_sent = null): void
    {
        $this->nl_csf_sent = $nl_csf_sent;
    }

    public function getNlCsfDate(): ?DateTime
    {
        return $this->nl_csf_date;
    }

    public function setNlCsfDate(?DateTime $nl_csf_date = null): void
    {
        $this->nl_csf_date = $nl_csf_date;
    }

    public function getNlIsolCsfSent(): ?bool
    {
        return $this->nl_isol_csf_sent;
    }

    public function setNlIsolCsfSent(?bool $nl_isol_csf_sent = null): void
    {
        $this->nl_isol_csf_sent = $nl_isol_csf_sent;
    }

    public function getNlIsolCsfDate(): ?DateTime
    {
        return $this->nl_isol_csf_date;
    }

    public function setNlIsolCsfDate(?DateTime $nl_isol_csf_date = null): void
    {
        $this->nl_isol_csf_date = $nl_isol_csf_date;
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

    public function isComplete(): bool
    {
        return $this->status->equal(CaseStatus::COMPLETE);
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
        return null;
    }

    public function getSentToNationalLab(): bool
    {
        return ($this->nl_csf_sent || $this->nl_isol_csf_sent || $this->nl_isol_blood_sent || $this->nl_broth_sent || $this->nl_other_sent);
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
