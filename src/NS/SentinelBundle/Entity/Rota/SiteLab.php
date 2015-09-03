<?php

namespace NS\SentinelBundle\Entity\Rota;

use \Doctrine\ORM\Mapping as ORM;
use \Gedmo\Mapping\Annotation as Gedmo;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
use \NS\SentinelBundle\Entity\BaseSiteLab;
use \NS\SentinelBundle\Form\Types\ElisaKit;
use \NS\SentinelBundle\Form\Types\ElisaResult;
use \NS\SentinelBundle\Form\Types\GenotypeResultG;
use \NS\SentinelBundle\Form\Types\GenotypeResultP;
use \NS\SentinelBundle\Form\Types\TripleChoice;
use \JMS\Serializer\Annotation\Groups;

/**
 * Description of RotaVirusSiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Rota\SiteLabRepository")
 * @ORM\Table(name="rotavirus_site_labs")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"caseFile"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY","ROLE_RRL_LAB","ROLE_NL_LAB"},through={"caseFile"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},through={"caseFile"},relation="site",class="NSSentinelBundle:Site"),
 *      })
 */
class SiteLab extends BaseSiteLab
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="NS\SentinelBundle\Entity\RotaVirus",inversedBy="siteLab")
     * @ORM\JoinColumn(nullable=false,unique=true)
     */
    protected $caseFile;
//---------------------------------
    // Global Variables
    /**
     * @var \DateTime $received
     * @ORM\Column(name="received",type="datetime",nullable=true)
     * @Groups({"api"})
     */
    private $received;

    /**
     * stool_adequate
     * @var TripleChoice $adequate
     * @ORM\Column(name="adequate",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $adequate;

    /**
     * @var TripleChoice $stored
     * @ORM\Column(name="stored",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $stored;

    /**
     * @var TripleChoice $elisaDone
     * @ORM\Column(name="elisaDone",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $elisaDone;

    /**
     * @var ElisaKit $elisaKit
     * @ORM\Column(name="elisaKit",type="ElisaKit",nullable=true)
     * @Groups({"api"})
     */
    private $elisaKit;

    /**
     * @var string $elisaKitOther
     * @ORM\Column(name="elisaKitOther",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $elisaKitOther;

    /**
     * @var string $elisaLoadNumber
     * @ORM\Column(name="elisaLoadNumber",type="string",nullable=true)
     * @Groups({"api"})
     */
    private $elisaLoadNumber;

    /**
     * @var \DateTime $elisaExpiryDate
     * @ORM\Column(name="elisaExpiryDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $elisaExpiryDate;

    /**
     * @var \DateTime $testDate
     * @ORM\Column(name="elisaTestDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $elisaTestDate;

    /**
     * @var ElisaResult $elisaResult
     * @ORM\Column(name="elisaResult",type="ElisaResult",nullable=true)
     * @Groups({"api"})
     */
    private $elisaResult;

    /**
     * @var \DateTime $genotypingDate
     * @ORM\Column(name="genotypingDate",type="date", nullable=true)
     * @Groups({"api"})
     */
    private $genotypingDate;

    /**
     * @var GenotypeResultG $genotypingResultG
     * @ORM\Column(name="genotypingResultG",type="GenotypeResultG", nullable=true)
     * @Groups({"api"})
     */
    private $genotypingResultG;

    /**
     * @var string $genotypingResultGSpecify
     * @ORM\Column(name="genotypingResultGSpecify",type="string", nullable=true)
     * @Groups({"api"})
     */
    private $genotypingResultGSpecify;

    /**
     * @var GenotypeResultP $genotypeResultP
     * @ORM\Column(name="genotypeResultP",type="GenotypeResultP", nullable=true)
     * @Groups({"api"})
     */
    private $genotypeResultP;

    /**
     * @var string $genotypeResultPSpecify
     * @ORM\Column(name="genotypeResultPSpecify",type="string", nullable=true)
     * @Groups({"api"})
     */
    private $genotypeResultPSpecify;

    /**
     * RRL_stool_sent
     * @var TripleChoice $stoolSentToRRL
     * @ORM\Column(name="stoolSentToRRL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $stoolSentToRRL; // These are duplicated from the boolean fields in the class we extend

    /**
     * RRL_stool_date
     * @var \DateTime $stoolSentToRRLDate
     * @ORM\Column(name="stoolSentToRRLDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $stoolSentToRRLDate;

    /**
     * NL_stool_sent
     * @var TripleChoice $stoolSentToNL
     * @ORM\Column(name="stoolSentToNL",type="TripleChoice",nullable=true)
     * @Groups({"api"})
     */
    private $stoolSentToNL; // These are duplicated from the boolean fields in the class we extend

    /**
     * NL_stool_date
     * @var \DateTime $stoolSentToNLDate
     * @ORM\Column(name="stoolSentToNLDate",type="date",nullable=true)
     * @Groups({"api"})
     */
    private $stoolSentToNLDate;

    public function __construct($virus = null)
    {
        if($virus instanceof RotaVirus)
            $this->caseFile = $virus;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getReceived()
    {
        return $this->received;
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

    public function getElisaKitOther()
    {
        return $this->elisaKitOther;
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

    public function getGenotypingDate()
    {
        return $this->genotypingDate;
    }

    public function getGenotypingResultg()
    {
        return $this->genotypingResultG;
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

    public function getStoolSentToRRL()
    {
        return $this->stoolSentToRRL;
    }

    public function getStoolSentToRRLDate()
    {
        return $this->stoolSentToRRLDate;
    }

    public function getStoolSentToNL()
    {
        return $this->stoolSentToNL;
    }

    public function getStoolSentToNLDate()
    {
        return $this->stoolSentToNLDate;
    }

    public function setReceived(\DateTime $received = null)
    {
        $this->received = $received;

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

    public function setElisaKitOther($elisaKitOther)
    {
        $this->elisaKitOther = $elisaKitOther;
        return $this;
    }

    public function setElisaLoadNumber($elisaLoadNumber)
    {
        $this->elisaLoadNumber = $elisaLoadNumber;
        return $this;
    }

    public function setElisaExpiryDate(\DateTime $elisaExpiryDate = null)
    {
        $this->elisaExpiryDate = $elisaExpiryDate;

        return $this;
    }

    public function setElisaTestDate(\DateTime $elisaTestDate = null)
    {
        $this->elisaTestDate = $elisaTestDate;

        return $this;
    }

    public function setElisaResult(ElisaResult $elisaResult)
    {
        $this->elisaResult = $elisaResult;
        return $this;
    }

    public function setGenotypingDate(\DateTime $genotypingDate = null)
    {
        $this->genotypingDate = $genotypingDate;

        return $this;
    }

    public function setGenotypingResultg(GenotypeResultG $genotypingResultG)
    {
        $this->genotypingResultG = $genotypingResultG;
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

    public function setStoolSentToRRL(TripleChoice $stoolSentToRRL)
    {
        $this->stoolSentToRRL = $stoolSentToRRL;
        return $this;
    }

    public function setStoolSentToRRLDate(\DateTime $stoolSentToRRLDate = null)
    {
        $this->stoolSentToRRLDate = $stoolSentToRRLDate;

        return $this;
    }

    public function setStoolSentToNL(TripleChoice $stoolSentToNL)
    {
        $this->stoolSentToNL = $stoolSentToNL;
        return $this;
    }

    public function setStoolSentToNLDate(\DateTime $stoolSentToNLDate = null)
    {
        $this->stoolSentToNLDate = $stoolSentToNLDate;

        return $this;
    }

    public function isComplete()
    {
     
    }
}