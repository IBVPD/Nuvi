<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use NS\SecurityBundle\Annotation\Secured;
use NS\SecurityBundle\Annotation\SecuredCondition;

use NS\SentinelBundle\Form\Types\TripleChoice;

/**
 * Description of RotaVirusSiteLab
 * @author gnat
 * @ORM\Entity(repositoryClass="NS\SentinelBundle\Repository\RotaVirusSiteLab")
 * @ORM\Table(name="rotavirus_site_labs")
 * @Gedmo\Loggable
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},through={"case"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},through={"case"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB"},through="case",relation="site",class="NSSentinelBundle:Site"),
 *      })
 */
class RotaVirusSiteLab
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="RotaVirus",inversedBy="lab")
     * @ORM\JoinColumn(nullable=false,unique=true)
     */
    private $case;

//v. Case-based Specimen Collection Data
    /**
     * stool_received_date
     * @var DateTime $stoolReceivedDate
     * @ORM\Column(name="stoolReceivedDate",type="date")
     */
    private $stoolReceivedDate;

    /**
     * stool_adequate
     * @var TripleChoice $stoolAdequate
     * @ORM\Column(name="stoolAdequate",type="TripleChoice")
     */
    private $stoolAdequate;

//vi. Case-based Laboratory Data
    /**
     * stool_ELISA_done
     * @var TripleChoice $stoolELISADone
     * @ORM\Column(name="stoolELISADone",type="TripleChoice")
     */
    private $stoolELISADone;

    /**
     * stool_test_date
     * @var DateTime $stoolTestDate
     * @ORM\Column(name="stoolTestDate",type="date")
     */
    private $stoolTestDate;

    /**
     * stool_ELISA_result
     * @var ElisaResult $stoolELISAResult
     * @ORM\Column(name="stoolELISAResult",type="ElisaResult")
     */
    private $stoolELISAResult;

    /**
     * stool_stored
     * @var TripleChoice $stoolStored
     * @ORM\Column(name="stoolStored",type="TripleChoice")
     */
    private $stoolStored;

    /**
     * RRL_stool_sent
     * @var TripleChoice $stoolSentToRRL
     * @ORM\Column(name="stoolSentToRRL",type="TripleChoice")
     */
    private $stoolSentToRRL;

//RRL_name
    /**
     * RRL_stool_date
     * @var DateTime $stoolSentToRRLDate
     * @ORM\Column(name="stoolSentToRRLDate",type="date")
     */
    private $stoolSentToRRLDate;

    /**
     * RRL_ELISA_result
     * @var ElisaResult $rrlELISAResult
     * @ORM\Column(name="rrlELISAResult",type="ElisaResult")
 */
    private $rrlELISAResult;

    /**
     * RRL_genotype_date
     * @var DateTime $rrlGenoTypeDate
     * @ORM\Column(name="rrlGenoTypeDate",type="date")
     */
    private $rrlGenoTypeDate;

    /**
     * RRL_genotype_result
     * @var string $rrlGenoTypeResult
     * @ORM\Column(name="rrlGenoTypeResult",type="string")
     */
    private $rrlGenoTypeResult;

    /**
     * NL_stool_sent
     * @var TripleChoice $stoolSentToNL
     * @ORM\Column(name="stoolSentToNL",type="TripleChoice")
     */
    private $stoolSentToNL;

//NL_name
    /**
     * NL_stool_date
     * @var DateTime $stoolSentToNLDate
     * @ORM\Column(name="stoolSentToNLDate",type="date")
     */
    private $stoolSentToNLDate;

    /**
     * NL_ELISA_result
     * @var ElisaResult $nlELISAResult
     * @ORM\Column(name="nlELISAResult",type="ElisaResult")
     */
    private $nlELISAResult;

    /**
     * NL_genotype_date
     * @var DateTime $nlGenoTypeDate
     * @ORM\Column(name="nlGenoTypeDate",type="date")
     */
    private $nlGenoTypeDate;

    /**
     * NL_genotype_result
     * @var string $nlGenoTypeResult
     * @ORM\Column(name="nlGenoTypeResult",type="string")
     */
    private $nlGenoTypeResult;

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

    public function getRrlELISAResult()
    {
        return $this->rrlELISAResult;
    }

    public function getRrlGenoTypeDate()
    {
        return $this->rrlGenoTypeDate;
    }

    public function getRrlGenoTypeResult()
    {
        return $this->rrlGenoTypeResult;
    }

    public function getStoolSentToNL()
    {
        return $this->stoolSentToNL;
    }

    public function getStoolSentToNLDate()
    {
        return $this->stoolSentToNLDate;
    }

    public function getNlELISAResult()
    {
        return $this->nlELISAResult;
    }

    public function getNlGenoTypeDate()
    {
        return $this->nlGenoTypeDate;
    }

    public function getNlGenoTypeResult()
    {
        return $this->nlGenoTypeResult;
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

    public function setStoolReceivedDate(DateTime $stoolReceivedDate)
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

    public function setStoolTestDate(DateTime $stoolTestDate)
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
        $this->stoolSentToRRL = $stoolSentToRRL;
        return $this;
    }

    public function setStoolSentToRRLDate(DateTime $stoolSentToRRLDate)
    {
        $this->stoolSentToRRLDate = $stoolSentToRRLDate;
        return $this;
    }

    public function setRrlELISAResult(ElisaResult $rrlELISAResult)
    {
        $this->rrlELISAResult = $rrlELISAResult;
        return $this;
    }

    public function setRrlGenoTypeDate(DateTime $rrlGenoTypeDate)
    {
        $this->rrlGenoTypeDate = $rrlGenoTypeDate;
        return $this;
    }

    public function setRrlGenoTypeResult($rrlGenoTypeResult)
    {
        $this->rrlGenoTypeResult = $rrlGenoTypeResult;
        return $this;
    }

    public function setStoolSentToNL(TripleChoice $stoolSentToNL)
    {
        $this->stoolSentToNL = $stoolSentToNL;
        return $this;
    }

    public function setStoolSentToNLDate(DateTime $stoolSentToNLDate)
    {
        $this->stoolSentToNLDate = $stoolSentToNLDate;
        return $this;
    }

    public function setNlELISAResult(ElisaResult $nlELISAResult)
    {
        $this->nlELISAResult = $nlELISAResult;
        return $this;
    }

    public function setNlGenoTypeDate(DateTime $nlGenoTypeDate)
    {
        $this->nlGenoTypeDate = $nlGenoTypeDate;
        return $this;
    }

    public function setNlGenoTypeResult($nlGenoTypeResult)
    {
        $this->nlGenoTypeResult = $nlGenoTypeResult;
        return $this;
    }
}
