<?php

namespace NS\SentinelBundle\Entity\RotaVirus;

use DateTime;
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
     * @var DateTime|null
     * @ORM\Column(name="specimenCollectionDate",type="date",nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    protected $specimenCollectionDate;

    /**
     * @var DateTime|null
     * @ORM\Column(name="dt_gt",type="date", nullable=true)
     * @Serializer\Groups({"api","export"})
     * @Serializer\Type(name="DateTime<'Y-m-d'>")
     * @LocalAssert\NoFutureDate
     */
    protected $dt_gt;

    /**
     * @var GenotypeResultG|null
     * @ORM\Column(name="gt_result_g",type="GenotypeResultG", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $gt_result_g;

    /**
     * @var string|null
     * @ORM\Column(name="gt_result_g_specify",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $gt_result_g_specify;

    /**
     * @var GenotypeResultP|null
     * @ORM\Column(name="gt_result_p",type="GenotypeResultP", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $gt_result_p;

    /**
     * @var string|null
     * @ORM\Column(name="gt_result_p_specify",type="string", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $gt_result_p_specify;

    /**
     * @var ElisaResult|null
     * @ORM\Column(name="pcr_vp6_result",type="ElisaResult", nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $pcr_vp6_result;

    public function getSpecimenCollectionDate(): ?DateTime
    {
        return $this->specimenCollectionDate;
    }

    public function setSpecimenCollectionDate(?DateTime $specimenCollectionDate): void
    {
        $this->specimenCollectionDate = $specimenCollectionDate;
    }

    public function getGenotypingDate(): ?DateTime
    {
        return $this->dt_gt;
    }

    public function getGenotypingResultg(): ?GenotypeResultG
    {
        return $this->gt_result_g;
    }

    public function getGenotypingResultGSpecify(): ?string
    {
        return $this->gt_result_g_specify;
    }

    public function getGenotypeResultP(): ?GenotypeResultP
    {
        return $this->gt_result_p;
    }

    public function getGenotypeResultPSpecify(): ?string
    {
        return $this->gt_result_p_specify;
    }

    public function getPcrVp6Result(): ?ElisaResult
    {
        return $this->pcr_vp6_result;
    }

    public function setGenotypingDate(?DateTime $genotypingDate = null): void
    {
        $this->dt_gt = $genotypingDate;
    }

    public function setGenotypingResultg(?GenotypeResultG $genotypingResultg): void
    {
        $this->gt_result_g = $genotypingResultg;
    }

    public function setGenotypingResultGSpecify(?string $genotypingResultGSpecify): void
    {
        $this->gt_result_g_specify = $genotypingResultGSpecify;
    }

    public function setGenotypeResultP(?GenotypeResultP $genotypeResultP): void
    {
        $this->gt_result_p = $genotypeResultP;
    }

    public function setGenotypeResultPSpecify(?string $genotypeResultPSpecify): void
    {
        $this->gt_result_p_specify = $genotypeResultPSpecify;
    }

    public function setPcrVp6Result(?ElisaResult $pcrVp6Result): void
    {
        $this->pcr_vp6_result = $pcrVp6Result;
    }

    public function getMandatoryFields(): array
    {
        return [
            'dg_gt',
            'gt_result_g',
            'gt_result_g_specify',
            'gt_result_p',
            'gt_result_p_specify',
            'pcr_vp6_result'];
    }

    public function getDtGt(): ?DateTime
    {
        return $this->dt_gt;
    }

    public function setDtGt(?DateTime $dt_gt): void
    {
        $this->dt_gt = $dt_gt;
    }

    public function getGtResultG(): ?GenotypeResultG
    {
        return $this->gt_result_g;
    }

    public function setGtResultG(?GenotypeResultG $gt_result_g): void
    {
        $this->gt_result_g = $gt_result_g;
    }

    public function getGtResultGSpecify(): ?string
    {
        return $this->gt_result_g_specify;
    }

    public function setGtResultGSpecify(?string $gt_result_g_specify): void
    {
        $this->gt_result_g_specify = $gt_result_g_specify;
    }

    public function getGtResultP(): ?GenotypeResultP
    {
        return $this->gt_result_p;
    }

    public function setGtResultP(?GenotypeResultP $gt_result_p): void
    {
        $this->gt_result_p = $gt_result_p;
    }

    public function getGtResultPSpecify(): ?string
    {
        return $this->gt_result_p_specify;
    }

    public function setGtResultPSpecify(?string $gt_result_p_specify): void
    {
        $this->gt_result_p_specify = $gt_result_p_specify;
    }
}
