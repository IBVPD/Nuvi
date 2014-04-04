<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Site
 *
 * @ORM\Table(name="sites")
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\Site")
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",through={"country"},class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB","ROLE_RRL_LAB","ROLE_NL_LAB"},field="id"),
 *      }) 
 */
class Site implements \Serializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var integer $rvYearIntro
     * @ORM\Column(name="rvYearIntro",type="integer",nullable=true)
     * @Assert\GreaterThan(value=1900)
     */
    private $rvYearIntro;

    /**
     * @var integer $ibdYearIntro
     * @ORM\Column(name="ibdYearIntro",type="integer",nullable=true)
     * @Assert\GreaterThan(value=1900)
     */
    private $ibdYearIntro;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255,nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255,nullable=true)
     */
    private $city;

    /**
     * @var integer $numberOfBeds
     * @ORM\Column(name="numberOfBeds",type="integer",nullable=true)
     */
    private $numberOfBeds;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255,nullable=true)
     * @Assert\Url()
     */
    private $website;    

    /**
     * @var integer
     *
     * @ORM\Column(name="currentCaseId", type="integer")
     */
    private $currentCaseId = 1;

    /**
     * @var Country
     * 
     * @ORM\ManyToOne(targetEntity="Country",inversedBy="sites")
     */
    private $country;

    /**
     *
     * @var Meningitis
     * @ORM\OneToMany(targetEntity="Meningitis",mappedBy="site")
     */
    private $meningitisCases;

    /**
     * @var RotaVirus
     * @ORM\OneToMany(targetEntity="RotaVirus",mappedBy="site")
     */
    private $rotavirusCases;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        if(strlen($this->name) > 20)
            return mb_substr ($this->name, 0,31)."...";
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
     * @param \NS\SentinelBundle\Entity\Country $country
     * @return Site
     */
    public function setCountry(\NS\SentinelBundle\Entity\Country $country = null)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \NS\SentinelBundle\Entity\Country 
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
     * Constructor
     */
    public function __construct()
    {
        $this->meningitisCases = new ArrayCollection();
        $this->rotavirusCases  = new ArrayCollection();
    }
    
    /**
     * Add meningitisCases
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $meningitisCases
     * @return Site
     */
    public function addMeningitisCase(\NS\SentinelBundle\Entity\Meningitis $meningitisCases)
    {
        $this->meningitisCases[] = $meningitisCases;
    
        return $this;
    }

    /**
     * Remove meningitisCases
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $meningitisCases
     */
    public function removeMeningitisCase(\NS\SentinelBundle\Entity\Meningitis $meningitisCases)
    {
        $this->meningitisCases->removeElement($meningitisCases);
    }

    /**
     * Get meningitisCases
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMeningitisCases()
    {
        return $this->meningitisCases;
    }

    /**
     * Add rotavirusCases
     *
     * @param \NS\SentinelBundle\Entity\Rotavirus $rotavirusCases
     * @return Country
     */
    public function addRotavirusCase(\NS\SentinelBundle\Entity\Rotavirus $rotavirusCases)
    {
        $this->rotavirusCases[] = $rotavirusCases;

        return $this;
    }

    /**
     * Remove rotavirusCases
     *
     * @param \NS\SentinelBundle\Entity\Rotavirus $rotavirusCases
     */
    public function removeRotavirusCase(\NS\SentinelBundle\Entity\Rotavirus $rotavirusCases)
    {
        $this->rotavirusCases->removeElement($rotavirusCases);
    }

    /**
     * Get rotavirusCases
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRotavirusCases()
    {
        return $this->rotavirusCases;
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

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->code,
            $this->website,
            $this->rvYearIntro,
            $this->ibdYearIntro,
            $this->street,
            $this->city,
            $this->numberOfBeds,
            $this->country,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->name,
            $this->code,
            $this->website,
            $this->rvYearIntro,
            $this->ibdYearIntro,
            $this->street,
            $this->city,
            $this->numberOfBeds,
            $this->country,
             ) = unserialize($serialized);
    }
}