<?php

namespace NS\SentinelBundle\Entity\Rota;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Entity\BaseSiteLab;
use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\ElisaResult;

/**
 * Description of RotaVirusSiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\Rota\SiteLab")
 * @ORM\Table(name="rotavirus_site_labs")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB","ROLE_NL_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
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
    private $case;

//v. Case-based Specimen Collection Data
    /**
     * stool_received_date
     * @var \DateTime $stoolReceivedDate
     * @ORM\Column(name="stoolReceivedDate",type="date",nullable=true)
     */
    private $stoolReceivedDate;

    /**
     * stool_adequate
     * @var TripleChoice $stoolAdequate
     * @ORM\Column(name="stoolAdequate",type="TripleChoice",nullable=true)
     */
    private $stoolAdequate;

//vi. Case-based Laboratory Data
    /**
     * stool_ELISA_done
     * @var TripleChoice $stoolELISADone
     * @ORM\Column(name="stoolELISADone",type="TripleChoice",nullable=true)
     */
    private $stoolELISADone;

    /**
     * stool_test_date
     * @var \DateTime $stoolTestDate
     * @ORM\Column(name="stoolTestDate",type="date",nullable=true)
     */
    private $stoolTestDate;

    /**
     * stool_ELISA_result
     * @var ElisaResult $stoolELISAResult
     * @ORM\Column(name="stoolELISAResult",type="ElisaResult",nullable=true)
     */
    private $stoolELISAResult;

    /**
     * stool_stored
     * @var TripleChoice $stoolStored
     * @ORM\Column(name="stoolStored",type="TripleChoice",nullable=true)
     */
    private $stoolStored;

    /**
     * RRL_stool_sent
     * @var TripleChoice $stoolSentToRRL
     * @ORM\Column(name="stoolSentToRRL",type="TripleChoice",nullable=true)
     */
    private $stoolSentToRRL; // These are duplicated from the boolean fields in the class we extend

    /**
     * RRL_stool_date
     * @var \DateTime $stoolSentToRRLDate
     * @ORM\Column(name="stoolSentToRRLDate",type="date",nullable=true)
     */
    private $stoolSentToRRLDate;

    /**
     * NL_stool_sent
     * @var TripleChoice $stoolSentToNL
     * @ORM\Column(name="stoolSentToNL",type="TripleChoice",nullable=true)
     */
    private $stoolSentToNL; // These are duplicated from the boolean fields in the class we extend

    /**
     * NL_stool_date
     * @var \DateTime $stoolSentToNLDate
     * @ORM\Column(name="stoolSentToNLDate",type="date",nullable=true)
     */
    private $stoolSentToNLDate;

    public function __construct($virus = null)
    {
        if($virus instanceof RotaVirus)
            $this->case = $virus;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCase()
    {
        return $this->case;
    }

    public function getStoolReceivedDate()
    {
        return $this->stoolReceivedDate;
    }

    public function getStoolAdequate()
    {
        return $this->stoolAdequate;
    }

    public function getStoolELISADone()
    {
        return $this->stoolELISADone;
    }

    public function getStoolTestDate()
    {
        return $this->stoolTestDate;
    }

    public function getStoolELISAResult()
    {
        return $this->stoolELISAResult;
    }

    public function getStoolStored()
    {
        return $this->stoolStored;
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

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCase($case)
    {
        $this->case = $case;
        return $this;
    }

    public function setStoolReceivedDate($stoolReceivedDate)
    {
        $this->stoolReceivedDate = $stoolReceivedDate;
        return $this;
    }

    public function setStoolAdequate(TripleChoice $stoolAdequate)
    {
        $this->stoolAdequate = $stoolAdequate;
        return $this;
    }

    public function setStoolELISADone(TripleChoice $stoolELISADone)
    {
        $this->stoolELISADone = $stoolELISADone;
        return $this;
    }

    public function setStoolTestDate($stoolTestDate)
    {
        $this->stoolTestDate = $stoolTestDate;
        return $this;
    }

    public function setStoolELISAResult(ElisaResult $stoolELISAResult)
    {
        $this->stoolELISAResult = $stoolELISAResult;
        return $this;
    }

    public function setStoolStored(TripleChoice $stoolStored)
    {
        $this->stoolStored = $stoolStored;
        return $this;
    }

    public function setStoolSentToRRL(TripleChoice $stoolSentToRRL)
    {
        $this->setSentToReferenceLab($stoolSentToRRL->equal(TripleChoice::YES));
        $this->stoolSentToRRL = $stoolSentToRRL;
        return $this;
    }

    public function setStoolSentToRRLDate( $stoolSentToRRLDate)
    {
        $this->stoolSentToRRLDate = $stoolSentToRRLDate;
        return $this;
    }

    public function setStoolSentToNL(TripleChoice $stoolSentToNL)
    {
        $this->setSentToNationalLab($stoolSentToNL->equal(TripleChoice::YES));
        $this->stoolSentToNL = $stoolSentToNL;
        return $this;
    }

    public function setStoolSentToNLDate($stoolSentToNLDate)
    {
        $this->stoolSentToNLDate = $stoolSentToNLDate;
        return $this;
    }

    public function isComplete()
    {
     
    }
}