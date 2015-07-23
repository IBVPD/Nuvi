<?php

namespace NS\SentinelBundle\Entity;

use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Collections\Collection;
use \Doctrine\ORM\Mapping as ORM;
use \JMS\Serializer\Annotation as Serializer;
use \NS\SentinelBundle\Form\Types\CaseStatus;
use \NS\SentinelBundle\Form\Types\Gender;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;
use \Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Description of BaseCase
 *
 * @author gnat
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @UniqueEntity(fields={"site","caseId"}, message="The case id already exists for this site!")
 */
abstract class BaseCase implements IdentityAssignmentInterface
{
    const AGE_DISTRIBUTION_UNKNOWN  = -1;
    const AGE_DISTRIBUTION_00_TO_05 = 1;
    const AGE_DISTRIBUTION_05_TO_11 = 2;
    const AGE_DISTRIBUTION_11_TO_23 = 3;
    const AGE_DISTRIBUTION_23_TO_59 = 4;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\NS\SentinelBundle\Generator\BaseCaseGenerator")
     * @var string $id
     * @ORM\Column(name="id",type="string")
     * @Serializer\Groups({"api"})
     */
    protected $id;

    /**
     * @var string $lastName
     * @ORM\Column(name="lastName",type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $lastName;

    /**
     * @var string $parentalName
     * @ORM\Column(name="parentalName",type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $parentalName;

    /**
     * @var string $firstName
     * @ORM\Column(name="firstName",type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $firstName;

    /**
     * case_ID
     * @var string $caseId
     * @ORM\Column(name="caseId",type="string",nullable=false)
     * @Assert\NotBlank()
     * @Serializer\Groups({"api"})
     */
    protected $caseId;

    /**
     * @var string $district
     * @ORM\Column(name="district",type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $district;

    /**
     * @var string $state
     * @ORM\Column(name="state",type="string",nullable=true)
     */
    protected $state;

    /**
     * @var DateTime $dob
     * @ORM\Column(name="dob",type="date",nullable=true)
     * @Assert\Date
     * @Serializer\Groups({"api"})
     */
    protected $dob;

    /**
     * @var TripleChoice $dobKnown
     * @ORM\Column(name="dobKnown",type="TripleChoice",nullable=true)
     */
    protected $dobKnown;
    protected $dobYears  = null;
    protected $dobMonths = null;

    /**
     * @var integer $age
     * @ORM\Column(name="age",type="integer",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $age;

    /**
     * @var integer $ageDistribution
     * @ORM\Column(name="ageDistribution",type="integer",nullable=true)
     */
    protected $ageDistribution;

    /**
     * @var Gender $gender
     * @ORM\Column(name="gender",type="Gender",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $gender;

    /**
     * @var DateTime $admDate
     * @ORM\Column(name="admDate",type="date",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $admDate;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     * @Serializer\Groups({"api"})
     */
    protected $status;

    /**
     * @var DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Serializer\Groups({"api"})
     */
    protected $updatedAt;

    /**
     * @var DateTime $createdAt
     * @ORM\Column(name="createdAt",type="datetime")
     * @Serializer\Groups({"api"})
     */
    protected $createdAt;

    /**
     * @var Region $region
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Region")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"api"})
     */
    protected $region;

    /**
     * @var Country $country
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"api"})
     */
    protected $country;

    /**
     * @var Site $site
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Site")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="code")
     * @Serializer\Groups({"api"})
     */
    protected $site;

//     * @ORM\OneToMany(targetEntity="BaseLab", mappedBy="caseFile")
    protected $externalLabs;
//     * @ORM\OneToOne(targetEntity="SiteLab", mappedBy="caseFile")
    protected $siteLab;

    /**
     * @Serializer\Exclude()
     */
    protected $referenceLab = -1;

