<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\BaseSiteLabInterface;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators as LocalAssert;
use NS\UtilBundle\Validator\Constraints as UtilAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus\SiteLabRepository")
 * @ORM\Table(name="rotavirus_site_labs")
 * @ORM\EntityListeners(value={"NS\SentinelBundle\Entity\Listener\BaseStatusListener"})
 *
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 *
 * @LocalAssert\GreaterThanDate(lessThanField="caseFile.stoolCollectionDate",greaterThanField="received",message="form.validation.vaccination-after-admission")
 * @LocalAssert\RelatedField(sourceField="elisaDone",sourceValue={"1"},fields={"elisaTestDate","elisaResult"})
 *
 * @LocalAssert\Other(groups={"Completeness"},field="elisaKit",otherField="elisaKitOther",value="NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit::OTHER")
 * @LocalAssert\Other(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"},field="genotypingResultG",otherField="genotypingResultGSpecify",value={"NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG::OTHER", "NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG::MIXED"})
 * @LocalAssert\Other(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"},field="genotypeResultP",otherField="genotypeResultPSpecify",value={"NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP::OTHER", "NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP::MIXED"})
 * @LocalAssert\Other(groups={"Completeness"},field="stoolSentToNL",otherField="stoolSentToNLDate",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 * @LocalAssert\Other(groups={"Completeness"},field="stoolSentToRRL",otherField="stoolSentToRRLDate",value="NS\SentinelBundle\Form\Types\TripleChoice::YES")
 */
class SiteLab implements BaseSiteLabInterface
{
    /**
     * @ORM\OneToOne(targetEntity="NS\SentinelBundle\Entity\RotaVirus",inversedBy="siteLab")
     * @ORM\JoinColumn(nullable=false,unique=true,onDelete="CASCADE")
     * @ORM\Id
     */
    protected $caseFile;
//---------------------------------
    // Global Variables
    /**
     * @var DateTime|null
     * @ORM\Column(name="received",type="datetime",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Default","Completeness"})
     * @Assert\Date(groups={"Default","Completeness"})
     * @LocalAssert\NoFutureDate
     */
    private $received;

    /**
     * stool_adequate
     * @var TripleChoice|null
     * @ORM\Column(name="adequate",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Default","Completeness"})
     * @UtilAssert\ArrayChoiceConstraint(groups={"Completeness"})
     */
    private $adequate;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="stored",type="TripleChoice",nullable=true)
     * @Assert\NotBlank(groups={"Default","Completeness"})
     * @UtilAssert\ArrayChoiceConstraint(groups={"Default","Completeness"})
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $stored;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="elisaDone",type="TripleChoice",nullable=true)
     * @Assert\NotBlank(groups={"Default","Completeness"})
     * @UtilAssert\ArrayChoiceConstraint(groups={"Default","Completeness"})
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $elisaDone;

    /**
     * @var ElisaKit|null
     * @ORM\Column(name="elisaKit",type="ElisaKit",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $elisaKit;

    /**
     * @var string|null
     * @ORM\Column(name="elisaKitOther",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaKitOther;

    /**
     * @var string|null
     * @ORM\Column(name="elisaLoadNumber",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $elisaLoadNumber;

    /**
     * @var DateTime|null
     * @ORM\Column(name="elisaExpiryDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $elisaExpiryDate;

    /**
     * @var DateTime|null
     * @ORM\Column(name="elisaTestDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\NotBlank(groups={"Completeness"})
     * @LocalAssert\NoFutureDate
     */
    private $elisaTestDate;

    /**
     * @var ElisaResult|null
     * @ORM\Column(name="elisaResult",type="ElisaResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $elisaResult;

    /**
     * @var DateTime|null
     * @ORM\Column(name="genotypingDate",type="date", nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\SerializedName("genotyping_date")
     * @LocalAssert\NoFutureDate
     * @Assert\NotBlank(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"})
     */
    private $genotypingDate;

    /**
     * @var GenotypeResultG|null
     * @ORM\Column(name="genotypingResultG",type="GenotypeResultG", nullable=true)
     * @Serializer\Groups({"api","export"})
     * @UtilAssert\ArrayChoiceConstraint(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"})
     */
    private $genotypingResultG;

    /**
     * @var string|null
     * @ORM\Column(name="genotypingResultGSpecify",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $genotypingResultGSpecify;

    /**
     * @var GenotypeResultP|null
     * @ORM\Column(name="genotypeResultP",type="GenotypeResultP", nullable=true)
     * @Serializer\Groups({"api","export"})
     * @UtilAssert\ArrayChoiceConstraint(groups={"ARF+Completeness","EMR+Completeness","EUR+Completeness","SEAR+Completeness","WPR+Completeness"})
     */
    private $genotypeResultP;

    /**
     * @var string|null
     * @ORM\Column(name="genotypeResultPSpecify",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $genotypeResultPSpecify;

    /**
     * TODO remove once migration to national lab complete
     *
     * RRL_stool_sent
     * @var TripleChoice|null
     * @ORM\Column(name="stoolSentToRRL",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $stoolSentToRRL; // These are duplicated from the boolean fields in the class we extend

    /**
     * TODO remove once migration to national lab complete
     *
     * RRL_stool_date
     * @var DateTime|null
     * @ORM\Column(name="stoolSentToRRLDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    private $stoolSentToRRLDate;

    /**
     * NL_stool_sent
     * @var TripleChoice|null
     * @ORM\Column(name="stoolSentToNL",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    private $stoolSentToNL; // These are duplicated from the boolean fields in the class we extend

    /**
     * NL_stool_date
     * @var DateTime|null
     * @ORM\Column(name="stoolSentToNLDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    private $stoolSentToNLDate;

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

    public function __construct(RotaVirus $case = null)
    {
        if ($case) {
            $this->caseFile = $case;
        }

        $this->updatedAt = new DateTime();
        $this->status    = new CaseStatus(CaseStatus::OPEN);
    }

    /**
     * @return BaseCase|RotaVirus
     */
    public function getCaseFile(): BaseCase
    {
        return $this->caseFile;
    }

    public function setCaseFile(BaseCase $caseFile): void
    {
        $this->caseFile = $caseFile;
    }

    public function getReceived(): ?DateTime
    {
        return $this->received;
    }

    public function getAdequate(): ?TripleChoice
    {
        return $this->adequate;
    }

    public function getStored(): ?TripleChoice
    {
        return $this->stored;
    }

    public function getElisaDone(): ?TripleChoice
    {
        return $this->elisaDone;
    }

    public function getElisaKit(): ?ElisaKit
    {
        return $this->elisaKit;
    }

    public function getElisaKitOther(): ?string
    {
        return $this->elisaKitOther;
    }

    public function getElisaLoadNumber(): ?string
    {
        return $this->elisaLoadNumber;
    }

    public function getElisaExpiryDate(): ?DateTime
    {
        return $this->elisaExpiryDate;
    }

    public function getElisaTestDate(): ?DateTime
    {
        return $this->elisaTestDate;
    }

    public function getElisaResult(): ?ElisaResult
    {
        return $this->elisaResult;
    }

    public function getGenotypingDate(): ?DateTime
    {
        return $this->genotypingDate;
    }

    public function getGenotypingResultg(): ?GenotypeResultG
    {
        return $this->genotypingResultG;
    }

    public function getGenotypingResultGSpecify(): ?string
    {
        return $this->genotypingResultGSpecify;
    }

    public function getGenotypeResultP(): ?GenotypeResultP
    {
        return $this->genotypeResultP;
    }

    public function getGenotypeResultPSpecify(): ?string
    {
        return $this->genotypeResultPSpecify;
    }

    public function getStoolSentToRRL(): ?TripleChoice
    {
        return $this->stoolSentToRRL;
    }

    public function getStoolSentToRRLDate(): ?DateTime
    {
        return $this->stoolSentToRRLDate;
    }

    public function getStoolSentToNL(): ?TripleChoice
    {
        return $this->stoolSentToNL;
    }

    public function getStoolSentToNLDate(): ?DateTime
    {
        return $this->stoolSentToNLDate;
    }

    public function setReceived(DateTime $received = null): void
    {
        $this->received = $received;
    }

    public function setAdequate(?TripleChoice $adequate): void
    {
        $this->adequate = $adequate;
    }

    public function setStored(?TripleChoice $stored): void
    {
        $this->stored = $stored;
    }

    public function setElisaDone(?TripleChoice $elisaDone): void
    {
        $this->elisaDone = $elisaDone;
    }

    public function setElisaKit(?ElisaKit $elisaKit): void
    {
        $this->elisaKit = $elisaKit;
    }

    public function setElisaKitOther(?string $elisaKitOther): void
    {
        $this->elisaKitOther = $elisaKitOther;
    }

    public function setElisaLoadNumber(?string $elisaLoadNumber): void
    {
        $this->elisaLoadNumber = $elisaLoadNumber;
    }

    public function setElisaExpiryDate(?DateTime $elisaExpiryDate = null): void
    {
        $this->elisaExpiryDate = $elisaExpiryDate;
    }

    public function setElisaTestDate(?DateTime $elisaTestDate = null): void
    {
        $this->elisaTestDate = $elisaTestDate;
    }

    public function setElisaResult(?ElisaResult $elisaResult): void
    {
        $this->elisaResult = $elisaResult;
    }

    public function setGenotypingDate(?DateTime $genotypingDate = null): void
    {
        $this->genotypingDate = $genotypingDate;
    }

    public function setGenotypingResultg(?GenotypeResultG $genotypingResultG): void
    {
        $this->genotypingResultG = $genotypingResultG;
    }

    public function setGenotypingResultGSpecify(?string $genotypingResultGSpecify): void
    {
        $this->genotypingResultGSpecify = $genotypingResultGSpecify;
    }

    public function setGenotypeResultP(GenotypeResultP $genotypeResultP): void
    {
        $this->genotypeResultP = $genotypeResultP;
    }

    public function setGenotypeResultPSpecify(?string $genotypeResultPSpecify): void
    {
        $this->genotypeResultPSpecify = $genotypeResultPSpecify;
    }

    public function setStoolSentToRRL(?TripleChoice $stoolSentToRRL): void
    {
        $this->stoolSentToRRL = $stoolSentToRRL;
    }

    public function setStoolSentToRRLDate(?DateTime $stoolSentToRRLDate = null): void
    {
        $this->stoolSentToRRLDate = $stoolSentToRRLDate;
    }

    public function setStoolSentToNL(?TripleChoice $stoolSentToNL): void
    {
        $this->stoolSentToNL = $stoolSentToNL;
    }

    public function setStoolSentToNLDate(?DateTime $stoolSentToNLDate = null): void
    {
        $this->stoolSentToNLDate = $stoolSentToNLDate;
    }

    public function getSentToNationalLab(): bool
    {
        $tripleChoice = $this->getStoolSentToNL();
        return ($tripleChoice && $tripleChoice->equal(TripleChoice::YES));
    }

    public function getSentToReferenceLab(): bool
    {
        $tripleChoice = $this->getStoolSentToRRL();
        return ($tripleChoice && $tripleChoice->equal(TripleChoice::YES));
    }

    public function isComplete(): void
    {
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
}
