<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\BaseExternalLab;
use NS\SentinelBundle\Form\Types\IsolateViable;
use NS\SentinelBundle\Form\Types\FinalResult;
use NS\SentinelBundle\Form\Types\HiSerotype;
use NS\SentinelBundle\Form\Types\IsolateType;
use NS\SentinelBundle\Form\Types\NmSerogroup;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SampleType;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\SpnSerotype;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use \Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
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
 * @Assert\Callback(methods={"validate"})
 * @SuppressWarnings(PHPMD.LongVariable)
 * @ORM\MappedSuperclass
 */
abstract class ExternalLab extends BaseExternalLab
{
    /**
     * @var \DateTime $sampleCollectionDate
     * @ORM\Column(name="sampleCollectionDate",type="date",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $sampleCollectionDate;

    /**
     * @var SampleType
     * @ORM\Column(type="SampleType",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $sampleType;

    /**
     * @var \DateTime $dateReceived
     * @ORM\Column(name="dateReceived", type="date",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $dateReceived;

    /**
     * @var IsolateViable
     * @ORM\Column(name="isolateViable",type="IsolateViable",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $isolateViable;

    /**
     * @var IsolateType
     * @ORM\Column(name="isolateType",type="IsolateType",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $isolateType;

    /**
     * @var PathogenIdentifier
     * @ORM\Column(name="pathogenIdentifierMethod",type="PathogenIdentifier",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $pathogenIdentifierMethod;

    /**
     * @var string
     * @ORM\Column(name="pathogenIdentifierOther", type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $pathogenIdentifierOther;

    /**
     * @var SerotypeIdentifier
     * @ORM\Column(name="serotypeIdentifier",type="SerotypeIdentifier",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $serotypeIdentifier;

    /**
     * @var string
     * @ORM\Column(name="serotypeIdentifierOther",type="string",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $serotypeIdentifierOther;

    /**
     * @var double
     * @ORM\Column(name="lytA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api"})
     */
    protected $lytA;

    /**
     * @var integer $ctrA
     * @ORM\Column(name="ctrA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api"})
     */
    protected $ctrA;

    /**
     * @var double
     * @ORM\Column(name="sodC",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api"})
     */
    protected $sodC;

    /**
     * @var double
     * @ORM\Column(name="hpd1",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api"})
     */
    protected $hpd1;

    /**
     * @var double
     * @ORM\Column(name="hpd3",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api"})
     */
    protected $hpd3;

    /**
     * @var double
     * @ORM\Column(name="bexA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api"})
     */
    protected $bexA;

    /**
     * @var double
     * @ORM\Column(name="rNaseP",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=-10,max=50)
     * @Serializer\Groups({"api"})
     */
    protected $rNaseP;

    /**
     * @var FinalResult $finalResult
     * @ORM\Column(name="finalResult",type="FinalResult",nullable=true)
     */
    private $finalResult;

    /**
     * @var double
     * @ORM\Column(name="spnSerotype",type="SpnSerotype",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $spnSerotype;

    /**
     * @var double
     * @ORM\Column(name="hiSerotype",type="HiSerotype",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $hiSerotype;

    /**
     * @var double
     * @ORM\Column(name="nmSerogroup",type="NmSerogroup",nullable=true)
     * @Serializer\Groups({"api"})
     */
    protected $nmSerogroup;

    /**
     * @return SampleType
     */
    public function getSampleType()
    {
        return $this->sampleType;
    }

    /**
     * @return \DateTime
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * @return IsolateViable
     */
    public function getIsolateViable()
    {
        return $this->isolateViable;
    }

    /**
     * @return IsolateType
     */
    public function getIsolateType()
    {
        return $this->isolateType;
    }

    /**
     * @return PathogenIdentifier
     */
    public function getPathogenIdentifierMethod()
    {
        return $this->pathogenIdentifierMethod;
    }

