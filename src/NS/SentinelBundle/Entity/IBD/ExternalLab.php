<?php

namespace NS\SentinelBundle\Entity\IBD;

use \Doctrine\ORM\Mapping as ORM;
use \NS\SentinelBundle\Entity\BaseExternalLab;
use \NS\SentinelBundle\Form\Types\PathogenIdentifier;
use \NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use \NS\SentinelBundle\Form\Types\SampleType;
use \NS\SentinelBundle\Form\Types\Volume;
use \NS\SentinelBundle\Form\Types\IsolateType;
use \NS\SentinelBundle\Form\Types\AlternateTripleChoice;

// Annotations
use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Description of ExternalLab
 * @author gnat
 * @ORM\Entity()
 * @ORM\Table(name="ibd_external_labs",uniqueConstraints={@ORM\UniqueConstraint(name="site_type_idx",columns={"case_id","discr"})})
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
 * @Assert\Callback(methods={"validate"})
 */
abstract class ExternalLab extends BaseExternalLab
{
    /**
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\IBD",inversedBy="externalLabs")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $case;

    /**
     * @var SampleType
     * @ORM\Column(type="SampleType",nullable=true)
     */
    protected $sampleType;

    /**
     * @var \DateTime $dateReceived
     * @ORM\Column(name="dateReceived", type="date",nullable=true)
     */
    protected $dateReceived;

    /**
     * @var Volume
     * @ORM\Column(name="csfVolumeExtracted",type="Volume",nullable=true)
     */
    protected $csfVolumeExtracted;

    /**
     * @var \DateTime $DNAExtractionDate;
     * @ORM\Column(name="DNAExtractionDate",type="date",nullable=true)
     */
    protected $DNAExtractionDate;

    /**
     * @var integer
     * @ORM\Column(name="DNAVolume",type="integer",nullable=true)
     */
    protected $DNAVolume;

    /**
     * @var AlternateTripleChoice
     * @ORM\Column(name="isolateViable",type="AlternateTripleChoice",nullable=true)
     */
    protected $isolateViable;

    /**
     * @var IsolateType
     * @ORM\Column(name="isolateType",type="IsolateType",nullable=true)
     */
    protected $isolateType;

    /**
     * @var PathogenIdentifier
     * @ORM\Column(name="pathogenIdentifierMethod",type="PathogenIdentifier",nullable=true)
     */
    protected $pathogenIdentifierMethod;

    /**
     * @var string
     * @ORM\Column(name="pathogenIdentifierOther", type="string",nullable=true)
     */
    protected $pathogenIdentifierOther;

    /**
     * @var SerotypeIdentifier
     * @ORM\Column(name="serotypeIdentifier",type="SerotypeIdentifier",nullable=true)
     */
    protected $serotypeIdentifier;

    /**
     * @var string
     * @ORM\Column(name="serotypeIdentifierOther",type="string",nullable=true)
     */
    protected $serotypeIdentifierOther;

    /**
     * @var double
     * @ORM\Column(name="lytA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=0,max=50)
     */
    protected $lytA;

    /**
     * @var integer $ctrA
     * @ORM\Column(name="ctrA",type="integer")
     * @Assert\Range(min=0,max=50)
     */
    protected $ctrA;

    /**
     * @var double
     * @ORM\Column(name="sodC",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=0,max=50)
     */
    protected $sodC;

    /**
     * @var double
     * @ORM\Column(name="hpd1",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=0,max=50)
     */
    protected $hpd1;

    /**
     * @var double
     * @ORM\Column(name="hpd3",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=0,max=50)
     */
    protected $hpd3;

    /**
     * @var double
     * @ORM\Column(name="bexA",type="decimal",precision=3, scale=1,nullable=true)
     * @Assert\Range(min=0,max=50)
     */
    protected $bexA;

    /**
     * @var double
     * @ORM\Column(name="rNaseP",type="decimal",precision=3, scale=1,nullable=true)
     */
    protected $rNaseP;

    /**
     * @var double
     * @ORM\Column(name="spnSerotype",type="decimal",precision=3, scale=1,nullable=true)
     */
    protected $spnSerotype;

    /**
     * @var double
     * @ORM\Column(name="hiSerotype",type="decimal",precision=3, scale=1,nullable=true)
     */
    protected $hiSerotype;

    /**
     * @var double
     * @ORM\Column(name="nmSerogroup",type="decimal",precision=3, scale=1,nullable=true)
     */
    protected $nmSerogroup;
    public function getSampleType()
    {
        return $this->sampleType;
    }

    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    public function getCsfVolumeExtracted()
    {
        return $this->csfVolumeExtracted;
    }

    public function getDNAExtractionDate()
    {
        return $this->DNAExtractionDate;
    }

    public function getDNAVolume()
    {
        return $this->DNAVolume;
    }

    public function getIsolateViable()
    {
        return $this->isolateViable;
    }

    public function getIsolateType()
    {
        return $this->isolateType;
    }

    public function getPathogenIdentifierMethod()
    {
        return $this->pathogenIdentifierMethod;
    }

    public function getPathogenIdentifierOther()
    {
        return $this->pathogenIdentifierOther;
    }

    public function getSerotypeIdentifier()
    {
        return $this->serotypeIdentifier;
    }

