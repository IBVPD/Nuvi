<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Form\Types\CaseStatus;

use \JMS\Serializer\Annotation as Serializer;

/**
 * Description of BaseLab
 * @ORM\MappedSuperclass
 * @author gnat
 */
abstract class BaseExternalLab
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     */
    protected $caseFile;

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
     * @var \DateTime $createdAt
     * @ORM\Column(name="createdAt", type="datetime")
     * @Serializer\Groups({"api"})
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Serializer\Groups({"api"})
     */
    protected $updatedAt;

    /**
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     */
    protected $comment;

    /**
     *
     */
    public function __construct()
    {
        $this->status = new CaseStatus(0);
        $this->createdAt = $this->updatedAt = new \DateTime();
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
     * @return $this
     */
    public function setCaseFile($case = null)
    {
        $this->caseFile = $case;

        return $this;
    }

    /**
     * Get case
     *
     * @return \NS\SentinelBundle\Entity\BaseCase
     */
    public function getCaseFile()
    {
        return $this->caseFile;
    }

    /**
     * @return bool
     */
    public function hasCase()
    {
        return ($this->caseFile !== null);
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return $this->status->getValue() == CaseStatus::COMPLETE;
    }

    /**
     * @return bool
     */
    public function getIsComplete()
    {
        return $this->status->getValue() == CaseStatus::COMPLETE;
    }

    /**
     * @return string
     */
    public function getLabId()
    {
        return $this->labId;
    }

    /**
     * @param $labId
     * @return $this
     */
    public function setLabId($labId)
    {
        $this->labId = $labId;
        return $this;
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
     * @return $this
     */
    public function setStatus(CaseStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     *
     */
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

    /**
     *
     */
    public function getIncompleteField()
    {
        foreach ($this->getMandatoryFields() as $fieldName) {
            if (!$this->$fieldName) {
                return $fieldName;
            }
        }

        return;
    }

    /**
     * @return mixed
     */
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

    /**
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     *
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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
     * @return BaseExternalLab
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