    /**
     * @return string
     */
    public function getPathogenIdentifierOther()
    {
        return $this->pathogenIdentifierOther;
    }

    /**
     * @return SerotypeIdentifier
     */
    public function getSerotypeIdentifier()
    {
        return $this->serotypeIdentifier;
    }

    /**
     * @return string
     */
    public function getSerotypeIdentifierOther()
    {
        return $this->serotypeIdentifierOther;
    }

    /**
     * @return float
     */
    public function getLytA()
    {
        return $this->lytA;
    }

    /**
     * @return int
     */
    public function getCtrA()
    {
        return $this->ctrA;
    }

    /**
     * @return float
     */
    public function getSodC()
    {
        return $this->sodC;
    }

    /**
     * @return float
     */
    public function getHpd1()
    {
        return $this->hpd1;
    }

    /**
     * @return float
     */
    public function getHpd3()
    {
        return $this->hpd3;
    }

    /**
     * @return float
     */
    public function getBexA()
    {
        return $this->bexA;
    }

    /**
     * @return float
     */
    public function getRNaseP()
    {
        return $this->rNaseP;
    }

    /**
     * @return float
     */
    public function getSpnSerotype()
    {
        return $this->spnSerotype;
    }

    /**
     * @return float
     */
    public function getHiSerotype()
    {
        return $this->hiSerotype;
    }

    /**
     * @return float
     */
    public function getNmSerogroup()
    {
        return $this->nmSerogroup;
    }

    /**
     * @param SampleType $sampleType
     * @return $this
     */
    public function setSampleType(SampleType $sampleType)
    {
        $this->sampleType = $sampleType;
        return $this;
    }

    /**
     * @param \DateTime|null $dateReceived
     * @return $this
     */
    public function setDateReceived(\DateTime $dateReceived = null)
    {
        $this->dateReceived = $dateReceived;
        return $this;
    }

    /**
     * @param IsolateViable $isolateViable
     * @return $this
     */
    public function setIsolateViable(IsolateViable $isolateViable)
    {
        $this->isolateViable = $isolateViable;
        return $this;
    }

    /**
     * @param IsolateType $isolateType
     * @return $this
     */
    public function setIsolateType(IsolateType $isolateType)
    {
        $this->isolateType = $isolateType;
        return $this;
    }

    /**
     * @param PathogenIdentifier $pathogenIdentifierMethod
     * @return $this
     */
    public function setPathogenIdentifierMethod(PathogenIdentifier $pathogenIdentifierMethod)
    {
        $this->pathogenIdentifierMethod = $pathogenIdentifierMethod;
        return $this;
    }

    /**
     * @param $pathogenIdentifierOther
     * @return $this
     */
    public function setPathogenIdentifierOther($pathogenIdentifierOther)
    {
        $this->pathogenIdentifierOther = $pathogenIdentifierOther;
        return $this;
    }

    /**
     * @param SerotypeIdentifier $serotypeIdentifier
     * @return $this
     */
    public function setSerotypeIdentifier(SerotypeIdentifier $serotypeIdentifier)
    {
        $this->serotypeIdentifier = $serotypeIdentifier;
        return $this;
    }

    /**
     * @param $serotypeIdentifierOther
     * @return $this
     */
    public function setSerotypeIdentifierOther($serotypeIdentifierOther)
    {
        $this->serotypeIdentifierOther = $serotypeIdentifierOther;
        return $this;
    }

    /**
     * @param $lytA
     * @return $this
     */
    public function setLytA($lytA)
    {
        $this->lytA = $lytA;
        return $this;
    }

    /**
     * @param $ctrA
     * @return $this
     */
    public function setCtrA($ctrA)
    {
        $this->ctrA = $ctrA;
        return $this;
    }

    /**
     * @param $sodC
     * @return $this
     */
    public function setSodC($sodC)
    {
        $this->sodC = $sodC;
        return $this;
    }

