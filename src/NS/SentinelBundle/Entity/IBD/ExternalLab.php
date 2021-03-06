<?php

namespace NS\SentinelBundle\Entity\IBD;

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
     * @var SpnSerotype
     * @ORM\Column(name="spn_serotype",type="SpnSerotype",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $spn_serotype;

    /**
     * @var HiSerotype
     * @ORM\Column(name="hi_serotype",type="HiSerotype",nullable=true)
     * @Serializer\Groups({"api","export"})
     */
    protected $hi_serotype;

    /**
     * @var NmSerogroup
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
     * @return SpnSerotype
     */
    public function getSpnSerotype()
    {
        return $this->spn_serotype;
    }

    /**
     * @return HiSerotype
     */
    public function getHiSerotype()
    {
        return $this->hi_serotype;
    }

    /**
     * @return NmSerogroup
     */
    public function getNmSerogroup()
    {
        return $this->nm_serogroup;
    }

    /**
     * @param SampleType $sampleType
     */
    public function setSampleType(SampleType $sampleType)
    {
        $this->type_sample_recd = $sampleType;
    }

    /**
     * @param IsolateViable $isolateViable
     */
    public function setIsolateViable(IsolateViable $isolateViable)
    {
        $this->isolate_viable = $isolateViable;
    }

    /**
     * @param IsolateType $isolateType
     */
    public function setIsolateType(IsolateType $isolateType)
    {
        $this->isolate_type = $isolateType;
    }

    /**
     * @param PathogenIdentifier $pathogenIdentifierMethod
     */
    public function setPathogenIdentifierMethod(PathogenIdentifier $pathogenIdentifierMethod)
    {
        $this->method_used_pathogen_identify = $pathogenIdentifierMethod;
    }

    /**
     * @param $pathogenIdentifierOther
     */
    public function setPathogenIdentifierOther($pathogenIdentifierOther)
    {
        $this->method_used_pathogen_identify_other = $pathogenIdentifierOther;
    }

    /**
     * @param SerotypeIdentifier $serotypeIdentifier
     */
    public function setSerotypeIdentifier(SerotypeIdentifier $serotypeIdentifier)
    {
        $this->method_used_st_sg = $serotypeIdentifier;
    }

    /**
     * @param $serotypeIdentifierOther
     */
    public function setSerotypeIdentifierOther($serotypeIdentifierOther)
    {
        $this->method_used_st_sg_other = $serotypeIdentifierOther;
    }

    /**
     * @param $lytA
     */
    public function setLytA($lytA)
    {
        $this->spn_lytA = $lytA;
    }

    /**
     * @param $ctrA
     */
    public function setCtrA($ctrA)
    {
        $this->nm_ctrA = $ctrA;
    }

    /**
     * @param $sodC
     */
    public function setSodC($sodC)
    {
        $this->nm_sodC = $sodC;
    }

    /**
     * @param $hpd1
     */
    public function setHpd1($hpd1)
    {
        $this->hi_hpd1 = $hpd1;
    }

    /**
     * @param $hpd3
     */
    public function setHpd3($hpd3)
    {
        $this->hi_hpd3 = $hpd3;
    }

    /**
     * @param $bexA
     */
    public function setBexA($bexA)
    {
        $this->hi_bexA = $bexA;
    }

    /**
     * @param $rNaseP
     */
    public function setRNaseP($rNaseP)
    {
        $this->humanDNA_RNAseP = $rNaseP;
    }

    /**
     * @param SpnSerotype $spnSerotype
     */
    public function setSpnSerotype(SpnSerotype $spnSerotype = null)
    {
        $this->spn_serotype = $spnSerotype;
    }

    /**
     * @param HiSerotype $hiSerotype
     */
    public function setHiSerotype(HiSerotype $hiSerotype = null)
    {
        $this->hi_serotype = $hiSerotype;
    }

    /**
     * @param NmSerogroup $nmSerogroup
     */
    public function setNmSerogroup(NmSerogroup $nmSerogroup = null)
    {
        $this->nm_serogroup = $nmSerogroup;
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
        foreach ($this->getMandatoryFields() as $fieldName) {
            if (!$this->$fieldName) {
                return $fieldName;
            }
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
     * @param FinalResult $finalResult
     */
    public function setFinalResult(FinalResult $finalResult)
    {
        $this->final_RL_result_detection = $finalResult;
    }
}
