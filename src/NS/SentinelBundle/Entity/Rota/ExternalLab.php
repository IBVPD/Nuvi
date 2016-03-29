<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;
use \Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
use \NS\SentinelBundle\Form\Types\GenotypeResultG;
use \NS\SentinelBundle\Form\Types\GenotypeResultP;
use \NS\SentinelBundle\Form\Types\ElisaResult;
use \NS\SentinelBundle\Entity\BaseExternalLab;
use \JMS\Serializer\Annotation as Serializer;

/**
 * Description of ExternalLab
 * @author gnat
 *
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @SuppressWarnings(PHPMD.LongVariable)
 * @ORM\MappedSuperclass
 */
abstract class ExternalLab extends BaseExternalLab
{
    /**
     * @var \DateTime $specimenCollectionDate
     * @ORM\Column(name="specimenCollectionDate",type="date",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $specimenCollectionDate;

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

    /**
     * @return \DateTime
     */
    public function getSpecimenCollectionDate()
    {
        return $this->specimenCollectionDate;
    }

    /**
     * @param \DateTime $specimenCollectionDate
     * @return ExternalLab
     */
    public function setSpecimenCollectionDate($specimenCollectionDate)
    {
        $this->specimenCollectionDate = $specimenCollectionDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getGenotypingDate()
    {
        return $this->genotypingDate;
    }

    /**
     * @return GenotypeResultG
     */
    public function getGenotypingResultg()
    {
        return $this->genotypingResultg;
    }

    /**
     * @return string
     */
    public function getGenotypingResultGSpecify()
    {
        return $this->genotypingResultGSpecify;
    }

    /**
     * @return GenotypeResultP
     */
    public function getGenotypeResultP()
    {
        return $this->genotypeResultP;
    }

    /**
     * @return string
     */
    public function getGenotypeResultPSpecify()
    {
        return $this->genotypeResultPSpecify;
    }

    /**
     * @return ElisaResult
     */
    public function getPcrVp6Result()
    {
        return $this->pcrVp6Result;
    }

    /**
     * @return \DateTime
     */
    public function getGenotypeResultSentToCountry()
    {
        return $this->genotypeResultSentToCountry;
    }

    /**
     * @return \DateTime
     */
    public function getGenotypeResultSentToWHO()
    {
        return $this->genotypeResultSentToWHO;
    }

    /**
     * @return \DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * @param \DateTime $dateReceived
     */
    public function setDateReceived(\DateTime $dateReceived = null)
    {
        $this->dateReceived = $dateReceived;
    }

    /**
     * @param \DateTime $genotypingDate
     * @return $this
     */
    public function setGenotypingDate(\DateTime $genotypingDate = null)
    {
        $this->genotypingDate = $genotypingDate;
        return $this;
    }

    /**
     * @param $genotypingResultg
     * @return $this
     */
    public function setGenotypingResultg($genotypingResultg)
    {
        $this->genotypingResultg = $genotypingResultg;
        return $this;
    }

    /**
     * @param $genotypingResultGSpecify
     * @return $this
     */
    public function setGenotypingResultGSpecify($genotypingResultGSpecify)
    {
        $this->genotypingResultGSpecify = $genotypingResultGSpecify;
        return $this;
    }

    /**
     * @param $genotypeResultP
     * @return $this
     */
    public function setGenotypeResultP($genotypeResultP)
    {
        $this->genotypeResultP = $genotypeResultP;
        return $this;
    }

    /**
     * @param $genotypeResultPSpecify
     * @return $this
     */
    public function setGenotypeResultPSpecify($genotypeResultPSpecify)
    {
        $this->genotypeResultPSpecify = $genotypeResultPSpecify;
        return $this;
    }

    /**
     * @param $pcrVp6Result
     * @return $this
     */
    public function setPcrVp6Result($pcrVp6Result)
    {
        $this->pcrVp6Result = $pcrVp6Result;
        return $this;
    }

    /**
     * @param $genotypeResultSentToCountry
     * @return $this
     */
    public function setGenotypeResultSentToCountry($genotypeResultSentToCountry)
    {
        $this->genotypeResultSentToCountry = $genotypeResultSentToCountry;
        return $this;
    }

    /**
     * @param $genotypeResultSentToWHO
     * @return $this
     */
    public function setGenotypeResultSentToWHO($genotypeResultSentToWHO)
    {
        $this->genotypeResultSentToWHO = $genotypeResultSentToWHO;
        return $this;
    }

    /**
     * @return array
     */
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
