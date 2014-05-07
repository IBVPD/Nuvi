<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;

// Annotations
use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use \NS\SentinelBundle\Entity\BaseLab;

/**
 * Description of ExternalLab
 * @author gnat
 * @ORM\Entity()
 * @ORM\Table(name="rota_external_labs",uniqueConstraints={@ORM\UniqueConstraint(name="site_type_idx",columns={"case_id","discr"})})
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB","ROLE_NL_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr",type="string")
 * @ORM\DiscriminatorMap({"reference" = "ReferenceLab", "national" = "NationalLab"})
 */
class ExternalLab extends BaseLab
{
    /**
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus",inversedBy="externalLabs")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $case;
    protected $caseClass = "\NS\SentinelBundle\Entity\RotaVirus";

    /**
     * @var EIAResult $eiaResult
     * @ORM\Column(name="eiaResult",type="EIAResult")
     */
    private $eiaResult;

    /**
     * @var \DateTime $genotypingDate
     * @ORM\Column(name="genotypingDate",type="date")
     */
    private $genotypingDate;

    /**
     * @var GenotypeResultG $genotypingResultg
     * @ORM\Column(name="genotypingResultg",type="GenotypeResultG")
     */
    private $genotypingResultg;

    /**
     * @var GenotypeResultGSpecify $genotypingResultGSpecify
     * @ORM\Column(name="genotypingResultGSpecify",type="GenotypeResultGSpecify")
     */
    private $genotypingResultGSpecify;

    /**
     * @var GenotypeResultP $genotypeResultP
     * @ORM\Column(name="genotypeResultP",type="GenotypeResultP")
     */
    private $genotypeResultP;

    /**
     * @var GenotypeResultPSpecify $genotypeResultPSpecify
     * @ORM\Column(name="genotypeResultPSpecify",type="GenotypeResultPSpecify")
     */
    private $genotypeResultPSpecify;

    /**
     * @var PCRVP6Result $pcrVp6Result
     * @ORM\Column(name="pcrVp6Result",type="PCRVP6Result")
     */
    private $pcrVp6Result;

    /**
     * @var \DateTime $genotypeResultSentToCountry
     * @ORM\Column(name="genotypeResultSentToCountry",type="date")
     */
    private $genotypeResultSentToCountry;

    /**
     * @var \DateTime $genotypeResultSentToWHO
     * @ORM\Column(name="genotypeResultSentToWHO",type="date")
     */
    private $genotypeResultSentToWHO;

    public function getEiaResult()
    {
        return $this->eiaResult;
    }

    public function getGenotypingDate()
    {
        return $this->genotypingDate;
    }

    public function getGenotypingResultg()
    {
        return $this->genotypingResultg;
    }

    public function getGenotypingResultGSpecify()
    {
        return $this->genotypingResultGSpecify;
    }

    public function getGenotypeResultP()
    {
        return $this->genotypeResultP;
    }

    public function getGenotypeResultPSpecify()
    {
        return $this->genotypeResultPSpecify;
    }

    public function getPcrVp6Result()
    {
        return $this->pcrVp6Result;
    }

    public function getGenotypeResultSentToCountry()
    {
        return $this->genotypeResultSentToCountry;
    }

    public function getGenotypeResultSentToWHO()
    {
        return $this->genotypeResultSentToWHO;
    }

    public function setEiaResult(EIAResult $eiaResult)
    {
        $this->eiaResult = $eiaResult;
        return $this;
    }

    public function setGenotypingDate($genotypingDate)
    {
        $this->genotypingDate = $genotypingDate;
        return $this;
    }

    public function setGenotypingResultg(GenotypeResultG $genotypingResultg)
    {
        $this->genotypingResultg = $genotypingResultg;
        return $this;
    }

    public function setGenotypingResultGSpecify(GenotypeResultGSpecify $genotypingResultGSpecify)
    {
        $this->genotypingResultGSpecify = $genotypingResultGSpecify;
        return $this;
    }

    public function setGenotypeResultP(GenotypeResultP $genotypeResultP)
    {
        $this->genotypeResultP = $genotypeResultP;
        return $this;
    }

    public function setGenotypeResultPSpecify(GenotypeResultPSpecify $genotypeResultPSpecify)
    {
        $this->genotypeResultPSpecify = $genotypeResultPSpecify;
        return $this;
    }

    public function setPcrVp6Result(PCRVP6Result $pcrVp6Result)
    {
        $this->pcrVp6Result = $pcrVp6Result;
        return $this;
    }

    public function setGenotypeResultSentToCountry($genotypeResultSentToCountry)
    {
        $this->genotypeResultSentToCountry = $genotypeResultSentToCountry;
        return $this;
    }

    public function setGenotypeResultSentToWHO($genotypeResultSentToWHO)
    {
        $this->genotypeResultSentToWHO = $genotypeResultSentToWHO;
        return $this;
    }


}
