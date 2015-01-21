<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Form\Types\CaseStatus;

use \JMS\Serializer\Annotation as Serializer;

/**
 * Description of BaseLab
 *
 * @author gnat
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseExternalLab
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

//     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\IBD",inversedBy="externalLabs")
//     * @ORM\JoinColumn(nullable=false)
    protected $caseFile;

    protected $caseClass;

    /**
     * @var string $labId
     * @ORM\Column(name="labId",type="string")
     * @Assert\NotBlank
     * @Serializer\Groups({"api"})
     */
    protected $labId;

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

    public function __construct()
    {
        if(!is_string($this->caseClass) || empty($this->caseClass))
            throw new \InvalidArgumentException("The case class is not set");

        $this->status = new CaseStatus(0);
    }

    public function setLab(\NS\SentinelBundle\Entity\ReferenceLab $lab)
    {

    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set case
     *
     * @param  $case
     * @return MeningitisLab
     */
    public function setCaseFile($case = null)
    {
        if(!$case instanceof $this->caseClass)
            throw new \InvalidArgumentException("Expected ".$this->caseClass." got ".get_class ($case));

        $this->caseFile = $case;

        return $this;
    }

    /**
     * Get case
     *
     * @return \NS\SentinelBundle\Entity\Meningitis
     */
    public function getCaseFile()
    {
        return $this->caseFile;
    }

    public function hasCase()
    {
        return ($this->caseFile instanceof $this->caseClass);
    }

    public function isComplete()
    {
        return $this->status->getValue() == CaseStatus::COMPLETE;
    }

    public function getIsComplete()
    {
        return $this->status->getValue() == CaseStatus::COMPLETE;
    }

    public function getLabId()
    {
        return $this->labId;
    }

    public function setLabId($labId)
    {
        $this->labId = $labId;
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

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
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

    public function getIncompleteField()
    {
        foreach($this->getMandatoryFields() as $fieldName)
        {
            if(!$this->$fieldName)
                return $fieldName;
        }

        return;
    }

    abstract public function getMandatoryFields();

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function preUpdateAndPersist()
    {
        $this->calculateStatus();
        $this->setUpdatedAt(new \DateTime());
    }
}