    /**
     * @param $hpd1
     * @return $this
     */
    public function setHpd1($hpd1)
    {
        $this->hpd1 = $hpd1;
        return $this;
    }

    /**
     * @param $hpd3
     * @return $this
     */
    public function setHpd3($hpd3)
    {
        $this->hpd3 = $hpd3;
        return $this;
    }

    /**
     * @param $bexA
     * @return $this
     */
    public function setBexA($bexA)
    {
        $this->bexA = $bexA;
        return $this;
    }

    /**
     * @param $rNaseP
     * @return $this
     */
    public function setRNaseP($rNaseP)
    {
        $this->rNaseP = $rNaseP;
        return $this;
    }

    /**
     * @param SpnSerotype $spnSerotype
     * @return $this
     */
    public function setSpnSerotype(SpnSerotype $spnSerotype)
    {
        $this->spnSerotype = $spnSerotype;
        return $this;
    }

    /**
     * @param HiSerotype $hiSerotype
     * @return $this
     */
    public function setHiSerotype(HiSerotype $hiSerotype)
    {
        $this->hiSerotype = $hiSerotype;
        return $this;
    }

    /**
     * @param NmSerogroup $nmSerogroup
     * @return $this
     */
    public function setNmSerogroup(NmSerogroup $nmSerogroup)
    {
        $this->nmSerogroup = $nmSerogroup;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSampleCollectionDate()
    {
        return $this->sampleCollectionDate;
    }

    /**
     * @param \DateTime $sampleCollectionDate
     * @return ExternalLab
     */
    public function setSampleCollectionDate(\DateTime $sampleCollectionDate = null)
    {
        $this->sampleCollectionDate = $sampleCollectionDate;
        return $this;
    }

    /**
     * @return array
     */
    public function getMandatoryFields()
    {
        return array(
                    'sampleType',
                    'dateReceived',
                    'isolateViable',
                    'isolateType',
                    'pathogenIdentifierMethod',
                    'serotypeIdentifier',
                    'spnSerotype',
                    'hiSerotype',
                    'nmSerogroup',
        );
    }

    /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        // if pathogenIdentifierMethod is other, enforce value in 'pathogenIdentifierMethod other' field
        if ($this->pathogenIdentifierMethod && $this->pathogenIdentifierMethod->equal(PathogenIdentifier::OTHER) && empty($this->pathogenIdentifierOther)) {
            $context->buildViolation('form.validation.pathogenIdentifierMethod-other-without-other-text')->atPath('pathogenIdentifierMethod')->addViolation();
        }

        // if serotypeIdentifier is other, enforce value in 'serotypeIdentifier other' field
        if ($this->serotypeIdentifier && $this->serotypeIdentifier->equal(SerotypeIdentifier::OTHER) && empty($this->serotypeIdentifierOther)) {
            $context->buildViolation('form.validation.serotypeIdentifier-other-without-other-text')->atPath('serotypeIdentifier')->addViolation();
        }
    }

    /**
     * @return null|string|void
     */
    public function getIncompleteField()
    {
        $ret = parent::getIncompleteField();
        if($ret) {
            return $ret;
        }

        if ($this->pathogenIdentifierMethod && $this->pathogenIdentifierMethod->equal(PathogenIdentifier::OTHER) && empty($this->pathogenIdentifierOther)) {
            return 'pathogenIdentier';
        }

        if ($this->serotypeIdentifier && $this->serotypeIdentifier->equal(SerotypeIdentifier::OTHER) && empty($this->serotypeIdentifierOther)) {
            return 'serotypeIdentier';
        }

        return null;
    }

    /**
     * 
     * @return FinalResult
     */
    public function getFinalResult()
    {
        return $this->finalResult;
    }

    /**
     * 
     * @param FinalResult $finalResult
     * @return ExternalLab
     */
    public function setFinalResult(FinalResult $finalResult)
    {
        $this->finalResult = $finalResult;
        return $this;
    }
}
