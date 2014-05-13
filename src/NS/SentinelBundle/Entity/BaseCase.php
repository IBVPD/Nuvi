<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Interfaces\IdentityAssignmentInterface;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Form\Types\Gender;

/**
 * Description of BaseCase
 *
 * @author gnat
 * @ORM\MappedSuperclass
 */
abstract class BaseCase implements IdentityAssignmentInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\NS\SentinelBundle\Generator\Custom")
     * @var string $id
     * @ORM\Column(name="id",type="string")
     */
    protected $id;

    /**
     * case_ID
     * @var string $caseId
     * @ORM\Column(name="caseId",type="string",nullable=false)
     */
    protected $caseId;

    /**
     * @var DateTime $dob
     * @ORM\Column(name="dob",type="date",nullable=true)
     * @Assert\Date
     */
    protected $dob;

    /**
     * @var integer $age
     * @ORM\Column(name="age",type="integer",nullable=true)
     * @Assert\Range(min=0,max=59,minMessage="Children should older than 0 months",maxMessage="Children should be younger than 59 months to be tracked")
     */
    protected $age;

    /**
     * @var Gender $gender
     * @ORM\Column(name="gender",type="Gender",nullable=true)
     */
    protected $gender;

    /**
     * @var DateTime $admDate
     * @ORM\Column(name="admDate",type="date",nullable=true)
     */
    protected $admDate;

//     * @ORM\OneToMany(targetEntity="BaseLab", mappedBy="case")
    protected $externalLabs;

//     * @ORM\OneToOne(targetEntity="SiteLab", mappedBy="case")
    protected $siteLab;

    protected $referenceLab     = -1;
    protected $nationalLab      = -1;
    protected $siteLabClass     = null;
    protected $referenceClass   = null;
    protected $nationalClass    = null;
 
    /**
     * @var Region $region
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $region;

    /**
     * @var Country $country
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $country;

    /**
     * @var Site $site
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $site;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     */
    protected $status;

    /**
     * @var DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     */
    protected $updatedAt;

    public function __construct()
    {
        if(!is_string($this->nationalClass) || empty($this->nationalClass))
            throw new \InvalidArgumentException("The NationalLab class is not set");

        if(!is_string($this->referenceClass) || empty($this->referenceClass))
            throw new \InvalidArgumentException("The ReferenceLab class is not set");

        if(!is_string($this->siteLabClass) || empty($this->siteLabClass))
            throw new \InvalidArgumentException("The SiteLab class is not set");

        $this->externalLabs = new ArrayCollection();
        $this->status       = new CaseStatus(0);
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

    protected function _findLab($class)
    {
        foreach($this->externalLabs as $l)
        {
            if($l instanceof $class)
                return $l;
        }

        return null;
    }

    protected function _findReferenceLab()
    {
        if(is_integer($this->referenceLab) && $this->referenceLab == -1)
            $this->referenceLab = $this->_findLab($this->referenceClass);

        return $this->referenceLab;
    }

    protected function _findNationalLab()
    {
        if(is_integer($this->nationalLab) && $this->nationalLab == -1)
            $this->nationalLab = $this->_findLab($this->nationalClass);

        return $this->nationalLab;
    }

    /**
     * Add externalLabs
     *
     * @param  $externalLabs
     * @return Meningitis
     */
    public function addExternalLab($externalLabs)
    {
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExternalLabs()
    {
        return $this->externalLabs;
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
        return ($this->siteLab) ? $this->siteLab->getSentToReferenceLab(): false;
    }

    /**
     * Get sentToNationalLab
     *
     * @return boolean
     */
    public function getSentToNationalLab()
    {
        return ($this->siteLab) ? $this->siteLab->getSentToNationalLab(): false;
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

    public function getSiteLab()
    {
        return $this->siteLab;
    }

    public function setSiteLab($lab)
    {
        $lab->setCase($this);
        $this->siteLab = $lab;
        return $this;
    }

    public function hasSiteLab()
    {
        return ($this->siteLab instanceof $this->siteLabClass);
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

    public function setDob($dob)
    {
        if(!$dob instanceOf \DateTime)
            return;

        $this->dob = $dob;

        $interval = ($this->admDate) ? $dob->diff($this->admDate) : $dob->diff(new \DateTime());
        $this->setAge(($interval->format('%a') / 30));

        return $this;
    }

    public function setAdmDate($admDate)
    {
        $this->admDate = $admDate;

        if (($this->admDate && $this->dob))
        {
            $interval = $this->dob->diff($this->admDate);
            $this->setAge(($interval->format('%a') / 30));
        }

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
}
