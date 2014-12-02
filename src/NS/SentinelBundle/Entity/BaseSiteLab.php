<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of BaseSiteLab
 *
 * @author gnat
 * @ORM\MappedSuperclass
 */
class BaseSiteLab
{
    protected $case;

    /**
     * @var boolean $sentToReferenceLab
     * @ORM\Column(name="sentToReferenceLab",type="boolean")
     */
    protected $sentToReferenceLab = false;

    /**
     * @var boolean $sentToNationalLab
     * @ORM\Column(name="sentToNationalLab",type="boolean")
     */
    protected $sentToNationalLab = false;

    /**
     * Set sentToReferenceLab
     *
     * @param boolean $sentToReferenceLab
     *
     */
    public function setSentToReferenceLab($sentToReferenceLab)
    {
        $this->sentToReferenceLab = $sentToReferenceLab;

        return $this;
    }

    /**
     * Get sentToReferenceLab
     *
     * @return boolean
     */
    public function getSentToReferenceLab()
    {
        return $this->sentToReferenceLab;
    }

    public function getCase()
    {
        return $this->case;
    }

    public function setCase($case)
    {
        $this->case = $case;
        return $this;
    }

    /**
     * Set sentToNationalLab
     *
     * @param boolean $sentToNationalLab
     * @return BaseSiteLab
     */
    public function setSentToNationalLab($sentToNationalLab)
    {
        $this->sentToNationalLab = $sentToNationalLab;

        return $this;
    }

    /**
     * Get sentToNationalLab
     *
     * @return boolean
     */
    public function getSentToNationalLab()
    {
        return $this->sentToNationalLab;
    }
}
