<?php

namespace NS\SentinelBundle\Entity\Pneumonia;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
use NS\SentinelBundle\Entity\BaseExternalLab;
use NS\SentinelBundle\Form\IBD\Types\FinalResult;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\IsolateType;
use NS\SentinelBundle\Form\IBD\Types\IsolateViable;
use NS\SentinelBundle\Form\IBD\Types\NmSerogroup;
use NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SampleType;
use NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @var SampleType|null
     * @ORM\Column(name="type_sample_recd",type="SampleType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $type_sample_recd;

    /**
     * @var IsolateViable|null
     * @ORM\Column(name="isolate_viable",type="IsolateViable",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $isolate_viable;

    /**
     * @var IsolateType|null
     * @ORM\Column(name="isolate_type",type="IsolateType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $isolate_type;

    /**
     * @var PathogenIdentifier|null
     * @ORM\Column(name="method_used_pathogen_identify",type="PathogenIdentifier",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $method_used_pathogen_identify;

    /**
     * @var string|null
     * @ORM\Column(name="method_used_pathogen_identify_other", type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $method_used_pathogen_identify_other;

    /**
     * @var SerotypeIdentifier|null
     * @ORM\Column(name="method_used_st_sg",type="SerotypeIdentifier",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $method_used_st_sg;

    /**
     * @var string|null
     * @ORM\Column(name="method_used_st_sg_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $method_used_st_sg_other;

    /**
     * @var double|null
     * @ORM\Column(name="Spn_lytA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $spn_lytA;

    /**
     * @var double|null
     * @ORM\Column(name="Nm_ctrA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $nm_ctrA;

    /**
     * @var double|null
     * @ORM\Column(name="nm_sodC",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $nm_sodC;

    /**
     * @var double|null
     * @ORM\Column(name="hi_hpd1",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_hpd1;

    /**
     * @var double|null
     * @ORM\Column(name="hi_hpd3",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_hpd3;

    /**
     * @var double|null
     * @ORM\Column(name="hi_bexA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_bexA;

    /**
     * @var double|null
     * @ORM\Column(name="humanDNA_RNAseP",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $humanDNA_RNAseP;

    /**
     * @var FinalResult|null
     * @ORM\Column(name="final_RL_result_detection",type="FinalResult",nullable=true)
     */
    protected $final_RL_result_detection;

    /**
     * @var SpnSerotype|null
     * @ORM\Column(name="spn_serotype",type="SpnSerotype",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $spn_serotype;

    /**
     * @var HiSerotype|null
     * @ORM\Column(name="hi_serotype",type="HiSerotype",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_serotype;

    /**
     * @var NmSerogroup|null
     * @ORM\Column(name="nm_serogroup",type="NmSerogroup",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $nm_serogroup;

    public function getSampleType(): ?SampleType
    {
        return $this->type_sample_recd;
    }

    public function getIsolateViable(): ?IsolateViable
    {
        return $this->isolate_viable;
    }

    public function getIsolateType(): ?IsolateType
    {
        return $this->isolate_type;
    }

    public function getPathogenIdentifierMethod(): ?PathogenIdentifier
    {
        return $this->method_used_pathogen_identify;
    }

    public function getPathogenIdentifierOther(): ?string
    {
        return $this->method_used_pathogen_identify_other;
    }

    public function getSerotypeIdentifier(): ?SerotypeIdentifier
    {
        return $this->method_used_st_sg;
    }

    public function getSerotypeIdentifierOther(): ?string
    {
        return $this->method_used_st_sg_other;
    }

    public function getLytA(): ?float
    {
        return $this->spn_lytA;
    }

    public function getCtrA(): ?float
    {
        return $this->nm_ctrA;
    }

    public function getSodC(): ?float
    {
        return $this->nm_sodC;
    }

    public function getHpd1(): ?float
    {
        return $this->hi_hpd1;
    }

    public function getHpd3(): ?float
    {
        return $this->hi_hpd3;
    }

    public function getBexA(): ?float
    {
        return $this->hi_bexA;
    }

    public function getRNaseP(): ?float
    {
        return $this->humanDNA_RNAseP;
    }

    public function getSpnSerotype(): ?SpnSerotype
    {
        return $this->spn_serotype;
    }

    public function getHiSerotype(): ?HiSerotype
    {
        return $this->hi_serotype;
    }

    public function getNmSerogroup(): ?NmSerogroup
    {
        return $this->nm_serogroup;
    }

    public function setSampleType(?SampleType $sampleType): void
    {
        $this->type_sample_recd = $sampleType;
    }

    public function setIsolateViable(?IsolateViable $isolateViable): void
    {
        $this->isolate_viable = $isolateViable;
    }

    public function setIsolateType(?IsolateType $isolateType): void
    {
        $this->isolate_type = $isolateType;
    }

    public function setPathogenIdentifierMethod(?PathogenIdentifier $pathogenIdentifierMethod): void
    {
        $this->method_used_pathogen_identify = $pathogenIdentifierMethod;
    }

    public function setPathogenIdentifierOther(?string $pathogenIdentifierOther): void
    {
        $this->method_used_pathogen_identify_other = $pathogenIdentifierOther;
    }

    public function setSerotypeIdentifier(?SerotypeIdentifier $serotypeIdentifier): void
    {
        $this->method_used_st_sg = $serotypeIdentifier;
    }

    public function setSerotypeIdentifierOther(?string $serotypeIdentifierOther): void
    {
        $this->method_used_st_sg_other = $serotypeIdentifierOther;
    }

    public function setLytA(?float $lytA): void
    {
        $this->spn_lytA = $lytA;
    }

    public function setCtrA(?float $ctrA): void
    {
        $this->nm_ctrA = $ctrA;
    }

    public function setSodC(?float $sodC): void
    {
        $this->nm_sodC = $sodC;
    }

    public function setHpd1(?float $hpd1): void
    {
        $this->hi_hpd1 = $hpd1;
    }

    public function setHpd3(?float $hpd3): void
    {
        $this->hi_hpd3 = $hpd3;
    }

    public function setBexA(?float $bexA): void
    {
        $this->hi_bexA = $bexA;
    }

    public function setRNaseP(?float $rNaseP): void
    {
        $this->humanDNA_RNAseP = $rNaseP;
    }

    /**
     * @param SpnSerotype $spnSerotype
     */
    public function setSpnSerotype(SpnSerotype $spnSerotype): void
    {
        $this->spn_serotype = $spnSerotype;
    }

    public function setHiSerotype(?HiSerotype $hiSerotype): void
    {
        $this->hi_serotype = $hiSerotype;
    }

    public function setNmSerogroup(?NmSerogroup $nmSerogroup): void
    {
        $this->nm_serogroup = $nmSerogroup;
    }

    public function getTypeSampleRecd(): ?SampleType
    {
        return $this->type_sample_recd;
    }

    public function setTypeSampleRecd(?SampleType $type_sample_recd): void
    {
        $this->type_sample_recd = $type_sample_recd;
    }

    public function getMethodUsedPathogenIdentify(): ?PathogenIdentifier
    {
        return $this->method_used_pathogen_identify;
    }

    public function setMethodUsedPathogenIdentify(?PathogenIdentifier $method_used_pathogen_identify): void
    {
        $this->method_used_pathogen_identify = $method_used_pathogen_identify;
    }

    public function getMethodUsedPathogenIdentifyOther(): ?string
    {
        return $this->method_used_pathogen_identify_other;
    }

    public function setMethodUsedPathogenIdentifyOther(?string $method_used_pathogen_identify_other): void
    {
        $this->method_used_pathogen_identify_other = $method_used_pathogen_identify_other;
    }

    public function getMethodUsedStSg(): ?SerotypeIdentifier
    {
        return $this->method_used_st_sg;
    }

    public function setMethodUsedStSg(?SerotypeIdentifier $method_used_st_sg): void
    {
        $this->method_used_st_sg = $method_used_st_sg;
    }

    public function getMethodUsedStSgOther(): ?string
    {
        return $this->method_used_st_sg_other;
    }

    public function setMethodUsedStSgOther(?string $method_used_st_sg_other): void
    {
        $this->method_used_st_sg_other = $method_used_st_sg_other;
    }

    public function getSpnLytA(): ?float
    {
        return $this->spn_lytA;
    }

    public function setSpnLytA(?float $spn_lytA): void
    {
        $this->spn_lytA = $spn_lytA;
    }

    public function getNmCtrA(): ?float
    {
        return $this->nm_ctrA;
    }

    public function setNmCtrA(?float $nm_ctrA): void
    {
        $this->nm_ctrA = $nm_ctrA;
    }

    public function getNmSodC(): ?float
    {
        return $this->nm_sodC;
    }

    public function setNmSodC(?float $nm_sodC): void
    {
        $this->nm_sodC = $nm_sodC;
    }

    public function getHiHpd1(): ?float
    {
        return $this->hi_hpd1;
    }

    public function setHiHpd1(?float $hi_hpd1): void
    {
        $this->hi_hpd1 = $hi_hpd1;
    }

    public function getHiHpd3(): ?float
    {
        return $this->hi_hpd3;
    }

    public function setHiHpd3(?float $hi_hpd3): void
    {
        $this->hi_hpd3 = $hi_hpd3;
    }

    public function getHiBexA(): ?float
    {
        return $this->hi_bexA;
    }

    public function setHiBexA(?float $hi_bexA): void
    {
        $this->hi_bexA = $hi_bexA;
    }

    public function getHumanDNARNAseP(): ?float
    {
        return $this->humanDNA_RNAseP;
    }

    public function setHumanDNARNAseP(?float $humanDNA_RNAseP): void
    {
        $this->humanDNA_RNAseP = $humanDNA_RNAseP;
    }

    public function getFinalRLResultDetection(): ?FinalResult
    {
        return $this->final_RL_result_detection;
    }

    public function setFinalRLResultDetection(?FinalResult $final_RL_result_detection): void
    {
        $this->final_RL_result_detection = $final_RL_result_detection;
    }

    public function getMandatoryFields(): array
    {
        return [
            'type_sample_recd',
            'dt_sample_recd',
            'isolate_viable',
            'isolate_type',
            'method_used_pathogen_identify',
            'method_used_st_sg',
            'spn_serotype',
            'hi_serotype',
            'nm_serogroup',
        ];
    }

    /**
     * @param ExecutionContextInterface $context
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context): void
    {
        // if pathogenIdentifierMethod is other, enforce value in 'pathogenIdentifierMethod other' field
        if ($this->method_used_pathogen_identify && $this->method_used_pathogen_identify->equal(PathogenIdentifier::OTHER) && empty($this->method_used_pathogen_identify_other)) {
            $context->buildViolation('form.validation.pathogenIdentifierMethod-other-without-other-text')->atPath('pathogenIdentifierMethod')->addViolation();
        }

        // if serotypeIdentifier is other, enforce value in 'serotypeIdentifier other' field
        if ($this->method_used_st_sg && $this->method_used_st_sg->equal(SerotypeIdentifier::OTHER) && empty($this->method_used_st_sg_other)) {
            $context->buildViolation('form.validation.serotypeIdentifier-other-without-other-text')->atPath('serotypeIdentifier')->addViolation();
        }
    }

    public function getIncompleteField(): ?string
    {
        $ret = parent::getIncompleteField();
        if ($ret) {
            return $ret;
        }

        if ($this->method_used_pathogen_identify && $this->method_used_pathogen_identify->equal(PathogenIdentifier::OTHER) && empty($this->method_used_pathogen_identify_other)) {
            return 'pathogenIdentifier';
        }

        if ($this->method_used_st_sg && $this->method_used_st_sg->equal(SerotypeIdentifier::OTHER) && empty($this->method_used_st_sg_other)) {
            return 'serotypeIdentifier';
        }

        return null;
    }

    public function getFinalResult(): ?FinalResult
    {
        return $this->final_RL_result_detection;
    }

    public function setFinalResult(?FinalResult $finalResult): void
    {
        $this->final_RL_result_detection = $finalResult;
    }
}