    public function getSerotypeIdentifierOther()
    {
        return $this->serotypeIdentifierOther;
    }

    public function getLytA()
    {
        return $this->lytA;
    }

    public function getCtrA()
    {
        return $this->ctrA;
    }

    public function getSodC()
    {
        return $this->sodC;
    }

    public function getHpd1()
    {
        return $this->hpd1;
    }

    public function getHpd3()
    {
        return $this->hpd3;
    }

    public function getBexA()
    {
        return $this->bexA;
    }

    public function getRNaseP()
    {
        return $this->rNaseP;
    }

    public function getSpnSerotype()
    {
        return $this->spnSerotype;
    }

    public function getHiSerotype()
    {
        return $this->hiSerotype;
    }

    public function getNmSerogroup()
    {
        return $this->nmSerogroup;
    }

    public function setSampleType(SampleType $sampleType)
    {
        $this->sampleType = $sampleType;
        return $this;
    }

    public function setDateReceived($dateReceived)
    {
        if ($dateReceived instanceof \DateTime)
            $this->dateReceived = $dateReceived;

        return $this;
    }

    public function setCsfVolumeExtracted(Volume $csfVolumeExtracted)
    {
        $this->csfVolumeExtracted = $csfVolumeExtracted;
        return $this;
    }

    public function setDNAExtractionDate($DNAExtractionDate)
    {
        if ($DNAExtractionDate instanceof \DateTime)
            $this->DNAExtractionDate = $DNAExtractionDate;

        return $this;
    }

    public function setDNAVolume($DNAVolume)
    {
        $this->DNAVolume = $DNAVolume;
        return $this;
    }

    public function setIsolateViable(AlternateTripleChoice $isolateViable)
    {
        $this->isolateViable = $isolateViable;
        return $this;
    }

    public function setIsolateType(IsolateType $isolateType)
    {
        $this->isolateType = $isolateType;
        return $this;
    }

    public function setPathogenIdentifierMethod(PathogenIdentifier $pathogenIdentifierMethod)
    {
        $this->pathogenIdentifierMethod = $pathogenIdentifierMethod;
        return $this;
    }

    public function setPathogenIdentifierOther($pathogenIdentifierOther)
    {
        $this->pathogenIdentifierOther = $pathogenIdentifierOther;
        return $this;
    }

    public function setSerotypeIdentifier(SerotypeIdentifier $serotypeIdentifier)
    {
        $this->serotypeIdentifier = $serotypeIdentifier;
        return $this;
    }

    public function setSerotypeIdentifierOther($serotypeIdentifierOther)
    {
        $this->serotypeIdentifierOther = $serotypeIdentifierOther;
        return $this;
    }

    public function setLytA($lytA)
    {
        $this->lytA = $lytA;
        return $this;
    }

    public function setCtrA($ctrA)
    {
        $this->ctrA = $ctrA;
        return $this;
    }

    public function setSodC($sodC)
    {
        $this->sodC = $sodC;
        return $this;
    }

    public function setHpd1($hpd1)
    {
        $this->hpd1 = $hpd1;
        return $this;
    }

    public function setHpd3($hpd3)
    {
        $this->hpd3 = $hpd3;
        return $this;
    }

    public function setBexA($bexA)
    {
        $this->bexA = $bexA;
        return $this;
    }

    public function setRNaseP($rNaseP)
    {
        $this->rNaseP = $rNaseP;
        return $this;
    }

    public function setSpnSerotype($spnSerotype)
    {
        $this->spnSerotype = $spnSerotype;
        return $this;
    }

    public function setHiSerotype($hiSerotype)
    {
        $this->hiSerotype = $hiSerotype;
        return $this;
    }

    public function setNmSerogroup($nmSerogroup)
    {
        $this->nmSerogroup = $nmSerogroup;
        return $this;
    }

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
                    'resultSentToCountry',
                    'resultSentToWHO',
                    );
    }

    public function validate(ExecutionContextInterface $context)
    {
        // if pathogenIdentifierMethod is other, enforce value in 'pathogenIdentifierMethod other' field
        if($this->pathogenIdentifierMethod && $this->pathogenIdentifierMethod->equal(PathogenIdentifier::OTHER) && empty($this->pathogenIdentifierOther))
            $context->addViolationAt('pathogenIdentifierMethod',"form.validation.pathogenIdentifierMethod-other-without-other-text");

        // if serotypeIdentifier is other, enforce value in 'serotypeIdentifier other' field
        if($this->serotypeIdentifier && $this->serotypeIdentifier->equal(SerotypeIdentifier::OTHER) && empty($this->serotypeIdentifierOther))
            $context->addViolationAt('serotypeIdentifier',"form.validation.serotypeIdentifier-other-without-other-text");
    }

    public function getIncompleteField()
    {
        $ret = parent::getIncompleteField();
        if($ret)
            return $ret;

        if($this->pathogenIdentifierMethod && $this->pathogenIdentifierMethod->equal(Diagnosis::OTHER) && empty($this->pathogenIdentifierOther))
            return 'pathogenIdentier';

        if($this->serotypeIdentifierMethod && $this->serotypeIdentifierMethod->equal(Diagnosis::OTHER) && empty($this->serotypeIdentifierOther))
            return 'serotypeIdentier';
    }
}