    /**
     * @Serializer\Exclude()
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

        $this->externalLabs = new ArrayCollection();
        $this->status       = new CaseStatus(CaseStatus::OPEN);
        $this->createdAt    = new \DateTime();
        $this->updatedAt    = new \DateTime();
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
     * @param string $id
     * @return string
     */
    public function getFullIdentifier($id)
    {
        if (property_exists($this, 'admDate') && $this->admDate) {
            $year = $this->admDate->format('y');
        }
        elseif (property_exists($this, 'onsetDate') && $this->onsetDate) {
            $year = $this->onsetDate->format('y');
        }
        else {
            $year = date('y');
        }

        return sprintf("%s-%s-%d-%06d", $this->country->getCode(), $this->site->getCode(), $year, $id);
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
     * @param string $class
     * @return BaseExternalLab
     */
    protected function _findLab($class)
    {
        foreach ($this->externalLabs as $lab) {
            if ($lab instanceof $class) {
                return $lab;
            }
        }

        return null;
    }

    /**
     * @return BaseExternalLab
     */
    protected function _findReferenceLab()
    {
        if (is_integer($this->referenceLab) && $this->referenceLab == -1) {
            $this->referenceLab = $this->_findLab($this->referenceClass);
        }

        return $this->referenceLab;
    }

    /**
     * @return BaseExternalLab
     */
    protected function _findNationalLab()
    {
        if (is_integer($this->nationalLab) && $this->nationalLab == -1) {
            $this->nationalLab = $this->_findLab($this->nationalClass);
        }

        return $this->nationalLab;
    }

    /**
     * Add externalLabs
     *
     * @param  $externalLabs
     * @return BaseCase
     */
    public function addExternalLab($externalLabs)
    {
        $externalLabs->setCaseFile($this);
        $this->externalLabs[] = $externalLabs;

        return $this;
    }

    /**
     * Remove externalLabs
     *
     * @param  $externalLabs
     */
    public function removeExternalLab($externalLabs)
    {
        $this->externalLabs->removeElement($externalLabs);
    }

    /**
     * Get externalLabs
     *
     * @return Collection
     */
    public function getExternalLabs()
    {
        return $this->externalLabs;
    }

    /**
     * @param array $externalLabs
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setExternalLabs($externalLabs)
    {
        $this->referenceLab = $this->nationalLab  = -1;
        $this->externalLabs->clear();

        foreach ($externalLabs as $externalLab) {
            $this->addExternalLab($externalLab);
        }

        return $this;
    }

    /**
     * Get ReferenceLab
     *
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function getReferenceLab()
    {
        return $this->_findReferenceLab();
    }

    /**
     * @return boolean
     */
    public function hasReferenceLab()
    {
        $this->_findReferenceLab();

        return ($this->referenceLab instanceof $this->referenceClass);
    }

    /**
     * Get NationalLab
     *
     * @return \NS\SentinelBundle\Entity\NationalLab
     */
    public function getNationalLab()
    {
        return $this->_findNationalLab();
    }

    /**
     *
     * @return boolean
     */
    public function hasNationalLab()
    {
        $this->_findNationalLab();

        return ($this->nationalLab instanceof $this->nationalClass);
    }

    /**
     * Get sentToReferenceLab
     *
     * @return boolean
     */
    public function getSentToReferenceLab()
    {
        return ($this->siteLab) ? $this->siteLab->getSentToReferenceLab() : false;
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
     * @param string $referenceClass
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setReferenceClass($referenceClass)
    {
        $this->referenceClass = $referenceClass;
        return $this;
    }

    /**
     * @param string $nationalClass
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setNationalClass($nationalClass)
    {
        $this->nationalClass = $nationalClass;
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasSiteLab()
    {
        return ($this->siteLab instanceof $this->siteLabClass);
    }

    /**
     * @return BaseSiteLab
     */
    public function getSiteLab()
    {
        return $this->siteLab;
    }

    /**
     * @param \NS\SentinelBundle\Entity\BaseSiteLab $siteLab
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setSiteLab(BaseSiteLab $siteLab)
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
     * @return null
     */
    public function calculateStatus()
    {
        if($this->status->getValue() >= CaseStatus::CANCELLED) {
            return;
        }

        $this->status = ($this->getIncompleteField()) ? new CaseStatus(CaseStatus::OPEN) :new CaseStatus(CaseStatus::COMPLETE);

        return;
    }

    /**
     * 
     */
    public function calculateAge()
    {
        if ($this->dob && $this->admDate) {
            $interval = $this->dob->diff($this->admDate);
            $this->setAge(($interval->format('%a') / 30.5));
        }
        elseif ($this->admDate && !$this->dob) {
            if(!$this->age && !is_null($this->dobYears) && !is_null($this->dobMonths)) {
                $this->setAge((int)(($this->dobYears*12)+$this->dobMonths));
            }

            if ($this->age >= 0) {
                $d         = clone $this->admDate;
                $this->dob = $d->sub(new \DateInterval("P" . ((int) $this->age) . "M"));
            }
        }

        if ($this->age >= 0) {
            if ($this->age < 6) {
                $this->setAgeDistribution(self::AGE_DISTRIBUTION_00_TO_05);
            }
            else if ($this->age < 12) {
                $this->setAgeDistribution(self::AGE_DISTRIBUTION_05_TO_11);
            }
            else if ($this->age < 24) {
                $this->setAgeDistribution(self::AGE_DISTRIBUTION_11_TO_23);
            }
            else if ($this->age < 60) {
                $this->setAgeDistribution(self::AGE_DISTRIBUTION_23_TO_59);
            }
            else {
                $this->setAgeDistribution(self::AGE_DISTRIBUTION_UNKNOWN);
            }
        }
        else {
            $this->setAgeDistribution (self::AGE_DISTRIBUTION_UNKNOWN);
        }
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

    abstract public function getIncompleteField();
    abstract public function getMinimumRequiredFields();
    abstract public function calculateResult();

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpdateAndPersist()
    {
        $this->calculateAge();
        $this->calculateStatus();
        $this->calculateResult();
        $this->setUpdatedAt(new \DateTime());
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
        return $this->dob;
    }

    /**
     * @return \DateTime
     */
    public function getAdmDate()
    {
        return $this->admDate;
    }

    /**
     * @return string
     */
    public function getCaseId()
    {
        return $this->caseId;
    }

    /**
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
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
     * @return integer
     */
    public function getDobYears()
    {
        if(!$this->dobYears && $this->age) {
            $this->dobYears = (int)($this->age/12);
        }

        return $this->dobYears;
    }

    /**
     *
     * @return month
     */
    public function getDobMonths()
    {
        if (!$this->dobMonths && $this->age) {
            $this->getDobYears();
            $this->dobMonths = (int) ($this->age - ($this->dobYears * 12));
        }

        return $this->dobMonths;
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
     *
     * @param integer $dobYears
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setDobYears($dobYears)
    {
        $this->dobYears = $dobYears;

        return $this;
    }

    /**
     *
     * @param integer $dobMonths
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setDobMonths($dobMonths)
    {
        $this->dobMonths = $dobMonths;

        return $this;
    }

    /**
     *
     * @param \DateTime $dob
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setDob(\DateTime $dob = null)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     *
     * @param \DateTime $admDate
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setAdmDate(\DateTime $admDate = null)
    {
        $this->admDate = $admDate;

        return $this;
    }

    /**
     *
     * @param string $caseId
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;

        return $this;
    }

    /**
     *
     * @param integer $age
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function setAge($age)
    {
        $this->age = $age;

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
}
