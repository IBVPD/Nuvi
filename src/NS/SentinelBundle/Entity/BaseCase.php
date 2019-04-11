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
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use NS\SentinelBundle\Validators as LocalAssert;

/**
 * Description of BaseCase
 *
 * @author gnat
 * @ORM\MappedSuperclass
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @UniqueEntity(fields={"site","case_id"}, message="The case id already exists for this site!")
 *
 * @LocalAssert\GreaterThanDate(atPath="adm_date",lessThanField="birthdate",greaterThanField="admDate",message="The date of birth is past the date of admission")
 * @LocalAssert\BirthdayOrAge()
 */
abstract class BaseCase
{
    const AGE_DISTRIBUTION_UNKNOWN  = -1;
    const AGE_DISTRIBUTION_00_TO_05 = 1;
    const AGE_DISTRIBUTION_05_TO_11 = 2;
    const AGE_DISTRIBUTION_11_TO_23 = 3;
    const AGE_DISTRIBUTION_23_TO_59 = 4;

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
     * @Assert\NotBlank()
     * @Serializer\Groups({"api","export"})
     */
    protected $case_id;

    /**
     * @var string|null
     * @ORM\Column(name="district",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
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
     */
    protected $dobKnown;

    /** @var YearMonth */
    protected $dobYearMonths;

    /**
     * @var int|null
     * @ORM\Column(name="age_months",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
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
     * @Assert\NotBlank(groups={"AMR"})
     */
    protected $gender;

    /**
     * @var DateTime|null
     * @ORM\Column(name="adm_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\NotBlank(groups={"AMR"})
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

    /**
     * Constructor
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        if (!is_string($this->nationalClass) || empty($this->nationalClass)) {
            throw new InvalidArgumentException("The NationalLab class is not set");
        }

        if (!is_string($this->referenceClass) || empty($this->referenceClass)) {
            throw new InvalidArgumentException("The ReferenceLab class is not set");
        }

        if (!is_string($this->siteLabClass) || empty($this->siteLabClass)) {
            throw new InvalidArgumentException("The SiteLab class is not set");
        }

        $this->status    = new CaseStatus(CaseStatus::OPEN);
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     *
     */
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
        return (($this->siteLab && method_exists($this->siteLab, 'getSentToReferenceLab') && $this->siteLab->getSentToReferenceLab()) || ($this->nationalLab && $this->nationalLab->getSentToReferenceLab()));
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
        return $this->status->getValue() === CaseStatus::COMPLETE;
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
    public function setDobYearMonths(YearMonth $dobMonthYears=null)
    {
        $this->dobYearMonths = $dobMonthYears;
        if ($dobMonthYears) {
            $this->age_months = $dobMonthYears->getMonths();
        }
    }

    /**
     * @param DateTime $birthdate
     * @return BaseCase
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     * @param DateTime $dob
     */
    public function setDob(DateTime $dob = null)
    {
        $this->birthdate = $dob;
    }

    /**
     * @param DateTime $admDate
     */
    public function setAdmDate(DateTime $admDate = null)
    {
        $this->adm_date = $admDate;
    }

    /**
     * @param string $caseId
     */
    public function setCaseId($caseId)
    {
        $this->case_id = $caseId;
    }

    /**
     * @param integer $age
     */
    public function setAge($age)
    {
        $this->age_months = $age;
    }

    /**
     * @param int $age_months
     */
    public function setAgeMonths($age_months)
    {
        $this->age_months = $age_months;
    }

    /**
     *
     * @param Gender $gender
     */
    public function setGender(Gender $gender)
    {
        $this->gender = $gender;
    }

    /**
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     *
     * @return string
     */
    public function getParentalName()
    {
        return $this->parentalName;
    }

    /**
     * @param string $parentalName
     */
    public function setParentalName($parentalName)
    {
        $this->parentalName = $parentalName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     *
     * @return integer
     */
    public function getAgeDistribution()
    {
        return $this->ageDistribution;
    }

    /**
     *
     * @param integer $ageDistribution
     */
    public function setAgeDistribution($ageDistribution)
    {
        $this->ageDistribution = $ageDistribution;
    }

    /**
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $district
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return boolean
     */
    public function hasWarning()
    {
        return $this->warning;
    }

    /**
     * @param boolean $warning
     * @return BaseCase
     */
    public function setWarning($warning)
    {
        $this->warning = $warning;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUnlinked()
    {
        return (strpos($this->id, '-XXX-') !== false);
    }
}
