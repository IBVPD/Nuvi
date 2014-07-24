<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\BaseLab;
use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\ElisaResult;
use NS\SentinelBundle\Form\Types\ElisaKit;
use NS\SentinelBundle\Form\Types\GenotypeResultG;
use NS\SentinelBundle\Form\Types\GenotypeResultP;

/**
 * Description of Lab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Rota\Lab")
 * @ORM\Table(name="rotavirus_labs")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION","ROLE_REGION_API"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_COUNTRY_API"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_SITE_API"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 */
class Lab extends BaseLab
{
    protected $caseClass = 'NS\SentinelBundle\Entity\RotaVirus';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="NS\SentinelBundle\Entity\RotaVirus",inversedBy="lab")
     * @ORM\JoinColumn(nullable=false,unique=true)
     */
    protected $case;

    //------------------------------
    // Site Lab Specific
    /**
     * @var \DateTime $siteReceivedDate
     * @ORM\Column(name="siteReceivedDate",type="date",nullable=true)
     */
    private $siteReceivedDate;

    /**
     * @var string $siteLabId
     * @ORM\Column(name="siteLabId",type="string",nullable=true)
     */
    private $siteLabId;

    //------------------------------
    // National Lab Specific
    /**
     * @var TripleChoice $sentToNL
     * @ORM\Column(name="sentToNL",type="TripleChoice",nullable=true)
     */
    private $sentToNL;

    /**
     * @var \DateTime $sentToNLDate
     * @ORM\Column(name="sentToNLDate",type="date",nullable=true)
     */
    private $sentToNLDate;

    /**
     * @var \DateTime $nlReceivedDate
     * @ORM\Column(name="nlReceivedDate",type="date",nullable=true)
     */
    private $nlReceivedDate;

    /**
     * @var string $nlLabId
     * @ORM\Column(name="nlLabId",type="string",nullable=true)
     */
    private $nlLabId;

    //------------------------------
    // Regional Reference Lab Specific
    /**
     * @var TripleChoice $sentToRRL
     * @ORM\Column(name="sentToRRL",type="TripleChoice",nullable=true)
     */
    private $sentToRRL;

    /**
     * @var \DateTime $sentToRRLDate
     * @ORM\Column(name="sentToRRLDate",type="date",nullable=true)
     */
    private $sentToRRLDate;

    /**
     * @var \DateTime $rrlReceivedDate
     * @ORM\Column(name="rrlReceivedDate",type="date",nullable=true)
     */
    private $rrlReceivedDate;

    /**
     * @var string $rrlLabId
     * @ORM\Column(name="rrlLabId",type="string",nullable=true)
     */
    private $rrlLabId;

    //---------------------------------
    // Global Variables
    /**
     * stool_adequate
     * @var TripleChoice $adequate
     * @ORM\Column(name="adequate",type="TripleChoice",nullable=true)
     */
    private $adequate;

    /**
     * @var TripleChoice $stored
     * @ORM\Column(name="stored",type="TripleChoice",nullable=true)
     */
    private $stored;

    /**
     * @var TripleChoice $elisaDone
     * @ORM\Column(name="elisaDone",type="TripleChoice",nullable=true)
     */
    private $elisaDone;

    /**
     * @var ElisaKit $elisaKit
     * @ORM\Column(name="elisaKit",type="ElisaKit",nullable=true)
     */
    private $elisaKit;

    /**
     * @var string $elisaKitOther
     * @ORM\Column(name="elisaKitOther",type="string",nullable=true)
     */
    private $elisaKitOther;

    /**
     * @var string $elisaLoadNumber
     * @ORM\Column(name="elisaLoadNumber",type="string",nullable=true)
     */
    private $elisaLoadNumber;

    /**
     * @var \DateTime $elisaExpiryDate
     * @ORM\Column(name="elisaExpiryDate",type="date",nullable=true)
     */
    private $elisaExpiryDate;

    /**
     * @var \DateTime $testDate
     * @ORM\Column(name="elisaTestDate",type="date",nullable=true)
     */
    private $elisaTestDate;

    /**
     * @var ElisaResult $elisaResult
     * @ORM\Column(name="elisaResult",type="ElisaResult",nullable=true)
     */
    private $elisaResult;

    /**
     * @var TripleChoice $secondaryElisaDone
     * @ORM\Column(name="secondaryElisaDone",type="TripleChoice",nullable=true)
     */
    private $secondaryElisaDone;

    /**
     * @var ElisaKit $secondaryElisaKit
     * @ORM\Column(name="secondaryElisaKit",type="ElisaKit",nullable=true)
     */
    private $secondaryElisaKit;

    /**
     * @var string $secondaryElisaKitOther
     * @ORM\Column(name="secondaryElisaKitOther",type="string",nullable=true)
     */
    private $secondaryElisaKitOther;

    /**
     * @var string $secondaryElisaLoadNumber
     * @ORM\Column(name="secondaryElisaLoadNumber",type="string",nullable=true)
     */
    private $secondaryElisaLoadNumber;

    /**
     * @var \DateTime $secondaryElisaExpiryDate
     * @ORM\Column(name="secondaryElisaExpiryDate",type="date",nullable=true)
     */
    private $secondaryElisaExpiryDate;

    /**
     * @var \DateTime $testDate
     * @ORM\Column(name="secondaryElisaTestDate",type="date",nullable=true)
     */
    private $secondaryElisaTestDate;

    /**
     * @var ElisaResult $secondaryElisaResult
     * @ORM\Column(name="secondaryElisaResult",type="ElisaResult",nullable=true)
     */
    private $secondaryElisaResult;

    /**
     * @var \DateTime $genotypingDate
     * @ORM\Column(name="genotypingDate",type="date", nullable=true)
     */
    private $genotypingDate;

    /**
     * @var GenotypeResultG $genotypingResultg
     * @ORM\Column(name="genotypingResultg",type="GenotypeResultG", nullable=true)
     */
    private $genotypingResultg;

    /**
     * @var string $genotypingResultGSpecify
     * @ORM\Column(name="genotypingResultGSpecify",type="string", nullable=true)
     */
    private $genotypingResultGSpecify;

    /**
     * @var GenotypeResultP $genotypeResultP
     * @ORM\Column(name="genotypeResultP",type="GenotypeResultP", nullable=true)
     */
    private $genotypeResultP;

    /**
     * @var string $genotypeResultPSpecify
     * @ORM\Column(name="genotypeResultPSpecify",type="string", nullable=true)
     */
    private $genotypeResultPSpecify;

    public function getCaseClass()
    {
        return $this->caseClass;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCase()
    {
        return $this->case;
    }

    public function getSiteReceivedDate()
    {
        return $this->siteReceivedDate;
    }

    public function getSiteLabId()
    {
        return $this->siteLabId;
    }

    public function getSentToNL()
    {
        return $this->sentToNL;
    }

    public function getSentToNLDate()
    {
        return $this->sentToNLDate;
    }

    public function getNlReceivedDate()
    {
        return $this->nlReceivedDate;
    }

    public function getNlLabId()
    {
        return $this->nlLabId;
    }

    public function getSentToRRL()
    {
        return $this->sentToRRL;
    }

    public function getSentToRRLDate()
    {
        return $this->sentToRRLDate;
    }

    public function getRrlReceivedDate()
    {
        return $this->rrlReceivedDate;
    }

    public function getRrlLabId()
    {
        return $this->rrlLabId;
    }

    public function getAdequate()
    {
        return $this->adequate;
    }

    public function getStored()
    {
        return $this->stored;
    }

    public function getElisaDone()
    {
        return $this->elisaDone;
    }

    public function getElisaKit()
    {
        return $this->elisaKit;
    }

    public function getElisaLoadNumber()
    {
        return $this->elisaLoadNumber;
    }

    public function getElisaExpiryDate()
    {
        return $this->elisaExpiryDate;
    }

    public function getElisaTestDate()
    {
        return $this->elisaTestDate;
    }

    public function getElisaResult()
    {
        return $this->elisaResult;
    }

    public function getSecondaryElisaDone()
    {
        return $this->secondaryElisaDone;
    }

    public function getSecondaryElisaKit()
    {
        return $this->secondaryElisaKit;
    }

    public function getSecondaryElisaLoadNumber()
    {
        return $this->secondaryElisaLoadNumber;
    }

    public function getSecondaryElisaExpiryDate()
    {
        return $this->secondaryElisaExpiryDate;
    }

    public function getSecondaryElisaTestDate()
    {
        return $this->secondaryElisaTestDate;
    }

    public function getSecondaryElisaResult()
    {
        return $this->secondaryElisaResult;
    }

    public function getGenotypingDate()
    {
        return $this->genotypingDate;
    }

    public function getGenotypingResultg()
    {
        return $this->genotypingResultg;
    }

    public function getGenotypingResultGSpecify()
    {
        return $this->genotypingResultGSpecify;
    }

    public function getGenotypeResultP()
    {
        return $this->genotypeResultP;
    }

    public function getGenotypeResultPSpecify()
    {
        return $this->genotypeResultPSpecify;
    }

    public function getElisaKitOther()
    {
        return $this->elisaKitOther;
    }

    public function getSecondaryElisaKitOther()
    {
        return $this->secondaryElisaKitOther;
    }

    public function setElisaKitOther($elisaKitOther)
    {
        $this->elisaKitOther = $elisaKitOther;
        return $this;
    }

    public function setSecondaryElisaKitOther($secondaryElisaKitOther)
    {
        $this->secondaryElisaKitOther = $secondaryElisaKitOther;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setSiteReceivedDate($siteReceivedDate)
    {
        $this->siteReceivedDate = $siteReceivedDate;
        return $this;
    }

    public function setSiteLabId($siteLabId)
    {
        $this->siteLabId = $siteLabId;
        return $this;
    }

    public function setSentToNL(TripleChoice $sentToNL)
    {
        $this->sentToNL = $sentToNL;
        return $this;
    }

    public function setSentToNLDate($sentToNLDate)
    {
        $this->sentToNLDate = $sentToNLDate;
        return $this;
    }

    public function setNlReceivedDate($nlReceivedDate)
    {
        $this->nlReceivedDate = $nlReceivedDate;
        return $this;
    }

    public function setNlLabId($nlLabId)
    {
        $this->nlLabId = $nlLabId;
        return $this;
    }

    public function setSentToRRL(TripleChoice $sentToRRL)
    {
        $this->sentToRRL = $sentToRRL;
        return $this;
    }

    public function setSentToRRLDate($sentToRRLDate)
    {
        $this->sentToRRLDate = $sentToRRLDate;
        return $this;
    }

    public function setRrlReceivedDate($rrlReceivedDate)
    {
        $this->rrlReceivedDate = $rrlReceivedDate;
        return $this;
    }

    public function setRrlLabId($rrlLabId)
    {
        $this->rrlLabId = $rrlLabId;
        return $this;
    }

    public function setAdequate(TripleChoice $adequate)
    {
        $this->adequate = $adequate;
        return $this;
    }

    public function setStored(TripleChoice $stored)
    {
        $this->stored = $stored;
        return $this;
    }

    public function setElisaDone(TripleChoice $elisaDone)
    {
        $this->elisaDone = $elisaDone;
        return $this;
    }

    public function setElisaKit(ElisaKit $elisaKit)
    {
        $this->elisaKit = $elisaKit;
        return $this;
    }

    public function setElisaLoadNumber($elisaLoadNumber)
    {
        $this->elisaLoadNumber = $elisaLoadNumber;
        return $this;
    }

    public function setElisaExpiryDate($elisaExpiryDate)
    {
        $this->elisaExpiryDate = $elisaExpiryDate;
        return $this;
    }

    public function setElisaTestDate($elisaTestDate)
    {
        $this->elisaTestDate = $elisaTestDate;
        return $this;
    }

    public function setElisaResult(ElisaResult $elisaResult)
    {
        $this->elisaResult = $elisaResult;
        return $this;
    }

    public function setSecondaryElisaDone(TripleChoice $secondaryElisaDone)
    {
        $this->secondaryElisaDone = $secondaryElisaDone;
        return $this;
    }

    public function setSecondaryElisaKit(ElisaKit $secondaryElisaKit)
    {
        $this->secondaryElisaKit = $secondaryElisaKit;
        return $this;
    }

    public function setSecondaryElisaLoadNumber($secondaryElisaLoadNumber)
    {
        $this->secondaryElisaLoadNumber = $secondaryElisaLoadNumber;
        return $this;
    }

    public function setSecondaryElisaExpiryDate($secondaryElisaExpiryDate)
    {
        $this->secondaryElisaExpiryDate = $secondaryElisaExpiryDate;
        return $this;
    }

    public function setSecondaryElisaTestDate($secondaryElisaTestDate)
    {
        $this->secondaryElisaTestDate = $secondaryElisaTestDate;
        return $this;
    }

    public function setSecondaryElisaResult(ElisaResult $secondaryElisaResult)
    {
        $this->secondaryElisaResult = $secondaryElisaResult;
        return $this;
    }

    public function setGenotypingDate($genotypingDate)
    {
        $this->genotypingDate = $genotypingDate;
        return $this;
    }

    public function setGenotypingResultg(GenotypeResultG $genotypingResultg)
    {
        $this->genotypingResultg = $genotypingResultg;
        return $this;
    }

    public function setGenotypingResultGSpecify($genotypingResultGSpecify)
    {
        $this->genotypingResultGSpecify = $genotypingResultGSpecify;
        return $this;
    }

    public function setGenotypeResultP(GenotypeResultP $genotypeResultP)
    {
        $this->genotypeResultP = $genotypeResultP;
        return $this;
    }

    public function setGenotypeResultPSpecify($genotypeResultPSpecify)
    {
        $this->genotypeResultPSpecify = $genotypeResultPSpecify;
        return $this;
    }
}
