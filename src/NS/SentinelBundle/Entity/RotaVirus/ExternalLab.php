<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use Doctrine\ORM\Mapping as ORM;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultG;
use NS\SentinelBundle\Form\RotaVirus\Types\GenotypeResultP;
use NS\SentinelBundle\Form\RotaVirus\Types\ElisaResult;
use NS\SentinelBundle\Entity\BaseExternalLab;
use JMS\Serializer\Annotation as Serializer;
use NS\SentinelBundle\Validators as LocalAssert;

/**
 * Description of ExternalLab
 * @author gnat
 *
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
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    protected $specimenCollectionDate;

    /**
     * @var \DateTime $genotypingDate
     * @ORM\Column(name="dt_gt",type="date", nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    protected $dt_gt;

    /**
     * @var GenotypeResultG $genotypingResultg
     * @ORM\Column(name="gt_result_g",type="GenotypeResultG", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $gt_result_g;

    /**
     * @var string $genotypingResultGSpecify
     * @ORM\Column(name="gt_result_g_specify",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $gt_result_g_specify;

    /**
     * @var GenotypeResultP $genotypeResultP
     * @ORM\Column(name="gt_result_p",type="GenotypeResultP", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $gt_result_p;

    /**
     * @var string $genotypeResultPSpecify
     * @ORM\Column(name="gt_result_p_specify",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $gt_result_p_specify;

    /**
     * @var ElisaResult $pcrVp6Result
     * @ORM\Column(name="pcr_vp6_result",type="ElisaResult", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $pcr_vp6_result;

    /**
     * @return \DateTime|null
     */
    public function getSpecimenCollectionDate()
    {
        return $this->specimenCollectionDate;
    }

    /**
     * @param \DateTime $specimenCollectionDate
     */
    public function setSpecimenCollectionDate($specimenCollectionDate)
    {
        $this->specimenCollectionDate = $specimenCollectionDate;
    }

    /**
     * @return \DateTime|null
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
     * @return string|null
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
        return [
            'dg_gt',
            'gt_result_g',
            'gt_result_g_specify',
            'gt_result_p',
            'gt_result_p_specify',
            'pcr_vp6_result'];
    }

    /**
     * @return \DateTime
     */
    public function getDtGt()
    {
        return $this->dt_gt;
    }

    /**
     * @param \DateTime $dt_gt
     */
    public function setDtGt($dt_gt)
    {
        $this->dt_gt = $dt_gt;
    }

    /**
     * @return GenotypeResultG
     */
    public function getGtResultG()
    {
        return $this->gt_result_g;
    }

    /**
     * @param GenotypeResultG $gt_result_g
     */
    public function setGtResultG($gt_result_g)
    {
        $this->gt_result_g = $gt_result_g;
    }

    /**
     * @return string
     */
    public function getGtResultGSpecify()
    {
        return $this->gt_result_g_specify;
    }

    /**
     * @param string $gt_result_g_specify
     */
    public function setGtResultGSpecify($gt_result_g_specify)
    {
        $this->gt_result_g_specify = $gt_result_g_specify;
    }

    /**
     * @return GenotypeResultP
     */
    public function getGtResultP()
    {
        return $this->gt_result_p;
    }

    /**
     * @param GenotypeResultP $gt_result_p
     */
    public function setGtResultP($gt_result_p)
    {
        $this->gt_result_p = $gt_result_p;
    }

    /**
     * @return string
     */
    public function getGtResultPSpecify()
    {
        return $this->gt_result_p_specify;
    }

    /**
     * @param string $gt_result_p_specify
     */
    public function setGtResultPSpecify($gt_result_p_specify)
    {
        $this->gt_result_p_specify = $gt_result_p_specify;
    }
}
