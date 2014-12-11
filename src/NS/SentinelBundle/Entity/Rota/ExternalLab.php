<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;

// Annotations
use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use \NS\SentinelBundle\Entity\BaseExternalLab;
use \JMS\Serializer\Annotation as Serializer;

/**
 * Description of ExternalLab
 * @author gnat
 * @ORM\Entity()
 * @ORM\Table(name="rota_external_labs",uniqueConstraints={@ORM\UniqueConstraint(name="site_type_idx",columns={"case_id","discr"})})
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB","ROLE_NL_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr",type="string")
 * @ORM\DiscriminatorMap({"reference" = "ReferenceLab", "national" = "NationalLab"})
 * @Serializer\Discriminator(field = "disc", map = {"reference": "ReferenceLab", "national": "NationalLab"})
 * @SuppressWarnings(PHPMD.LongVariable)
 */
abstract class ExternalLab extends BaseExternalLab
{
    /**
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\RotaVirus",inversedBy="externalLabs")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $case;
    protected $caseClass = "\NS\SentinelBundle\Entity\RotaVirus";

    /**
     * @var \DateTime $dateReceived
     * @ORM\Column(name="dateReceived",type="date",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $dateReceived;

    /**
     * @var \DateTime $genotypingDate
     * @ORM\Column(name="genotypingDate",type="date", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $genotypingDate;

    /**
     * @var GenotypeResultG $genotypingResultg
     * @ORM\Column(name="genotypingResultg",type="GenotypeResultG", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $genotypingResultg;

    /**
     * @var string $genotypingResultGSpecify
     * @ORM\Column(name="genotypingResultGSpecify",type="string", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $genotypingResultGSpecify;

    /**
     * @var GenotypeResultP $genotypeResultP
     * @ORM\Column(name="genotypeResultP",type="GenotypeResultP", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $genotypeResultP;

    /**
     * @var string $genotypeResultPSpecify
     * @ORM\Column(name="genotypeResultPSpecify",type="string", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $genotypeResultPSpecify;

    /**
     * @var ElisaResult $pcrVp6Result
     * @ORM\Column(name="pcrVp6Result",type="ElisaResult", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $pcrVp6Result;

    /**
     * @var \DateTime $genotypeResultSentToCountry
     * @ORM\Column(name="genotypeResultSentToCountry",type="date", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $genotypeResultSentToCountry;

    /**
     * @var \DateTime $genotypeResultSentToWHO
     * @ORM\Column(name="genotypeResultSentToWHO",type="date", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $genotypeResultSentToWHO;

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

    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = $dateReceived;
    }

    public function setGenotypingDate($genotypingDate)
    {
        $this->genotypingDate = $genotypingDate;
        return $this;
    }

    public function setGenotypingResultg($genotypingResultg)
    {
        $this->genotypingResultg = $genotypingResultg;
        return $this;
    }

    public function setGenotypingResultGSpecify($genotypingResultGSpecify)
    {
        $this->genotypingResultGSpecify = $genotypingResultGSpecify;
        return $this;
    }

    public function setGenotypeResultP($genotypeResultP)
    {
        $this->genotypeResultP = $genotypeResultP;
        return $this;
    }

    public function setGenotypeResultPSpecify($genotypeResultPSpecify)
    {
        $this->genotypeResultPSpecify = $genotypeResultPSpecify;
        return $this;
    }

    public function setPcrVp6Result($pcrVp6Result)
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

    public function getMandatoryFields()
    {
        return array(
            'genotypingDate',
            'genotypingResultg',
            'genotypingResultGSpecify',
            'genotypeResultP',
            'genotypeResultPSpecify',
            'pcrVp6Result',
            'genotypeResultSentToCountry',
            'genotypeResultSentToWHO',);
    }
}
