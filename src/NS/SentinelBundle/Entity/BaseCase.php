<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @var string $lastName
     * @ORM\Column(name="lastName",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"AMR"})
     */
    protected $lastName;

    /**
     * @var string $parentalName
     * @ORM\Column(name="parentalName",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $parentalName;

    /**
     * @var string $firstName
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
     * @var string $district
     * @ORM\Column(name="district",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $district;

    /**
     * @var string $state
     * @ORM\Column(name="state",type="string",nullable=true)
     */
    protected $state;

    /**
     * @var \DateTime $birthdate
     * @ORM\Column(name="birthdate",type="date",nullable=true)
     * @Assert\Date
     * @LocalAssert\NoFutureDate()
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    protected $birthdate;

    /**
     * @var TripleChoice $dobKnown
     * @ORM\Column(name="dobKnown",type="TripleChoice",nullable=true)
     * @Serializer\Groups("export")
     * @Serializer\SerializedName("dobKnown")
     */
    protected $dobKnown;

    /** @var  YearMonth */
    protected $dobYearMonths;

    /**
     * @var integer $age
     * @ORM\Column(name="age_months",type="integer",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $age_months;

    /**
     * @var integer $ageDistribution
     * @ORM\Column(name="ageDistribution",type="integer",nullable=true)
     */
    protected $ageDistribution;

    /**
     * @var Gender $gender
     * @ORM\Column(name="gender",type="Gender",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Assert\NotBlank(groups={"AMR"})
     */
    protected $gender;

    /**
     * @var \DateTime $admDate
     * @ORM\Column(name="adm_date",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\NotBlank(groups={"AMR"})
     * @Assert\Date()
     * @LocalAssert\NoFutureDate()
     */
    protected $adm_date;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     * @Serializer\Groups({"api","export"})
     */
    protected $status;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Serializer\Groups({"api"})
     * @Serializer\Type(name="DateTime<'Y-m-d H:i:s'>")
     */
    protected $updatedAt;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(name="createdAt",type="datetime")
     * @Serializer\Groups({"api"})
     * @Serializer\Type(name="DateTime<'Y-m-d H:i:s'>")
     */
    protected $createdAt;

    /**
     * @var Region $region
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Region")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="code")
     * @Serializer\Groups({"api","export"})
     */
    protected $region;

    /**
     * @var Country $country
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Country")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="code")
     * @Serializer\Groups({"api","export"})
     */
    protected $country;

    /**
     * @var Site $site
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Site")
     * @ORM\JoinColumn(nullable=true,referencedColumnName="code")
     * @Serializer\Groups({"api","export"})
     */
    protected $site;

    protected $siteLab;

    /**
     * @var boolean $warning
     * @ORM\Column(name="hasWarning",type="boolean")
     */
    protected $warning = false;

    /**
     * @var int|BaseExternalLab $referenceLab
     */
    protected $referenceLab = -1;

    /**
     * @var int|BaseExternalLab $referenceLab
     */
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
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        if (!is_string($this->nationalClass) || empty($this->nationalClass)) {
            throw new \InvalidArgumentException("The NationalLab class is not set");
        }

        if (!is_string($this->referenceClass) || empty($this->referenceClass)) {
            throw new \InvalidArgumentException("The ReferenceLab class is not set");
        }

        if (!is_string($this->siteLabClass) || empty($this->siteLabClass)) {
            throw new \InvalidArgumentException("The SiteLab class is not set");
        }

        $this->status    = new CaseStatus(CaseStatus::OPEN);
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasId()
    {
        return !empty($this->id);
    }

    /**
     * Set region
     *
     * @param \NS\SentinelBundle\Entity\Region $region
     * @return BaseCase
     */
    public function setRegion(Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \NS\SentinelBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set country
     *
     * @param \NS\SentinelBundle\Entity\Country $country
     * @return BaseCase
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        $this->setRegion($country->getRegion());

        return $this;
    }

    /**
     * Get country
     *
     * @return \NS\SentinelBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set site
     *
     * @param \NS\SentinelBundle\Entity\Site $site
     * @return BaseCase
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;

        $this->setCountry($site->getCountry());

        return $this;
    }

    /**
     * Get site
     *
     * @return \NS\SentinelBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
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
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setStatus(CaseStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param BaseExternalLab $lab
     * @return $this
     */
    public function setReferenceLab(BaseExternalLab $lab)
    {
        $lab->setCaseFile($this);
        $this->referenceLab = $lab;

        return $this;
    }

    /**
     * @param BaseExternalLab $lab
     * @return $this
     */
    public function setNationalLab(BaseExternalLab $lab)
    {
        $lab->setCaseFile($this);
        $this->nationalLab = $lab;

        return $this;
    }

    /**
     * Get ReferenceLab
     *
     * @return \NS\SentinelBundle\Entity\BaseExternalLab
     */
    public function getReferenceLab()
    {
        return $this->referenceLab;
    }

    /**
     * @return boolean
     */
    public function hasReferenceLab()
    {
        return ($this->referenceLab instanceof $this->referenceClass);
    }

    /**
     * Get NationalLab
     *
     * @return \NS\SentinelBundle\Entity\BaseExternalLab
     */
    public function getNationalLab()
    {
        return $this->nationalLab;
    }

    /**
     *
     * @return boolean
     */
    public function hasNationalLab()
    {
        return ($this->nationalLab !== null);
    }

    /**
     * Get sentToReferenceLab
     *
     * @return boolean
     */
    public function getSentToReferenceLab()
    {
        return (($this->siteLab && $this->siteLab->getSentToReferenceLab()) || ($this->nationalLab && $this->nationalLab->getSentToReferenceLab()));
    }

    /**
     * Get sentToNationalLab
     *
     * @return boolean
     */
    public function getSentToNationalLab()
    {
        return ($this->siteLab) ? $this->siteLab->getSentToNationalLab() : false;
    }

    /**
     * @return boolean
     */
    public function hasSiteLab()
    {
        return ($this->siteLab instanceof $this->siteLabClass);
    }

    /**
     * @return BaseSiteLabInterface
     */
    public function getSiteLab()
    {
        return $this->siteLab;
    }

    /**
     * @param $siteLab
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setSiteLab($siteLab)
    {
        $siteLab->setCaseFile($this);
        $this->siteLab = $siteLab;
        
        return $this;
    }

    /**
     * @return boolean
     */
    public function isComplete()
    {
        return $this->status->getValue() == CaseStatus::COMPLETE;
    }

    /**
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return integer
     */
    public function getYear()
    {
        return $this->createdAt->format('Y');
    }

    /**
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->birthdate;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @return \DateTime
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
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setDobKnown(TripleChoice $dobKnown)
    {
        $this->dobKnown = $dobKnown;
        return $this;
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
     * @param \DateTime $birthdate
     * @return BaseCase
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    /**
     *
     * @param \DateTime $dob
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setDob(\DateTime $dob = null)
    {
        $this->birthdate = $dob;

        return $this;
    }

    /**
     *
     * @param \DateTime $admDate
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setAdmDate(\DateTime $admDate = null)
    {
        $this->adm_date = $admDate;

        return $this;
    }

    /**
     *
     * @param string $caseId
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setCaseId($caseId)
    {
        $this->case_id = $caseId;

        return $this;
    }

    /**
     *
     * @param integer $age
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setAge($age)
    {
        $this->age_months = $age;

        return $this;
    }

    /**
     * @param int $age_months
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setAgeMonths($age_months)
    {
        $this->age_months = $age_months;
        return $this;
    }

    /**
     *
     * @param Gender $gender
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setGender(Gender $gender)
    {
        $this->gender = $gender;

        return $this;
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
     *
     * @param string $parentalName
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setParentalName($parentalName)
    {
        $this->parentalName = $parentalName;

        return $this;
    }

    /**
     *
     * @param string $lastName
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     *
     * @param string $firstName
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
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
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setAgeDistribution($ageDistribution)
    {
        $this->ageDistribution = $ageDistribution;

        return $this;
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
     *
     * @param string $district
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     *
     * @param string $state
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
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
