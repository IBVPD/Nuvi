<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\TripleChoice;

use JMS\Serializer\Annotation\Groups;

/**
 * Description of BaseCase
 *
 * @author gnat
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseCase implements IdentityAssignmentInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\NS\SentinelBundle\Generator\Custom")
     * @var string $id
     * @ORM\Column(name="id",type="string")
     * @Groups({"api"})
     */
    protected $id;

    /**
     * @var string $lastName
     * @ORM\Column(name="lastName",type="string",nullable=true)
     * @Groups({"api"})
     */
    protected $lastName;

    /**
     * @var string $parentalName
     * @ORM\Column(name="parentalName",type="string",nullable=true)
     * @Groups({"api"})
     */
    protected $parentalName;

    /**
     * @var string $firstName
     * @ORM\Column(name="firstName",type="string",nullable=true)
     * @Groups({"api"})
     */
    protected $firstName;

    /**
     * case_ID
     * @var string $caseId
     * @ORM\Column(name="caseId",type="string",nullable=false)
     * @Groups({"api"})
     */
    protected $caseId;

    /**
     * @var DateTime $dob
     * @ORM\Column(name="dob",type="date",nullable=true)
     * @Assert\Date
     * @Groups({"api"})
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
     * @Groups({"api"})
     */
    protected $age;

    /**
     * @var Gender $gender
     * @ORM\Column(name="gender",type="Gender",nullable=true)
     * @Groups({"api"})
     */
    protected $gender;

    /**
     * @var DateTime $admDate
     * @ORM\Column(name="admDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    protected $admDate;

//     * @ORM\OneToMany(targetEntity="BaseLab", mappedBy="case")
    /**
     * @Groups({"api"})
     */
    protected $lab;
    protected $labClass = null;
 
    /**
     * @var Region $region
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\Region")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api"})
     */
    protected $region;

    /**
     * @var Country $country
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api"})
     */
    protected $country;

    /**
     * @var Site $site
     * @ORM\ManyToOne(targetEntity="NS\SentinelBundle\Entity\Site")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="code")
     * @Groups({"api"})
     */
    protected $site;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     * @Groups({"api"})
     */
    protected $status;

    /**
     * @var DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Groups({"api"})
     */
    protected $updatedAt;

    public function __construct()
    {
        if(!is_string($this->labClass) || empty($this->labClass))
            throw new \InvalidArgumentException("The lab class is not set");

        $this->status       = new CaseStatus(CaseStatus::OPEN);
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
        return sprintf("%s-%s-%d-%06d", $this->country->getCode(), $this->site->getCode(), date('y'), $id);
    }

    /**
     * Set region
     *
     * @param \NS\SentinelBundle\Entity\Region $region
     * @return Meningitis
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
     * @return Meningitis
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
     * @return Meningitis
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

    public function getLab()
    {
        return $this->lab;
    }

    public function hasLab()
    {
        if($this->labClass == null)
            throw new \RuntimeException("Lab Class is null");

        return ($this->lab instanceof $this->labClass);
    }

    public function setLab($lab)
    {
        if($this->labClass == null)
            throw new \RuntimeException("Lab Class is null");

        if(!$lab instanceof $this->labClass)
            throw new \InvalidArgumentException(sprintf("Expecting lab of type %s got %s",$this->labClass,get_class($lab)));

        $lab->setCase($this);
        $this->lab = $lab;
        return $this;
    }

    public function getLabClass()
    {
        return $this->labClass;
    }

    public function setLabClass($labClass)
    {
        $this->labClass = $labClass;
        return $this;
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
        else if($this->admDate && !$this->dob);
        {
            if(!$this->age && (!is_null($this->dobYears) || !is_null($this->dobMonths)))
                $this->age = (int)(($this->dobYears*12)+$this->dobMonths);

            if($this->age)
            {
                $d = clone $this->admDate;
                $this->dob = $d->sub(new \DateInterval("P".((int)$this->age)."M"));
            }
        }
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
        return $this->updatedAt->format('Y');
    }

    public function getAgeDistribution()
    {
        if($this->age <= 5)
            return 5;
        else if ($this->age <= 11)
            return 11;
        else if ($this->age <= 23)
            return 23;
        else if ($this->age <= 59)
            return 59;

        return 'unknown';
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
}
