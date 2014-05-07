<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of BaseLab
 *
 * @author gnat
 */
class BaseLab
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

//     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis",inversedBy="externalLabs")
//     * @ORM\JoinColumn(nullable=false)
    protected $case;
    protected $caseClass;

    /**
     * @var boolean $isComplete
     * @ORM\Column(name="isComplete",type="boolean")
     */
    protected $isComplete = false;

    public function __construct()
    {
        if(!is_string($this->caseClass) || empty($this->caseClass))
            throw new \InvalidArgumentException("The case class is not set");
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
        return $this->isComplete;
    }

    public function getIsComplete()
    {
        return $this->isComplete;
    }

    public function setIsComplete($value)
    {
        $this->isComplete = (bool)$value;

        return $this;
    }
}
