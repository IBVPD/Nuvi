<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use NS\SentinelBundle\Form\Types\CaseStatus;

use JMS\Serializer\Annotation\Groups;

/**
 * Description of BaseLab
 *
 * @author gnat
 * @ORM\MappedSuperclass
 * 
 * @author gnat
 */
class BaseLab
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

//     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis",inversedBy="externalLabs")
//     * @ORM\JoinColumn(nullable=false)
    protected $case;
    protected $caseClass;

    /**
     * @var CaseStatus $status
     * @ORM\Column(name="status",type="CaseStatus")
     */
    protected $status;

    /**
     * @var DateTime $updatedAt
     * @ORM\Column(name="updatedAt",type="datetime",nullable=true)
     * @Groups({"api"})
     */
    protected $updatedAt;

    public function __construct()
    {
        if(!is_string($this->caseClass) || empty($this->caseClass))
            throw new \InvalidArgumentException("The case class is not set");

        $this->status    = new CaseStatus(0);
        $this->updatedAt = new \DateTime();
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
    public function setCase($case = null)
    {
        if(!$case instanceof $this->caseClass)
            throw new \InvalidArgumentException("Expected ".$this->caseClass." got ".get_class ($case));

        $this->case = $case;

        return $this;
    }

    /**
     * Get case
     *
     * @return \NS\SentinelBundle\Entity\Meningitis
     */
    public function getCase()
    {
        return $this->case;
    }

    public function hasCase()
    {
        return ($this->case instanceof $this->caseClass);
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
}
