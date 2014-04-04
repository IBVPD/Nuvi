<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\PathogenIdentifier;
use NS\SentinelBundle\Form\Types\SerotypeIdentifier;
use NS\SentinelBundle\Form\Types\SampleType;
use NS\SentinelBundle\Form\Types\Volume;
use NS\SentinelBundle\Form\Types\IsolateType;

// Annotations
use Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

/**
 * Description of ReferenceLab
 * @author gnat
 * @ORM\Entity()
 * @ORM\Table(name="meningitis_external_labs",uniqueConstraints={@ORM\UniqueConstraint(name="site_type_idx",columns={"case_id","discr"})})
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr",type="string")
 * @ORM\DiscriminatorMap({"reference" = "ReferenceLab", "national" = "NationalLab"})
 */
class BaseLab
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Meningitis",inversedBy="externalLabs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $case;

    /**
     * @var SampleType
     * @ORM\Column(type="SampleType",nullable=true)
     */
    private $sampleType;

    /**
     * @var DateTime
     * @ORM\Column(type="date",nullable=true)
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
     * @ORM\Column(type="integer",nullable=true)
     */
    private $DNAVolume;

    /**
     * @var TripleChoice
     * @ORM\Column(type="TripleChoice",nullable=true)
     */
    private $isolateViable;

    /**
     * @var IsolateType
     * @ORM\Column(type="IsolateType",nullable=true)
     */
    private $isolateType;

    /**
     * @var PathogenIdentifier
     * @ORM\Column(type="PathogenIdentifier",nullable=true)
     */
    private $pathogenIdentifierMethod;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=true)
     */
    private $pathogenIdentierOther;

    /**
     * @var SerotypeIdentifier
     * @ORM\Column(type="SerotypeIdentifier",nullable=true)
     */
    private $serotypeIdentifier;

    /**
     * @var string
     * @ORM\Column(type="string",nullable=true)
     */
    private $serotypeIdentifierOther;

    /**
     * @var double
     * @ORM\Column(type="decimal",precision=3, scale=1,nullable=true)
     */
    private $lytA;

    /**
     * @var double
     * @ORM\Column(type="decimal",precision=3, scale=1,nullable=true)
     */
    private $sodC;

    /**
     * @var double
     * @ORM\Column(type="decimal",precision=3, scale=1,nullable=true)
     */
    private $hpd;

    /**
     * @var double
     * @ORM\Column(type="decimal",precision=3, scale=1,nullable=true)
     */
    private $rNaseP;

    /**
     * @var double
     * @ORM\Column(type="decimal",precision=3, scale=1,nullable=true)
     */
    private $spnSerotype;

    /**
     * @var double
     * @ORM\Column(type="decimal",precision=3, scale=1,nullable=true)
     */
    private $hiSerotype;

    /**
     * @var double
     * @ORM\Column(type="decimal",precision=3, scale=1,nullable=true)
     */
    private $nmSerogroup;

    /**
     * @var \DateTime $resultSentToCountry
     * @ORM\Column(name="resultSentToCountry",type="date",nullable=true)
     */
    private $resultSentToCountry;

    /**
     * @var \DateTime $resultSentToWHO
     * @ORM\Column(name="resultSentToWHO",type="date",nullable=true)
     */
    private $resultSentToWHO;

    /**
     * @var boolean $isComplete
     * @ORM\Column(name="isComplete",type="boolean")
     */
    private $isComplete = false;

    public function __construct()
    {
        $this->isComplete = false;
    }

    /**
     * Set case
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $case
     * @return MeningitisLab
     */
    public function setCase(\NS\SentinelBundle\Entity\Meningitis $case = null)
    {
        $this->case = $case;

        return $this;
    }

    /**
     * Get case
     *
     * @return \NS\SentinelBundle\Entity\Meningitis 
     */
    public function getCase()
    {
        return $this->case;
    }

    public function hasCase()
    {
        return $this->case instanceof Meningitis;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
     * Set pathogenIdentierOther
     *
     * @param string $pathogenIdentierOther
     * @return ReferenceLab
     */
    public function setPathogenIdentierOther($pathogenIdentierOther)
    {
        $this->pathogenIdentierOther = $pathogenIdentierOther;

        return $this;
    }

    /**
     * Get pathogenIdentierOther
     *
     * @return string 
     */
    public function getPathogenIdentierOther()
    {
        return $this->pathogenIdentierOther;
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

    public function getResultSentToCountry()
    {
        return $this->resultSentToCountry;
    }

    public function getResultSentToWHO()
    {
        return $this->resultSentToWHO;
    }

    public function getIsComplete()
    {
        return $this->isComplete;
    }

    public function setResultSentToCountry( $resultSentToCountry)
    {
        $this->resultSentToCountry = $resultSentToCountry;

        return $this;
    }

    public function setResultSentToWHO( $resultSentToWHO)
    {
        $this->resultSentToWHO = $resultSentToWHO;

        return $this;
    }

    public function setIsComplete($isComplete)
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->_calculateIsComplete();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->_calculateIsComplete();
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

    protected function _calculateIsComplete()
    {
        foreach($this->getMandatoryFields() as $fieldName)
        {
            if(!$this->$fieldName)
            {
                $this->isComplete = false;
                return;
            }
        }

        $this->isComplete = true;
    }
}