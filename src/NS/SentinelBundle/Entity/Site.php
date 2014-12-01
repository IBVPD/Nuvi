<?php

namespace NS\SentinelBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \JMS\Serializer\Annotation\Groups;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
use \NS\SentinelBundle\Form\Types\IBDIntenseSupport;
use \NS\SentinelBundle\Form\Types\SurveillanceConducted;
use \Symfony\Component\Validator\Constraints as Assert;

/**
 * Site
 *
 * @ORM\Table(name="sites")
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\Site")
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",through={"country"},class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB","ROLE_NL_LAB"},field="code"),
 *      }) 
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Site implements \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     * @ORM\Id
     * @Groups({"user","api"})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Groups({"user","api"})
     */
    private $name;

    /**
     * @var integer $rvYearIntro
     * @ORM\Column(name="rvYearIntro",type="integer",nullable=true)
     * @Assert\GreaterThan(value=1900)
     * @Groups({"user"})
     */
    private $rvYearIntro;

    /**
     * @var integer $ibdYearIntro
     * @ORM\Column(name="ibdYearIntro",type="integer",nullable=true)
     * @Assert\GreaterThan(value=1900)
     * @Groups({"user"})
     */
    private $ibdYearIntro;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255,nullable=true)
     * @Groups({"user"})
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255,nullable=true)
     * @Groups({"user"})
     */
    private $city;

    /**
     * @var integer $numberOfBeds
     * @ORM\Column(name="numberOfBeds",type="integer",nullable=true)
     * @Groups({"user"})
     */
    private $numberOfBeds;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255,nullable=true)
     * @Assert\Url()
     * @Groups({"user"})
     */
    private $website;    

    /**
     * @var integer
     *
     * @ORM\Column(name="currentCaseId", type="integer")
     * @Groups({"user"})
     */
    private $currentCaseId = 1;

    /**
     * @var SurveillanceConducted $surveillanceConducted
     * @ORM\Column(name="surveillanceConducted",type="SurveillanceConducted",nullable=false)
     */
    private $surveillanceConducted;

    /**
     * @var integer $ibdTier
     * @ORM\Column(name="ibdTier",type="integer",nullable=true)
     */
    private $ibdTier;

    /**
     * @var IBDIntenseSupport $ibdIntenseSupport
     * @ORM\Column(name="ibdIntenseSupport",type="IBDIntenseSupport",nullable=true)
     */
    private $ibdIntenseSupport;

    /**
     * @var \DateTime $ibdLastSiteAssessmentDate
     * @ORM\Column(name="ibdLastSiteAssessment",type="date",nullable=true)
     */
    private $ibdLastSiteAssessmentDate;

    /**
     * @var integer $ibdSiteAssessmentScore
     * @ORM\Column(name="ibdSiteAssessmentScore",type="integer",nullable=true)
     */
    private $ibdSiteAssessmentScore;

    /**
     * @var \DateTime $rvLastSiteAssessmentDate
     * @ORM\Column(name="rvLastSiteAssessmentDate",type="date",nullable=true)
     */
    private $rvLastSiteAssessmentDate;

    /**
     * @var string $ibvpdRl
     * @ORM\Column(name="ibvpdRl",type="string",nullable=true)
     */
    private $ibvpdRl;

    /**
     * @var string $rvRl
     * @ORM\Column(name="rvRl",type="string",nullable=true)
     */
    private $rvRl;

    /**
     * @var string $ibdEqaCode
     * @ORM\Column(name="ibdEqaCode",type="string",nullable=true)
     */
    private $ibdEqaCode;

    /**
     * @var string $rvEqaCode
     * @ORM\Column(name="rvEqaCode",type="string",nullable=true)
     */
    private $rvEqaCode;

    /**
     * @var Country
     * 
     * @ORM\ManyToOne(targetEntity="Country",inversedBy="sites")
     * @Groups({"user"})
     */
    private $country;

    //Fields used for reporting etc...

    private $totalCases;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->code;
    }

    public function hasId()
    {
        return (!empty($this->code) || (is_integer($this->code) && $this->code == 0) || !is_null($this->code));
    }

    public function setId($id)
    {
        return $this->code = $id;
    }

    public function __toString()
    {
        if(mb_strlen($this->name,'UTF-8') > 20)
            return mb_substr ($this->name, 0, 31,'UTF-8')."...";
        else
            return $this->name;
    }    

    /**
     * Set name
     *
     * @param string $name
     * @return Site
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return Site
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Site
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set rvYearIntro
     *
     * @param integer $rvYearIntro
     * @return Site
     */
    public function setRvYearIntro($rvYearIntro)
    {
        $this->rvYearIntro = $rvYearIntro;
    
        return $this;
    }

    /**
     * Get rvYearIntro
     *
     * @return integer 
     */
    public function getRvYearIntro()
    {
        return $this->rvYearIntro;
    }

    /**
     * Set ibdYearIntro
     *
     * @param integer $ibdYearIntro
     * @return Site
     */
    public function setIbdYearIntro($ibdYearIntro)
    {
        $this->ibdYearIntro = $ibdYearIntro;
    
        return $this;
    }

    /**
     * Get ibdYearIntro
     *
     * @return integer 
     */
    public function getIbdYearIntro()
    {
        return $this->ibdYearIntro;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Site
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Site
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set numberOfBeds
     *
     * @param integer $numberOfBeds
     * @return Site
     */
    public function setNumberOfBeds($numberOfBeds)
    {
        $this->numberOfBeds = $numberOfBeds;
    
        return $this;
    }

    /**
     * Get numberOfBeds
     *
     * @return integer 
     */
    public function getNumberOfBeds()
    {
        return $this->numberOfBeds;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Site
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    public function getCurrentCaseId()
    {
        return $this->currentCaseId;
    }

    public function setCurrentCaseId($currentCaseId)
    {
        $this->currentCaseId = $currentCaseId;
        return $this;
    }

    public function getIbdTier()
    {
        return $this->ibdTier;
    }

    public function getIbdIntenseSupport()
    {
        return $this->ibdIntenseSupport;
    }

    public function getIbdLastSiteAssessmentDate()
    {
        return $this->ibdLastSiteAssessmentDate;
    }

    public function getIbdSiteAssessmentScore()
    {
        return $this->ibdSiteAssessmentScore;
    }

    public function getRvLastSiteAssessmentDate()
    {
        return $this->rvLastSiteAssessmentDate;
    }

    public function getIbvpdRl()
    {
        return $this->ibvpdRl;
    }

    public function getRvRl()
    {
        return $this->rvRl;
    }

    public function getIbdEqaCode()
    {
        return $this->ibdEqaCode;
    }

    public function getRvEqaCode()
    {
        return $this->rvEqaCode;
    }

    public function getSurveillanceConducted()
    {
        return $this->surveillanceConducted;
    }

    public function setSurveillanceConducted(SurveillanceConducted $surveillanceConducted)
    {
        $this->surveillanceConducted = $surveillanceConducted;
        return $this;
    }

    public function setIbdTier($ibdTier)
    {
        $this->ibdTier = $ibdTier;
        return $this;
    }

    public function setIbdIntenseSupport($ibdIntenseSupport)
    {
        $this->ibdIntenseSupport = $ibdIntenseSupport;
        return $this;
    }

    public function setIbdLastSiteAssessmentDate($ibdLastSiteAssessmentDate)
    {
        $this->ibdLastSiteAssessmentDate = $ibdLastSiteAssessmentDate;
        return $this;
    }

    public function setIbdSiteAssessmentScore($ibdSiteAssessmentScore)
    {
        $this->ibdSiteAssessmentScore = $ibdSiteAssessmentScore;
        return $this;
    }

    public function setRvLastSiteAssessmentDate($rvLastSiteAssessmentDate)
    {
        $this->rvLastSiteAssessmentDate = $rvLastSiteAssessmentDate;
        return $this;
    }

    public function setIbvpdRl($ibvpdRl)
    {
        $this->ibvpdRl = $ibvpdRl;
        return $this;
    }

    public function setRvRl($rvRl)
    {
        $this->rvRl = $rvRl;
        return $this;
    }

    public function setIbdEqaCode($ibdEqaCode)
    {
        $this->ibdEqaCode = $ibdEqaCode;
        return $this;
    }

    public function setRvEqaCode($rvEqaCode)
    {
        $this->rvEqaCode = $rvEqaCode;
        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->code,
            $this->name,
            $this->website,
            $this->rvYearIntro,
            $this->ibdYearIntro,
            $this->street,
            $this->city,
            $this->numberOfBeds,
            $this->ibdTier,
            $this->ibdIntenseSupport,
            $this->ibdLastSiteAssessmentDate,
            $this->ibdSiteAssessmentScore,
            $this->rvLastSiteAssessmentDate,
            $this->ibvpdRl,
            $this->rvRl,
            $this->ibdEqaCode,
            $this->rvEqaCode,
            $this->surveillanceConducted,
            $this->country,

        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->code,
            $this->name,
            $this->website,
            $this->rvYearIntro,
            $this->ibdYearIntro,
            $this->street,
            $this->city,
            $this->numberOfBeds,
            $this->ibdTier,
            $this->ibdIntenseSupport,
            $this->ibdLastSiteAssessmentDate,
            $this->ibdSiteAssessmentScore,
            $this->rvLastSiteAssessmentDate,
            $this->ibvpdRl,
            $this->rvRl,
            $this->ibdEqaCode,
            $this->rvEqaCode,
            $this->surveillanceConducted,
            $this->country,
             ) = unserialize($serialized);
    }

    public function getTotalCases()
    {
        return $this->totalCases;
    }

    public function setTotalCases($totalCases)
    {
        $this->totalCases = $totalCases;
        return $this;
    }
}