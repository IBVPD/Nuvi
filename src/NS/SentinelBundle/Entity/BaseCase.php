<?php

namespace NS\SentinelBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Entity\ValueObjects\YearMonth;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Validators as LocalAssert;
use NS\UtilBundle\Validator\Constraints\ArrayChoiceConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @UniqueEntity(fields={"site","case_id"}, message="The case id already exists for this site!")
 *
 * @LocalAssert\GreaterThanDate(atPath="adm_date",lessThanField="birthdate",greaterThanField="admDate",message="The date of birth is past the date of admission")
 * @LocalAssert\BirthdayOrAge()
 */
abstract class BaseCase
{
    public const
        AGE_DISTRIBUTION_UNKNOWN  = -1,
        AGE_DISTRIBUTION_00_TO_05 = 1,
        AGE_DISTRIBUTION_06_TO_11 = 2,
        AGE_DISTRIBUTION_12_TO_23 = 3,
        AGE_DISTRIBUTION_24_TO_59 = 4;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\NS\SentinelBundle\Entity\Generator\BaseCaseGenerator")
     * @var string $id
     * @ORM\Column(name="id",type="string")
     * @Serializer\Groups({"api","export"})
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(name="lastName",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"AMR"})
     */
    protected $lastName;

    /**
     * @var string|null
     * @ORM\Column(name="parentalName",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $parentalName;

    /**
     * @var string|null
     * @ORM\Column(name="firstName",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"AMR"})
     */
    protected $firstName;

    /**
     * case_ID
     * @var string
     * @ORM\Column(name="case_id",type="string",nullable=false)
     * @Assert\NotBlank(groups={"Default","AMR","Completeness"})
     * @Serializer\Groups({"api","export"})
     */
    protected $case_id;

    /**
     * @var string|null
     * @ORM\Column(name="district",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    protected $district;

    /**
     * @var string|null
     * @ORM\Column(name="state",type="string",nullable=true)
     */
    protected $state;

    /**
     * @var DateTime|null
     * @ORM\Column(name="birthdate",type="date",nullable=true)
     * @Assert\Date
     * @Assert\NotBlank(groups={"Completeness"})
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    protected $birthdate;

    /**
     * @var TripleChoice|null
     * @ORM\Column(name="dobKnown",type="TripleChoice",nullable=true)
     * @Serializer\Groups("export")
     * @Serializer\SerializedName("dobKnown")
     * @ArrayChoiceConstraint(groups={"Completeness"})
     */
    protected $dobKnown;

    /** @var YearMonth */
    protected $dobYearMonths;

    /**
     * @var int|null
     * @ORM\Column(name="age_months",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"Completeness"})
     */
    protected $age_months;

    /**
     * @var int|null
     * @ORM\Column(name="ageDistribution",type="integer",nullable=true)
     */
    protected $ageDistribution;

    /**
     * @var Gender|null
     * @ORM\Column(name="gender",type="Gender",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @ArrayChoiceConstraint(groups={"AMR","Completeness"})
     */
    protected $gender;

    /**
     * @var DateTime|null
     * @ORM\Column(name="adm_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\NotBlank(groups={"AMR","Completeness"})
     * @Assert\Date()
     * @LocalAssert\NoFutureDate()
     */
    protected $adm_date;

    /**
     * @var CaseStatus
     * @ORM\Column(name="status",type="CaseStatus")
     * @Serializer\Groups({"api","export"})
     */
    protected $status;

    /**
     * @var DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Serializer\Groups({"api"})
     * @Serializer\Type(name="DateTime<'Y-m-d H:i:s'>")
     */
    protected $updatedAt;

    /**
     * @var DateTime
     * @ORM\Column(name="createdAt",type="datetime")
     * @Serializer\Groups({"api"})
     * @Serializer\Type(name="DateTime<'Y-m-d H:i:s'>")
     */
    protected $createdAt;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Region")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="code")
     * @Serializer\Groups({"api","export"})
     */
    protected $region;

    /**
     * @var Country
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Country")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="code")
     * @Serializer\Groups({"api","export"})
     */
    protected $country;

    /**
     * @var Site
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Site")
     * @ORM\JoinColumn(nullable=true,referencedColumnName="code")
     * @Serializer\Groups({"api","export"})
     */
    protected $site;

    protected $siteLab;

    /**
     * @var boolean
     * @ORM\Column(name="hasWarning",type="boolean")
     */
    protected $warning = false;

    // TODO evaluate if these could just be nullable??
    /** @var int|BaseExternalLab $referenceLab */
    protected $referenceLab = -1;

    /** @var int|BaseExternalLab $referenceLab */
    protected $nationalLab  = -1;

    /**
     * @Serializer\Exclude()
     */
    protected $siteLabClass   = null;

    /**
     * @Serializer\Exclude()
     */
    protected $referenceClass = null;

    /**
     * @Serializer\Exclude()
     */
    protected $nationalClass  = null;

    public function __construct()
    {
        if (!is_string($this->nationalClass) || empty($this->nationalClass)) {
            throw new InvalidArgumentException('The NationalLab class is not set');
        }

        if (!is_string($this->referenceClass) || empty($this->referenceClass)) {
            throw new InvalidArgumentException('The ReferenceLab class is not set');
        }

        if (!is_string($this->siteLabClass) || empty($this->siteLabClass)) {
            throw new InvalidArgumentException('The SiteLab class is not set');
        }

        $this->status    = new CaseStatus(CaseStatus::OPEN);
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function __clone()
    {
        $this->setId(null);
        if (is_object($this->siteLab)) {
            $this->setSiteLab(clone $this->siteLab);
        }

        if (is_object($this->referenceLab)) {
            $this->setReferenceLab(clone $this->referenceLab);
        }

        if (is_object($this->nationalLab)) {
            $this->setNationalLab(clone $this->nationalLab);
        }
    }

    public function __toString()
    {
        return $this->id ?? '';
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function hasId(): bool
    {
        return !empty($this->id);
    }

    public function setRegion(Region $region = null): void
    {
        $this->region = $region;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setCountry(Country $country = null): void
    {
        $this->country = $country;

        $this->setRegion($country->getRegion());
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setSite(Site $site = null): void
    {
        $this->site = $site;

        $this->setCountry($site->getCountry());
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function getStatus(): CaseStatus
    {
        return $this->status;
    }

    public function setStatus(CaseStatus $status): void
    {
        $this->status = $status;
    }

    public function setReferenceLab(BaseExternalLab $lab): void
    {
        $lab->setCaseFile($this);
        $this->referenceLab = $lab;
    }

    public function setNationalLab(BaseExternalLab $lab): void
    {
        $lab->setCaseFile($this);
        $this->nationalLab = $lab;
    }

    /**
     * @return int|BaseExternalLab
     */
    public function getReferenceLab()
    {
        return $this->referenceLab;
    }

    public function hasReferenceLab(): bool
    {
        return $this->referenceLab instanceof $this->referenceClass;
    }

    /**
     * @return int|BaseExternalLab
     */
    public function getNationalLab()
    {
        return $this->nationalLab;
    }

    public function hasNationalLab(): bool
    {
        return $this->nationalLab !== null;
    }

    public function getSentToReferenceLab(): bool
    {
        if ($this->siteLab && method_exists($this->siteLab,'getSentToReferenceLab') && $this->siteLab->getSentToReferenceLab()) {
            return true;
        }

        return $this->nationalLab && $this->nationalLab->getSentToReferenceLab();
    }

    public function getSentToNationalLab(): bool
    {
        return $this->siteLab ? $this->siteLab->getSentToNationalLab() : false;
    }

    public function hasSiteLab(): bool
    {
        return $this->siteLab instanceof $this->siteLabClass;
    }

    public function getSiteLab(): ?BaseSiteLabInterface
    {
        return $this->siteLab;
    }

    public function setSiteLab(BaseSiteLabInterface $siteLab): void
    {
        $siteLab->setCaseFile($this);
        $this->siteLab = $siteLab;
    }

    public function isComplete(): bool
    {
        return (int)$this->status->getValue() === CaseStatus::COMPLETE;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getYear(): string
    {
        return $this->createdAt->format('Y');
    }

    /**
     * @return DateTime
     */
    public function getDob()
    {
        return $this->birthdate;
    }

    /**
     * @return DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @return DateTime
     */
    public function getAdmDate()
    {
        return $this->adm_date;
    }

    /**
     * @return string
     */
    public function getCaseId()
    {
        return $this->case_id;
    }

    /**
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age_months;
    }

    /**
     * @return integer
     */
    public function getAgeMonths()
    {
        return $this->age_months;
    }

    /**
     *
     * @return Gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     *
     * @return TripleChoice
     */
    public function getDobKnown()
    {
        return $this->dobKnown;
    }

    /**
     *
     * @param TripleChoice $dobKnown
     */
    public function setDobKnown(TripleChoice $dobKnown)
    {
        $this->dobKnown = $dobKnown;
    }

    /**
     * @return YearMonth
     */
    public function getDobYearMonths()
    {
        if ($this->age_months > 0) {
            $this->dobYearMonths = new YearMonth($this->age_months / 12, $this->age_months % 12);
        }

        return $this->dobYearMonths;
    }

    /**
     * @param YearMonth $dobMonthYears
     */
    public function setDobYearMonths(YearMonth $dobMonthYears=null): void
    {
        $this->dobYearMonths = $dobMonthYears;
        if ($dobMonthYears) {
            $this->age_months = $dobMonthYears->getMonths();
        }
    }

    public function setBirthdate(?DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    public function setDob(DateTime $dob = null): void
    {
        $this->birthdate = $dob;
    }

    public function setAdmDate(?DateTime $admDate = null): void
    {
        $this->adm_date = $admDate;
    }

    public function setCaseId(?string $caseId): void
    {
        $this->case_id = $caseId;
    }

    public function setAge(?int $age): void
    {
        $this->age_months = $age;
    }

    public function setAgeMonths(?int $age_months): void
    {
        $this->age_months = $age_months;
    }

    public function setGender(?Gender $gender): void
    {
        $this->gender = $gender;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getParentalName(): ?string
    {
        return $this->parentalName;
    }

    public function setParentalName(?string $parentalName): void
    {
        $this->parentalName = $parentalName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getAgeDistribution(): ?int
    {
        return $this->ageDistribution;
    }

    public function setAgeDistribution(?int $ageDistribution): void
    {
        $this->ageDistribution = $ageDistribution;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setDistrict(?string $district): void
    {
        $this->district = $district;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    public function hasWarning(): bool
    {
        return $this->warning ?? false;
    }

    public function setWarning(bool $warning): void
    {
        $this->warning = $warning;
    }

    public function isUnlinked(): bool
    {
        return (strpos($this->id, '-XXX-') !== false);
    }
}
