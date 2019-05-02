<?php

namespace NS\SentinelBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Form\Types\CaseStatus;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
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
     * @var DateTime $dateReceived
     * @ORM\Column(name="dt_sample_recd", type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @Assert\NotBlank(groups={"Completeness"})
     */
    protected $dt_sample_recd;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     * @Serializer\Groups({"api","export"})
     */
    protected $status;

    /**
     * @var DateTime $createdAt
     * @ORM\Column(name="createdAt", type="datetime")
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     */
    protected $createdAt;

    /**
     * @var DateTime $updatedAt
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
        $this->createdAt = $this->updatedAt = new DateTime();
    }

    public function getDateReceived(): ?DateTime
    {
        return $this->dt_sample_recd;
    }

    public function setDateReceived(?DateTime $dateReceived = null): void
    {
        $this->dt_sample_recd = $dateReceived;
    }

    public function getDtSampleRecd(): ?DateTime
    {
        return $this->dt_sample_recd;
    }

    public function setDtSampleRecd(?DateTime $dt_sample_recd): void
    {
        $this->dt_sample_recd = $dt_sample_recd;
    }

    public function setCaseFile(?BaseCase $case = null): void
    {
        $this->caseFile = $case;
    }

    public function getCaseFile(): ?BaseCase
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

    public function getLabId(): ?string
    {
        return $this->lab_id;
    }

    public function setLabId(?string $labId): void
    {
        $this->lab_id = $labId;
    }

    public function getStatus(): CaseStatus
    {
        return $this->status;
    }

    public function setStatus(CaseStatus $status): void
    {
        $this->status = $status;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

}
