<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use Doctrine\ORM\Mapping as ORM;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use NS\SentinelBundle\Entity\BaseCase;
use NS\SentinelBundle\Entity\BaseSiteLabInterface;
use NS\SentinelBundle\Entity\RotaVirus;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaKit;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\SentinelBundle\Form\Types\TripleChoice;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Validators as LocalAssert;
use Symfony\Component\Validator\Constraints as Assert;
use NS\UtilBundle\Validator\Constraints as UtilAssert;

/**
 * Description of RotaVirusSiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirus\SiteLabRepository")
 * @ORM\Table(name="rotavirus_site_labs")
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 *
 * @LocalAssert\GreaterThanDate(lessThanField="caseFile.stoolCollectionDate",greaterThanField="received",message="form.validation.vaccination-after-admission")
 * @LocalAssert\RelatedField(sourceField="elisaDone",sourceValue={"1"},fields={"elisaTestDate","elisaResult"})
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
     * @var \DateTime $received
     * @ORM\Column(name="received",type="datetime",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank()
     * @Assert\Date()
     * @LocalAssert\NoFutureDate
     */
    private $received;

    /**
     * stool_adequate
     * @var TripleChoice $adequate
     * @ORM\Column(name="adequate",type="TripleChoice",nullable=true)
     * @Assert\NotBlank()
     * @Serializer\Groups({"api","export"})
     */
    private $adequate;

    /**
     * @var TripleChoice $stored
     * @ORM\Column(name="stored",type="TripleChoice",nullable=true)
     * @Assert\NotBlank()
     * @UtilAssert\ArrayChoiceConstraint()
     * @Serializer\Groups({"api","export"})
     */
    private $stored;

    /**
     * @var TripleChoice $elisaDone
     * @ORM\Column(name="elisaDone",type="TripleChoice",nullable=true)
     * @Assert\NotBlank()
     * @UtilAssert\ArrayChoiceConstraint()
     * @Serializer\Groups({"api","export"})
     */
    private $elisaDone;

    /**
     * @var ElisaKit $elisaKit
     * @ORM\Column(name="elisaKit",type="ElisaKit",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaKit;

    /**
     * @var string $elisaKitOther
     * @ORM\Column(name="elisaKitOther",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaKitOther;

    /**
     * @var string $elisaLoadNumber
     * @ORM\Column(name="elisaLoadNumber",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaLoadNumber;

    /**
     * @var \DateTime $elisaExpiryDate
     * @ORM\Column(name="elisaExpiryDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    private $elisaExpiryDate;

    /**
     * @var \DateTime $testDate
     * @ORM\Column(name="elisaTestDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    private $elisaTestDate;

    /**
     * @var ElisaResult $elisaResult
     * @ORM\Column(name="elisaResult",type="ElisaResult",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $elisaResult;

    /**
     * @var \DateTime $genotypingDate
     * @ORM\Column(name="genotypingDate",type="date", nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Serializer\SerializedName("genotyping_date")
     * @LocalAssert\NoFutureDate
     */
    private $genotypingDate;

    /**
     * @var GenotypeResultG $genotypingResultG
     * @ORM\Column(name="genotypingResultG",type="GenotypeResultG", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $genotypingResultG;

    /**
     * @var string $genotypingResultGSpecify
     * @ORM\Column(name="genotypingResultGSpecify",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $genotypingResultGSpecify;

    /**
     * @var GenotypeResultP $genotypeResultP
     * @ORM\Column(name="genotypeResultP",type="GenotypeResultP", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $genotypeResultP;

    /**
     * @var string $genotypeResultPSpecify
     * @ORM\Column(name="genotypeResultPSpecify",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $genotypeResultPSpecify;

    /**
     * RRL_stool_sent
     * @var TripleChoice $stoolSentToRRL
     * @ORM\Column(name="stoolSentToRRL",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $stoolSentToRRL; // These are duplicated from the boolean fields in the class we extend

    /**
     * RRL_stool_date
     * @var \DateTime $stoolSentToRRLDate
     * @ORM\Column(name="stoolSentToRRLDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    private $stoolSentToRRLDate;

    /**
     * NL_stool_sent
     * @var TripleChoice $stoolSentToNL
     * @ORM\Column(name="stoolSentToNL",type="TripleChoice",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    private $stoolSentToNL; // These are duplicated from the boolean fields in the class we extend

    /**
     * NL_stool_date
     * @var \DateTime $stoolSentToNLDate
     * @ORM\Column(name="stoolSentToNLDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    private $stoolSentToNLDate;

    /**
     * @param RotaVirus  $case
     */
    public function __construct(RotaVirus $case = null)
    {
        if ($case) {
            $this->caseFile = $case;
        }
    }

    /**
     * @return BaseCase|RotaVirus
     */
    public function getCaseFile()
    {
        return $this->caseFile;
    }

    public function setCaseFile(BaseCase $caseFile)
    {
        $this->caseFile = $caseFile;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime|null
     */
    public function getReceived()
    {
        return $this->received;
    }

    /**
     * @return TripleChoice|null
     */
    public function getAdequate()
    {
        return $this->adequate;
    }

    /**
     * @return TripleChoice|null
     */
    public function getStored()
    {
        return $this->stored;
    }

    /**
     * @return TripleChoice|null
     */
    public function getElisaDone()
    {
        return $this->elisaDone;
    }

    /**
     * @return ElisaKit|null
     */
    public function getElisaKit()
    {
        return $this->elisaKit;
    }

    /**
     * @return string|null
     */
    public function getElisaKitOther()
    {
        return $this->elisaKitOther;
    }

    /**
     * @return string|null
     */
    public function getElisaLoadNumber()
    {
        return $this->elisaLoadNumber;
    }

    /**
     * @return \DateTime|null
     */
    public function getElisaExpiryDate()
    {
        return $this->elisaExpiryDate;
    }

    /**
     * @return \DateTime
     */
    public function getElisaTestDate()
    {
        return $this->elisaTestDate;
    }

    /**
     * @return ElisaResult|null
     */
    public function getElisaResult()
    {
        return $this->elisaResult;
    }

    /**
     * @return \DateTime|null
     */
    public function getGenotypingDate()
    {
        return $this->genotypingDate;
    }

    /**
     * @return GenotypeResultG|null
     */
    public function getGenotypingResultg()
    {
        return $this->genotypingResultG;
    }

    /**
     * @return string|null
     */
    public function getGenotypingResultGSpecify()
    {
        return $this->genotypingResultGSpecify;
    }

    /**
     * @return GenotypeResultP|null
     */
    public function getGenotypeResultP()
    {
        return $this->genotypeResultP;
    }

    /**
     * @return string|null
     */
    public function getGenotypeResultPSpecify()
    {
        return $this->genotypeResultPSpecify;
    }

    /**
     * @return TripleChoice|null
     */
    public function getStoolSentToRRL()
    {
        return $this->stoolSentToRRL;
    }

    /**
     * @return \DateTime|null
     */
    public function getStoolSentToRRLDate()
    {
        return $this->stoolSentToRRLDate;
    }

    /**
     * @return TripleChoice|null
     */
    public function getStoolSentToNL()
    {
        return $this->stoolSentToNL;
    }

    /**
     * @return \DateTime|null
     */
    public function getStoolSentToNLDate()
    {
        return $this->stoolSentToNLDate;
    }

    /**
     * @param \DateTime|null $received
     */
    public function setReceived(\DateTime $received = null)
    {
        $this->received = $received;
    }

    /**
     * @param TripleChoice $adequate
     */
    public function setAdequate(TripleChoice $adequate)
    {
        $this->adequate = $adequate;
    }

    /**
     * @param TripleChoice $stored
     */
    public function setStored(TripleChoice $stored)
    {
        $this->stored = $stored;
    }

    /**
     * @param TripleChoice $elisaDone
     */
    public function setElisaDone(TripleChoice $elisaDone)
    {
        $this->elisaDone = $elisaDone;
    }

    /**
     * @param ElisaKit $elisaKit
     */
    public function setElisaKit(ElisaKit $elisaKit)
    {
        $this->elisaKit = $elisaKit;
    }

    /**
     * @param $elisaKitOther
     */
    public function setElisaKitOther($elisaKitOther)
    {
        $this->elisaKitOther = $elisaKitOther;
    }

    /**
     * @param $elisaLoadNumber
     */
    public function setElisaLoadNumber($elisaLoadNumber)
    {
        $this->elisaLoadNumber = $elisaLoadNumber;
    }

    /**
     * @param \DateTime|null $elisaExpiryDate
     */
    public function setElisaExpiryDate(\DateTime $elisaExpiryDate = null)
    {
        $this->elisaExpiryDate = $elisaExpiryDate;
    }

    /**
     * @param \DateTime|null $elisaTestDate
     */
    public function setElisaTestDate(\DateTime $elisaTestDate = null)
    {
        $this->elisaTestDate = $elisaTestDate;
    }

    /**
     * @param ElisaResult $elisaResult
     */
    public function setElisaResult(ElisaResult $elisaResult)
    {
        $this->elisaResult = $elisaResult;
    }

    /**
     * @param \DateTime|null $genotypingDate
     */
    public function setGenotypingDate(\DateTime $genotypingDate = null)
    {
        $this->genotypingDate = $genotypingDate;
    }

    /**
     * @param GenotypeResultG $genotypingResultG
     */
    public function setGenotypingResultg(GenotypeResultG $genotypingResultG)
    {
        $this->genotypingResultG = $genotypingResultG;
    }

    /**
     * @param $genotypingResultGSpecify
     */
    public function setGenotypingResultGSpecify($genotypingResultGSpecify)
    {
        $this->genotypingResultGSpecify = $genotypingResultGSpecify;
    }

    /**
     * @param GenotypeResultP $genotypeResultP
     */
    public function setGenotypeResultP(GenotypeResultP $genotypeResultP)
    {
        $this->genotypeResultP = $genotypeResultP;
    }

    /**
     * @param $genotypeResultPSpecify
     */
    public function setGenotypeResultPSpecify($genotypeResultPSpecify)
    {
        $this->genotypeResultPSpecify = $genotypeResultPSpecify;
    }

    /**
     * @param TripleChoice $stoolSentToRRL
     */
    public function setStoolSentToRRL(TripleChoice $stoolSentToRRL)
    {
        $this->stoolSentToRRL = $stoolSentToRRL;
    }

    /**
     * @param \DateTime|null $stoolSentToRRLDate
     */
    public function setStoolSentToRRLDate(\DateTime $stoolSentToRRLDate = null)
    {
        $this->stoolSentToRRLDate = $stoolSentToRRLDate;
    }

    /**
     * @param TripleChoice $stoolSentToNL
     */
    public function setStoolSentToNL(TripleChoice $stoolSentToNL)
    {
        $this->stoolSentToNL = $stoolSentToNL;
    }

    /**
     * @param \DateTime|null $stoolSentToNLDate
     */
    public function setStoolSentToNLDate(\DateTime $stoolSentToNLDate = null)
    {
        $this->stoolSentToNLDate = $stoolSentToNLDate;
    }

    /**
     * @inheritDoc
     */
    public function getSentToNationalLab(): bool
    {
        $tripleChoice = $this->getStoolSentToNL();
        return ($tripleChoice && $tripleChoice->equal(TripleChoice::YES));
    }

    /**
     * @inheritDoc
     */
    public function getSentToReferenceLab(): bool
    {
        $tripleChoice = $this->getStoolSentToRRL();
        return ($tripleChoice && $tripleChoice->equal(TripleChoice::YES));
    }

    /**
     *
     */
    public function isComplete()
    {
    }
}
