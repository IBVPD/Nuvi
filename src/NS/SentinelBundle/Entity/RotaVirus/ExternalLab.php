<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use Doctrine\ORM\Mapping as ORM;
use \Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
use \NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use \NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use \NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
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
     * @var \DateTime $genotypingDate
     * @ORM\Column(name="dt_gt",type="date", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $dt_gt;

    /**
     * @var GenotypeResultG $genotypingResultg
     * @ORM\Column(name="gt_result_g",type="GenotypeResultG", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $gt_result_g;

    /**
     * @var string $genotypingResultGSpecify
     * @ORM\Column(name="gt_result_g_specify",type="string", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $gt_result_g_specify;

    /**
     * @var GenotypeResultP $genotypeResultP
     * @ORM\Column(name="gt_result_p",type="GenotypeResultP", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $gt_result_p;

    /**
     * @var string $genotypeResultPSpecify
     * @ORM\Column(name="gt_result_p_specify",type="string", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $gt_result_p_specify;

    /**
     * @var ElisaResult $pcrVp6Result
     * @ORM\Column(name="pcr_vp6_result",type="ElisaResult", nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $pcr_vp6_result;

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
        return $this->dt_gt;
    }

    /**
     * @return GenotypeResultG
     */
    public function getGenotypingResultg()
    {
        return $this->gt_result_g;
    }

    /**
     * @return string
     */
    public function getGenotypingResultGSpecify()
    {
        return $this->gt_result_g_specify;
    }

    /**
     * @return GenotypeResultP
     */
    public function getGenotypeResultP()
    {
        return $this->gt_result_p;
    }

    /**
     * @return string
     */
    public function getGenotypeResultPSpecify()
    {
        return $this->gt_result_p_specify;
    }

    /**
     * @return ElisaResult
     */
    public function getPcrVp6Result()
    {
        return $this->pcr_vp6_result;
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
     * @param \DateTime $genotypingDate
     * @return $this
     */
    public function setGenotypingDate(\DateTime $genotypingDate = null)
    {
        $this->dt_gt = $genotypingDate;
        return $this;
    }

    /**
     * @param $genotypingResultg
     * @return $this
     */
    public function setGenotypingResultg($genotypingResultg)
    {
        $this->gt_result_g = $genotypingResultg;
        return $this;
    }

    /**
     * @param $genotypingResultGSpecify
     * @return $this
     */
    public function setGenotypingResultGSpecify($genotypingResultGSpecify)
    {
        $this->gt_result_g_specify = $genotypingResultGSpecify;
        return $this;
    }

    /**
     * @param $genotypeResultP
     * @return $this
     */
    public function setGenotypeResultP($genotypeResultP)
    {
        $this->gt_result_p = $genotypeResultP;
        return $this;
    }

    /**
     * @param $genotypeResultPSpecify
     * @return $this
     */
    public function setGenotypeResultPSpecify($genotypeResultPSpecify)
    {
        $this->gt_result_p_specify = $genotypeResultPSpecify;
        return $this;
    }

    /**
     * @param $pcrVp6Result
     * @return $this
     */
    public function setPcrVp6Result($pcrVp6Result)
    {
        $this->pcr_vp6_result = $pcrVp6Result;
        return $this;
    }

    /**
     * @return array
     */
    public function getMandatoryFields()
    {
        return array(
            'dg_gt',
            'gt_result_g',
            'gt_result_g_specify',
            'gt_result_p',
            'gt_result_p_specify',
            'pcr_vp6_result');
    }
}
