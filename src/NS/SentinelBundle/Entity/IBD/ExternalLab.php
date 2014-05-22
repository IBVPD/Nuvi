<?php

namespace NS\SentinelBundle\Entity\IBD;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\BaseExternalLab;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\SampleType;
use NS\SentinelBundle\Form\Types\Volume;
use NS\SentinelBundle\Form\Types\IsolateType;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var ExternalLabSample $samples
     * @ORM\OneToMany(targetEntity="\NS\SentinelBundle\Entity\IBD\ExternalLabSample", mappedBy="lab")
     */
    private $samples;

    /**
     * @ORM\ManyToOne(targetEntity="\NS\SentinelBundle\Entity\Meningitis",inversedBy="externalLabs")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $case;

    /**
     * @var SampleType
     * @ORM\Column(type="SampleType",nullable=true)
     */
    private $sampleType;

    /**
     * @var DateTime $dateReceived
     * @ORM\Column(name="dateReceived", type="date",nullable=true)
     */
    private $dateReceived;

    /**
     * @var Volume
     * @ORM\Column(type="Volume",nullable=true)
     */
    private $volume;

    /**
     * @var DateTime
     * @ORM\Column(type="date",nullable=true)
     */
    private $DNAExtractionDate;

    /**
     * @var integer
     * @ORM\Column(name="DNAVolume",type="integer",nullable=true)
     */
    private $DNAVolume;

    /**
     * @var TripleChoice
     * @ORM\Column(name="isolateViable",type="TripleChoice",nullable=true)
     */
    private $isolateViable;

    /**
     * @var IsolateType
     * @ORM\Column(name="isolateType",type="IsolateType",nullable=true)
     */
    private $isolateType;

    public function __construct()
    {
        $this->samples = new ArrayCollection();
    }

    public function getSamples()
    {
        return $this->samples;
    }

    public function setSamples(ExternalLabSample $samples)
    {
        $this->samples = $samples;
    }

    /**
     * Set sampleType
     *
     * @param SampleType $sampleType
     * @return ReferenceLab
     */
    public function setSampleType($sampleType)
    {
        $this->sampleType = $sampleType;

        return $this;
    }

    /**
     * Get sampleType
     *
     * @return SampleType 
     */
    public function getSampleType()
    {
        return $this->sampleType;
    }

    /**
     * Set dateReceived
     *
     * @param \DateTime $dateReceived
     * @return ReferenceLab
     */
    public function setDateReceived($dateReceived)
    {
        $this->dateReceived = $dateReceived;

        return $this;
    }

    /**
     * Get dateReceived
     *
     * @return \DateTime 
     */
    public function getDateReceived()
    {
        return $this->dateReceived;
    }

    /**
     * Set volume
     *
     * @param Volume $volume
     * @return ReferenceLab
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return Volume 
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set DNAExtractionDate
     *
     * @param \DateTime $dNAExtractionDate
     * @return ReferenceLab
     */
    public function setDNAExtractionDate($dNAExtractionDate)
    {
        $this->DNAExtractionDate = $dNAExtractionDate;

        return $this;
    }

    /**
     * Get DNAExtractionDate
     *
     * @return \DateTime 
     */
    public function getDNAExtractionDate()
    {
        return $this->DNAExtractionDate;
    }

    /**
     * Set DNAVolume
     *
     * @param integer $dNAVolume
     * @return ReferenceLab
     */
    public function setDNAVolume($dNAVolume)
    {
        $this->DNAVolume = $dNAVolume;

        return $this;
    }

    /**
     * Get DNAVolume
     *
     * @return integer 
     */
    public function getDNAVolume()
    {
        return $this->DNAVolume;
    }

    /**
     * Set isolateViable
     *
     * @param TripleChoice $isolateViable
     * @return ReferenceLab
     */
    public function setIsolateViable($isolateViable)
    {
        $this->isolateViable = $isolateViable;

        return $this;
    }

    /**
     * Get isolateViable
     *
     * @return TripleChoice 
     */
    public function getIsolateViable()
    {
        return $this->isolateViable;
    }

    /**
     * Set isolateType
     *
     * @param IsolateType $isolateType
     * @return ReferenceLab
     */
    public function setIsolateType($isolateType)
    {
        $this->isolateType = $isolateType;

        return $this;
    }

    /**
     * Get isolateType
     *
     * @return IsolateType 
     */
    public function getIsolateType()
    {
        return $this->isolateType;
    }

    /**
     * Set pathogenIdentifierMethod
     *
     * @param PathogenIdentifier $pathogenIdentifierMethod
     * @return ReferenceLab
     */
    public function setPathogenIdentifierMethod($pathogenIdentifierMethod)
    {
        $this->pathogenIdentifierMethod = $pathogenIdentifierMethod;

        return $this;
    }

    /**
     * Get pathogenIdentifierMethod
     *
     * @return PathogenIdentifier 
     */
    public function getPathogenIdentifierMethod()
    {
        return $this->pathogenIdentifierMethod;
    }

    /**
     * Set pathogenIdentifierOther
     *
     * @param string $pathogenIdentifierOther
     * @return ReferenceLab
     */
    public function setPathogenIdentifierOther($pathogenIdentifierOther)
    {
        $this->pathogenIdentifierOther = $pathogenIdentifierOther;

        return $this;
    }

    /**
     * Get pathogenIdentifierOther
     *
     * @return string 
     */
    public function getPathogenIdentifierOther()
    {
        return $this->pathogenIdentifierOther;
    }

    /**
     * Set serotypeIdentifier
     *
     * @param SerotypeIdentifier $serotypeIdentifier
     * @return ReferenceLab
     */
    public function setSerotypeIdentifier($serotypeIdentifier)
    {
        $this->serotypeIdentifier = $serotypeIdentifier;

        return $this;
    }

    /**
     * Get serotypeIdentifier
     *
     * @return SerotypeIdentifier 
     */
    public function getSerotypeIdentifier()
    {
        return $this->serotypeIdentifier;
    }

    /**
     * Set serotypeIdentifierOther
     *
     * @param string $serotypeIdentifierOther
     * @return ReferenceLab
     */
    public function setSerotypeIdentifierOther($serotypeIdentifierOther)
    {
        $this->serotypeIdentifierOther = $serotypeIdentifierOther;

        return $this;
    }

    /**
     * Get serotypeIdentifierOther
     *
     * @return string 
     */
    public function getSerotypeIdentifierOther()
    {
        return $this->serotypeIdentifierOther;
    }

    /**
     * Set lytA
     *
     * @param float $lytA
     * @return ReferenceLab
     */
    public function setLytA($lytA)
    {
        $this->lytA = $lytA;

        return $this;
    }

    /**
     * Get lytA
     *
     * @return float 
     */
    public function getLytA()
    {
        return $this->lytA;
    }

    /**
     * Set sodC
     *
     * @param float $sodC
     * @return ReferenceLab
     */
    public function setSodC($sodC)
    {
        $this->sodC = $sodC;

        return $this;
    }

    /**
     * Get sodC
     *
     * @return float 
     */
    public function getSodC()
    {
        return $this->sodC;
    }

    /**
     * Set hpd
     *
     * @param float $hpd
     * @return ReferenceLab
     */
    public function setHpd($hpd)
    {
        $this->hpd = $hpd;

        return $this;
    }

    /**
     * Get hpd
     *
     * @return float 
     */
    public function getHpd()
    {
        return $this->hpd;
    }

    /**
     * Set rNaseP
     *
     * @param float $rNaseP
     * @return ReferenceLab
     */
    public function setRNaseP($rNaseP)
    {
        $this->rNaseP = $rNaseP;

        return $this;
    }

    /**
     * Get rNaseP
     *
     * @return float 
     */
    public function getRNaseP()
    {
        return $this->rNaseP;
    }

    /**
     * Set spnSerotype
     *
     * @param float $spnSerotype
     * @return ReferenceLab
     */
    public function setSpnSerotype($spnSerotype)
    {
        $this->spnSerotype = $spnSerotype;

        return $this;
    }

    /**
     * Get spnSerotype
     *
     * @return float 
     */
    public function getSpnSerotype()
    {
        return $this->spnSerotype;
    }

    /**
     * Set hiSerotype
     *
     * @param float $hiSerotype
     * @return ReferenceLab
     */
    public function setHiSerotype($hiSerotype)
    {
        $this->hiSerotype = $hiSerotype;

        return $this;
    }

    /**
     * Get hiSerotype
     *
     * @return float 
     */
    public function getHiSerotype()
    {
        return $this->hiSerotype;
    }

    /**
     * Set nmSerogroup
     *
     * @param float $nmSerogroup
     * @return ReferenceLab
     */
    public function setNmSerogroup($nmSerogroup)
    {
        $this->nmSerogroup = $nmSerogroup;

        return $this;
    }

    /**
     * Get nmSerogroup
     *
     * @return float 
     */
    public function getNmSerogroup()
    {
        return $this->nmSerogroup;
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