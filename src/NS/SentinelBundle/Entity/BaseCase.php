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
     * @ORM\CustomIdGenerator(class="\NS\SentinelBundle\Generator\Custom")
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

    public function __construct()
    {
        if (!is_string($this->nationalClass) || empty($this->nationalClass))
            throw new \InvalidArgumentException("The NationalLab class is not set");

        if (!is_string($this->referenceClass) || empty($this->referenceClass))
            throw new \InvalidArgumentException("The ReferenceLab class is not set");

        if (!is_string($this->siteLabClass) || empty($this->siteLabClass))
            throw new \InvalidArgumentException("The SiteLab class is not set");

        $this->externalLabs = new ArrayCollection();
        $this->status       = new CaseStatus(CaseStatus::OPEN);
        $this->createdAt    = new \DateTime();
        $this->updatedAt    = new \DateTime();
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function hasId()
    {
        return !empty($this->id);
    }

    public function getFullIdentifier($id)
    {
        if(property_exists($this, 'admDate') && $this->admDate)
            $year = $this->admDate->format('y');
        else if(property_exists($this, 'onsetDate') && $this->onsetDate)
            $year = $this->onsetDate->format('y');
        else
            $year = date('y');

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

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(CaseStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    protected function _findLab($class)
    {
        foreach ($this->externalLabs as $l)
        {
            if ($l instanceof $class)
                return $l;
        }

        return null;
    }

    protected function _findReferenceLab()
    {
        if (is_integer($this->referenceLab) && $this->referenceLab == -1)
            $this->referenceLab = $this->_findLab($this->referenceClass);

        return $this->referenceLab;
    }

    protected function _findNationalLab()
    {
        if (is_integer($this->nationalLab) && $this->nationalLab == -1)
            $this->nationalLab = $this->_findLab($this->nationalClass);

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

    public function setExternalLabs($externalLabs)
    {
        $this->referenceLab = $this->nationalLab  = -1;
        $this->externalLabs->clear();

        foreach ($externalLabs as $externalLab)
            $this->addExternalLab($externalLab);

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

    public function setReferenceClass($referenceClass)
    {
        $this->referenceClass = $referenceClass;
        return $this;
    }

    public function setNationalClass($nationalClass)
    {
        $this->nationalClass = $nationalClass;
        return $this;
    }

    public function hasSiteLab()
    {
        return ($this->siteLab instanceof $this->siteLabClass);
    }

    public function getSiteLab()
    {
        return $this->siteLab;
    }

    public function setSiteLab($siteLab)
    {
        $siteLab->setCaseFile($this);
        $this->siteLab = $siteLab;

        return $this;
    }

    public function isComplete()
    {
        return $this->status->getValue() == CaseStatus::COMPLETE;
    }

    public function calculateStatus()
    {
        if($this->status->getValue() >= CaseStatus::CANCELLED)
            return;

        if($this->getIncompleteField())
            $this->status = new CaseStatus(CaseStatus::OPEN);
        else
            $this->status = new CaseStatus(CaseStatus::COMPLETE);

        return;
    }

    public function calculateAge()
    {
        if($this->dob && $this->admDate)
        {
            $interval = $this->dob->diff($this->admDate);
            $this->setAge(($interval->format('%a') / 30));
        }
        else if($this->admDate && !$this->dob)
        {
            if(!$this->age && !is_null($this->dobYears) && !is_null($this->dobMonths))
                $this->setAge((int)(($this->dobYears*12)+$this->dobMonths));

            if($this->age)
            {
                $d = clone $this->admDate;
                $this->dob = $d->sub(new \DateInterval("P".((int)$this->age)."M"));
            }
        }

        if($this->age >= 0)
        {
            if($this->age <= 5)
                $this->setAgeDistribution (self::AGE_DISTRIBUTION_00_TO_05);
            else if ($this->age <= 11)
                $this->setAgeDistribution (self::AGE_DISTRIBUTION_05_TO_11);
            else if ($this->age <= 23)
                $this->setAgeDistribution (self::AGE_DISTRIBUTION_11_TO_23);
            else if ($this->age <= 59)
                $this->setAgeDistribution (self::AGE_DISTRIBUTION_23_TO_59);
            else
                $this->setAgeDistribution (self::AGE_DISTRIBUTION_UNKNOWN);
        }
        else
            $this->setAgeDistribution (self::AGE_DISTRIBUTION_UNKNOWN);
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

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

    public function getYear()
    {
        return $this->createdAt->format('Y');
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function getAdmDate()
    {
        return $this->admDate;
    }

    public function getCaseId()
    {
        return $this->caseId;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getDobKnown()
    {
        return $this->dobKnown;
    }

    public function getDobYears()
    {
        if(!$this->dobYears && $this->age)
            $this->dobYears = (int)($this->age/12);

        return $this->dobYears;
    }

    public function getDobMonths()
    {
        if(!$this->dobMonths && $this->age)
        {
            $this->getDobYears();
            $this->dobMonths = (int)($this->age-($this->dobYears*12));
        }

        return $this->dobMonths;
    }

    public function setDobKnown(TripleChoice $dobKnown)
    {
        $this->dobKnown = $dobKnown;
        return $this;
    }

    public function setDobYears($dobYears)
    {
        $this->dobYears = $dobYears;

        return $this;
    }

    public function setDobMonths($dobMonths)
    {
        $this->dobMonths = $dobMonths;

        return $this;
    }

    public function setDob($dob)
    {
        if(!$dob instanceOf \DateTime)
            return;

        $this->dob = $dob;

        return $this;
    }

    public function setAdmDate($admDate)
    {
        if(!$admDate instanceOf \DateTime)
            return;

        $this->admDate = $admDate;

        return $this;
    }

    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function setGender(Gender $gender)
    {
        $this->gender = $gender;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getParentalName()
    {
        return $this->parentalName;
    }

    public function setParentalName($parentalName)
    {
        $this->parentalName = $parentalName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getAgeDistribution()
    {
        return $this->ageDistribution;
    }

    public function setAgeDistribution($ageDistribution)
    {
        $this->ageDistribution = $ageDistribution;
        return $this;
    }
}
