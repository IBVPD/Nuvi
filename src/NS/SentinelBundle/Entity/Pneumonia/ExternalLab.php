<?php

namespace NS\SentinelBundle\Entity\Pneumonia;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\BaseExternalLab;
use NS\SentinelBundle\Form\IBD\Types\IsolateViable;
use NS\SentinelBundle\Form\IBD\Types\FinalResult;
use NS\SentinelBundle\Form\IBD\Types\HiSerotype;
use NS\SentinelBundle\Form\IBD\Types\IsolateType;
use NS\SentinelBundle\Form\IBD\Types\NmSerogroup;
use NS\SentinelBundle\Form\IBD\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SampleType;
use NS\SentinelBundle\Form\IBD\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\IBD\Types\SpnSerotype;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;
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
     * @var SampleType
     * @ORM\Column(name="type_sample_recd",type="SampleType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $type_sample_recd;

    /**
     * @var IsolateViable
     * @ORM\Column(name="isolate_viable",type="IsolateViable",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $isolate_viable;

    /**
     * @var IsolateType
     * @ORM\Column(name="isolate_type",type="IsolateType",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $isolate_type;

    /**
     * @var PathogenIdentifier
     * @ORM\Column(name="method_used_pathogen_identify",type="PathogenIdentifier",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $method_used_pathogen_identify;

    /**
     * @var string
     * @ORM\Column(name="method_used_pathogen_identify_other", type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $method_used_pathogen_identify_other;

    /**
     * @var SerotypeIdentifier
     * @ORM\Column(name="method_used_st_sg",type="SerotypeIdentifier",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $method_used_st_sg;

    /**
     * @var string
     * @ORM\Column(name="method_used_st_sg_other",type="string",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $method_used_st_sg_other;

    /**
     * @var double
     * @ORM\Column(name="Spn_lytA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $spn_lytA;

    /**
     * @var integer $ctrA
     * @ORM\Column(name="Nm_ctrA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $nm_ctrA;

    /**
     * @var double
     * @ORM\Column(name="nm_sodC",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $nm_sodC;

    /**
     * @var double
     * @ORM\Column(name="hi_hpd1",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_hpd1;

    /**
     * @var double
     * @ORM\Column(name="hi_hpd3",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_hpd3;

    /**
     * @var double
     * @ORM\Column(name="hi_bexA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_bexA;

    /**
     * @var double
     * @ORM\Column(name="humanDNA_RNAseP",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api","export"})
     */
    protected $humanDNA_RNAseP;

    /**
     * @var FinalResult $finalResult
     * @ORM\Column(name="final_RL_result_detection",type="FinalResult",nullable=true)
     */
    protected $final_RL_result_detection;

    /**
     * @var double
     * @ORM\Column(name="spn_serotype",type="SpnSerotype",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $spn_serotype;

    /**
     * @var double
     * @ORM\Column(name="hi_serotype",type="HiSerotype",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_serotype;

    /**
     * @var double
     * @ORM\Column(name="nm_serogroup",type="NmSerogroup",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $nm_serogroup;


    /**
     * @return SampleType
     */
    public function getSampleType()
    {
        return $this->type_sample_recd;
    }

    /**
     * @return IsolateViable
     */
    public function getIsolateViable()
    {
        return $this->isolate_viable;
    }

    /**
     * @return IsolateType
     */
    public function getIsolateType()
    {
        return $this->isolate_type;
    }

    /**
     * @return PathogenIdentifier
     */
    public function getPathogenIdentifierMethod()
    {
        return $this->method_used_pathogen_identify;
    }

    /**
     * @return string
     */
    public function getPathogenIdentifierOther()
    {
        return $this->method_used_pathogen_identify_other;
    }

    /**
     * @return SerotypeIdentifier
     */
    public function getSerotypeIdentifier()
    {
        return $this->method_used_st_sg;
    }

    /**
     * @return string
     */
    public function getSerotypeIdentifierOther()
    {
        return $this->method_used_st_sg_other;
    }

    /**
     * @return float
     */
    public function getLytA()
    {
        return $this->spn_lytA;
    }

    /**
     * @return int
     */
    public function getCtrA()
    {
        return $this->nm_ctrA;
    }

    /**
     * @return float
     */
    public function getSodC()
    {
        return $this->nm_sodC;
    }

    /**
     * @return float
     */
    public function getHpd1()
    {
        return $this->hi_hpd1;
    }

    /**
     * @return float
     */
    public function getHpd3()
    {
        return $this->hi_hpd3;
    }

    /**
     * @return float
     */
    public function getBexA()
    {
        return $this->hi_bexA;
    }

    /**
     * @return float
     */
    public function getRNaseP()
    {
        return $this->humanDNA_RNAseP;
    }

    /**
     * @return float
     */
    public function getSpnSerotype()
    {
        return $this->spn_serotype;
    }

    /**
     * @return float
     */
    public function getHiSerotype()
    {
        return $this->hi_serotype;
    }

    /**
     * @return float
     */
    public function getNmSerogroup()
    {
        return $this->nm_serogroup;
    }

    /**
     * @param SampleType $sampleType
     * @return $this
     */
    public function setSampleType(SampleType $sampleType)
    {
        $this->type_sample_recd = $sampleType;
        return $this;
    }

    /**
     * @param IsolateViable $isolateViable
     * @return $this
     */
    public function setIsolateViable(IsolateViable $isolateViable)
    {
        $this->isolate_viable = $isolateViable;
        return $this;
    }

    /**
     * @param IsolateType $isolateType
     * @return $this
     */
    public function setIsolateType(IsolateType $isolateType)
    {
        $this->isolate_type = $isolateType;
        return $this;
    }

    /**
     * @param PathogenIdentifier $pathogenIdentifierMethod
     * @return $this
     */
    public function setPathogenIdentifierMethod(PathogenIdentifier $pathogenIdentifierMethod)
    {
        $this->method_used_pathogen_identify = $pathogenIdentifierMethod;
        return $this;
    }

    /**
     * @param $pathogenIdentifierOther
     * @return $this
     */
    public function setPathogenIdentifierOther($pathogenIdentifierOther)
    {
        $this->method_used_pathogen_identify_other = $pathogenIdentifierOther;
        return $this;
    }

    /**
     * @param SerotypeIdentifier $serotypeIdentifier
     * @return $this
     */
    public function setSerotypeIdentifier(SerotypeIdentifier $serotypeIdentifier)
    {
        $this->method_used_st_sg = $serotypeIdentifier;
        return $this;
    }

    /**
     * @param $serotypeIdentifierOther
     * @return $this
     */
    public function setSerotypeIdentifierOther($serotypeIdentifierOther)
    {
        $this->method_used_st_sg_other = $serotypeIdentifierOther;
        return $this;
    }

    /**
     * @param $lytA
     * @return $this
     */
    public function setLytA($lytA)
    {
        $this->spn_lytA = $lytA;
        return $this;
    }

    /**
     * @param $ctrA
     * @return $this
     */
    public function setCtrA($ctrA)
    {
        $this->nm_ctrA = $ctrA;
        return $this;
    }

    /**
     * @param $sodC
     * @return $this
     */
    public function setSodC($sodC)
    {
        $this->nm_sodC = $sodC;
        return $this;
    }

    /**
     * @param $hpd1
     * @return $this
     */
    public function setHpd1($hpd1)
    {
        $this->hi_hpd1 = $hpd1;
        return $this;
    }

    /**
     * @param $hpd3
     * @return $this
     */
    public function setHpd3($hpd3)
    {
        $this->hi_hpd3 = $hpd3;
        return $this;
    }

    /**
     * @param $bexA
     * @return $this
     */
    public function setBexA($bexA)
    {
        $this->hi_bexA = $bexA;
        return $this;
    }

    /**
     * @param $rNaseP
     * @return $this
     */
    public function setRNaseP($rNaseP)
    {
        $this->humanDNA_RNAseP = $rNaseP;
        return $this;
    }

    /**
     * @param SpnSerotype $spnSerotype
     * @return $this
     */
    public function setSpnSerotype(SpnSerotype $spnSerotype)
    {
        $this->spn_serotype = $spnSerotype;
        return $this;
    }

    /**
     * @param HiSerotype $hiSerotype
     * @return $this
     */
    public function setHiSerotype(HiSerotype $hiSerotype)
    {
        $this->hi_serotype = $hiSerotype;
        return $this;
    }

    /**
     * @param NmSerogroup $nmSerogroup
     * @return $this
     */
    public function setNmSerogroup(NmSerogroup $nmSerogroup)
    {
        $this->nm_serogroup = $nmSerogroup;
        return $this;
    }

    /**
     * @return SampleType
     */
    public function getTypeSampleRecd()
    {
        return $this->type_sample_recd;
    }

    /**
     * @param SampleType $type_sample_recd
     */
    public function setTypeSampleRecd($type_sample_recd)
    {
        $this->type_sample_recd = $type_sample_recd;
    }

    /**
     * @return PathogenIdentifier
     */
    public function getMethodUsedPathogenIdentify()
    {
        return $this->method_used_pathogen_identify;
    }

    /**
     * @param PathogenIdentifier $method_used_pathogen_identify
     */
    public function setMethodUsedPathogenIdentify($method_used_pathogen_identify)
    {
        $this->method_used_pathogen_identify = $method_used_pathogen_identify;
    }

    /**
     * @return string
     */
    public function getMethodUsedPathogenIdentifyOther()
    {
        return $this->method_used_pathogen_identify_other;
    }

    /**
     * @param string $method_used_pathogen_identify_other
     */
    public function setMethodUsedPathogenIdentifyOther($method_used_pathogen_identify_other)
    {
        $this->method_used_pathogen_identify_other = $method_used_pathogen_identify_other;
    }

    /**
     * @return SerotypeIdentifier
     */
    public function getMethodUsedStSg()
    {
        return $this->method_used_st_sg;
    }

    /**
     * @param SerotypeIdentifier $method_used_st_sg
     */
    public function setMethodUsedStSg($method_used_st_sg)
    {
        $this->method_used_st_sg = $method_used_st_sg;
    }

    /**
     * @return string
     */
    public function getMethodUsedStSgOther()
    {
        return $this->method_used_st_sg_other;
    }

    /**
     * @param string $method_used_st_sg_other
     */
    public function setMethodUsedStSgOther($method_used_st_sg_other)
    {
        $this->method_used_st_sg_other = $method_used_st_sg_other;
    }

    /**
     * @return float
     */
    public function getSpnLytA()
    {
        return $this->spn_lytA;
    }

    /**
     * @param float $spn_lytA
     */
    public function setSpnLytA($spn_lytA)
    {
        $this->spn_lytA = $spn_lytA;
    }

    /**
     * @return int
     */
    public function getNmCtrA()
    {
        return $this->nm_ctrA;
    }

    /**
     * @param int $nm_ctrA
     */
    public function setNmCtrA($nm_ctrA)
    {
        $this->nm_ctrA = $nm_ctrA;
    }

    /**
     * @return float
     */
    public function getNmSodC()
    {
        return $this->nm_sodC;
    }

    /**
     * @param float $nm_sodC
     */
    public function setNmSodC($nm_sodC)
    {
        $this->nm_sodC = $nm_sodC;
    }

    /**
     * @return float
     */
    public function getHiHpd1()
    {
        return $this->hi_hpd1;
    }

    /**
     * @param float $hi_hpd1
     */
    public function setHiHpd1($hi_hpd1)
    {
        $this->hi_hpd1 = $hi_hpd1;
    }

    /**
     * @return float
     */
    public function getHiHpd3()
    {
        return $this->hi_hpd3;
    }

    /**
     * @param float $hi_hpd3
     */
    public function setHiHpd3($hi_hpd3)
    {
        $this->hi_hpd3 = $hi_hpd3;
    }

    /**
     * @return float
     */
    public function getHiBexA()
    {
        return $this->hi_bexA;
    }

    /**
     * @param float $hi_bexA
     */
    public function setHiBexA($hi_bexA)
    {
        $this->hi_bexA = $hi_bexA;
    }

    /**
     * @return float
     */
    public function getHumanDNARNAseP()
    {
        return $this->humanDNA_RNAseP;
    }

    /**
     * @param float $humanDNA_RNAseP
     */
    public function setHumanDNARNAseP($humanDNA_RNAseP)
    {
        $this->humanDNA_RNAseP = $humanDNA_RNAseP;
    }

    /**
     * @return FinalResult
     */
    public function getFinalRLResultDetection()
    {
        return $this->final_RL_result_detection;
    }

    /**
     * @param FinalResult $final_RL_result_detection
     */
    public function setFinalRLResultDetection($final_RL_result_detection)
    {
        $this->final_RL_result_detection = $final_RL_result_detection;
    }

    /**
     * @return array
     */
    public function getMandatoryFields()
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
    public function validate(ExecutionContextInterface $context)
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

    /**
     * @return null|string
     */
    public function getIncompleteField()
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

    /**
     * 
     * @return FinalResult
     */
    public function getFinalResult()
    {
        return $this->final_RL_result_detection;
    }

    /**
     * 
     * @param FinalResult $finalResult
     * @return ExternalLab
     */
    public function setFinalResult(FinalResult $finalResult)
    {
        $this->final_RL_result_detection = $finalResult;
        return $this;
    }
}
