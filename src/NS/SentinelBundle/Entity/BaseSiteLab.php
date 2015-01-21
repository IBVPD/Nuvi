<?php

namespace NS\SentinelBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \JMS\Serializer\Annotation\Groups;

/**
 * Description of BaseSiteLab
 *
 * @author gnat
 * @ORM\MappedSuperclass
 */
class BaseSiteLab
{
    protected $caseFile;

    /**
     * @var boolean $sentToReferenceLab
     * 
     * @ORM\Column(name="sentToReferenceLab",type="boolean")
     * @Groups({"api"})
     */
    protected $sentToReferenceLab = false;

    /**
     * @var boolean $sentToNationalLab
     * @ORM\Column(name="sentToNationalLab",type="boolean")
     * 
     * @Groups({"api"})
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

    public function getCaseFile()
    {
        return $this->caseFile;
    }

    public function setCaseFile($case)
    {
        $this->caseFile = $case;
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
