<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\CSFAppearance;
use NS\SentinelBundle\Form\Types\CXRResult;
use NS\SentinelBundle\Form\Types\Diagnosis;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\Doses;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\Role;

// Annotations
use Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

/**
 * Description of MeningitisLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\MeningitisLab")
 * @ORM\Table(name="meningitis_labs")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through="case",relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},through="case",relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 */
class MeningitisLab 
{
    /**
     * @ORM\OneToOne(targetEntity="Meningitis",mappedBy="lab")
     */
    private $case;

    /**
     * Set case
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $case
     * @return MeningitisLab
     */
    public function setCase(\NS\SentinelBundle\Entity\Meningitis $case = null)
    {
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
}