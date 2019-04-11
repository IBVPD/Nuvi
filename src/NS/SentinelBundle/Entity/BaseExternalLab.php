<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Form\Types\CaseStatus;
use JMS\Serializer\Annotation as Serializer;

/**
 * Description of BaseLab
 * @ORM\MappedSuperclass
 * @author gnat
 */
abstract class BaseExternalLab
{
    /** @var BaseCase */
    protected $caseFile;

    /**
     * @var string $lab_id
     * @ORM\Column(name="lab_id",type="string",nullable=true)
     * @Assert\NotBlank
     * @Serializer\Groups({"api","export"})
     */
    protected $lab_id;

    /**
     * @var \DateTime $dateReceived
     * @ORM\Column(name="dt_sample_recd", type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    protected $dt_sample_recd;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     * @Serializer\Groups({"api","export"})
     */
    protected $status;

    /**
     * @var \DateTime $createdAt
     * @ORM\Column(name="createdAt", type="datetime")
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    protected $createdAt;

    /**
     * @var \DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime")
     * @Serializer\Groups({"api","export"})
     */
    protected $updatedAt;

    /**
     * @var string $comment
     * @ORM\Column(name="comment",type="text",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $comment;

    public function __construct()
    {
        $this->status = new CaseStatus(CaseStatus::OPEN);
        $this->createdAt = $this->updatedAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getDateReceived()
    {
        return $this->dt_sample_recd;
    }

    /**
     * @param \DateTime|null $dateReceived
     */
    public function setDateReceived(\DateTime $dateReceived = null)
    {
        $this->dt_sample_recd = $dateReceived;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtSampleRecd()
    {
        return $this->dt_sample_recd;
    }

    /**
     * @param \DateTime $dt_sample_recd
     */
    public function setDtSampleRecd($dt_sample_recd)
    {
        $this->dt_sample_recd = $dt_sample_recd;
    }

    public function setCaseFile(?BaseCase $case = null): void
    {
        $this->caseFile = $case;
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

    public function hasCase(): bool
    {
        return $this->caseFile !== null;
    }

    public function isComplete(): bool
    {
        return $this->status->getValue() === CaseStatus::COMPLETE;
    }

    /**
     * @return string|null
     */
    public function getLabId()
    {
        return $this->lab_id;
    }

    /**
     * @param $labId
     */
    public function setLabId($labId)
    {
        $this->lab_id = $labId;
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
     */
    public function setStatus(CaseStatus $status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function calculateStatus()
    {
        if ($this->status->getValue() >= CaseStatus::CANCELLED) {
            return;
        }

        if ($this->getIncompleteField()) {
            $this->status = new CaseStatus(CaseStatus::OPEN);
        } else {
            $this->status = new CaseStatus(CaseStatus::COMPLETE);
        }
    }

    public function getIncompleteField()
    {
        foreach ($this->getMandatoryFields() as $fieldName) {
            if (!$this->$fieldName) {
                return $fieldName;
            }
        }
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
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
