<?php

namespace NS\SentinelBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use \NS\SentinelBundle\Form\Types\GAVIEligible;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

/**
 * Country
 *
 * @ORM\Table(name="countries",uniqueConstraints={@ORM\UniqueConstraint(name="code_idx", columns={"code"})})
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\Country")
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},field="id"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},relation="sites",class="NSSentinelBundle:Site"),
 *      })
 */
class Country implements \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=4)
     */
    private $code;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="isActive",type="boolean")
     */
    private $isActive;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var GAVIEligible
     * @ORM\Column(name="gaviEligible",type="GAVIEligible",nullable=true)
     */
    private $gaviEligible;
    
    /**
     * @var integer $population
     * @ORM\Column(name="population",type="integer",nullable=true,nullable=true)
     */
    private $population;
    
    /**
     * @var integer $populationUnderFive
     * @ORM\Column(name="populationUnderFive",type="integer",nullable=true)
     */
    private $populationUnderFive;
    
    /**
     * @var Site
     * 
     * @ORM\OneToMany(targetEntity="Site", mappedBy="country")
     */
    private $sites;
    
    /**
     * @var Region
     * 
     * @ORM\ManyToOne(targetEntity="Region",inversedBy="countries")
     */
    private $region;

    /**
     * @var Meningitis
     * @ORM\OneToMany(targetEntity="Meningitis",mappedBy="country")
     */
    private $meningitisCases;
    
    /**
     * @var RotaVirus
     * @ORM\OneToMany(targetEntity="RotaVirus",mappedBy="country")
     */
    private $rotavirusCases;

    /**
     * @var boolean $tracksPneumonia
     * @ORM\Column(name="tracksPneumonia",type="boolean")
     */
    private $tracksPneumonia = true;

    /**
     * @var boolean $hasReferenceLab
     * @ORM\Column(name="hasReferenceLab",type="boolean")
     */
    private $hasReferenceLab;

    /**
     * @var boolean $hasNationalLab
     * @ORM\Column(name="hasNationalLab",type="boolean")
     */
    private $hasNationalLab;

    /**
     * Set name
     *
     * @param string $name
     * @return Country
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
     * Constructor
     */
    public function __construct($name = null)
    {
        $this->name            = $name;
        $this->sites           = new ArrayCollection();
        $this->meningitisCases = new ArrayCollection();
        $this->rotavirusCases  = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add sites
     *
     * @param \NS\SentinelBundle\Entity\Site $sites
     * @return Country
     */
    public function addSite(\NS\SentinelBundle\Entity\Site $sites)
    {
        $this->sites[] = $sites;
    
        return $this;
    }

    /**
     * Remove sites
     *
     * @param \NS\SentinelBundle\Entity\Site $sites
     */
    public function removeSite(\NS\SentinelBundle\Entity\Site $sites)
    {
        $this->sites->removeElement($sites);
    }

    /**
     * Get sites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSites()
    {
        return $this->sites;
    }

    /**
     * Set region
     *
     * @param \NS\SentinelBundle\Entity\Region $region
     * @return Country
     */
    public function setRegion(\NS\SentinelBundle\Entity\Region $region = null)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return \NS\SentinelBundle\Entity\Region 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Country
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Country
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
     * Add meningitisCases
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $meningitisCases
     * @return Country
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
     * Set gaviEligible
     *
     * @param \GAVIEligible $gaviEligible
     * @return Country
     */
    public function setGaviEligible(GAVIEligible $gaviEligible)
    {
        $this->gaviEligible = $gaviEligible;
    
        return $this;
    }

    /**
     * Get gaviEligible
     *
     * @return \GAVIEligible 
     */
    public function getGaviEligible()
    {
        return $this->gaviEligible;
    }

    /**
     * Set population
     *
     * @param integer $population
     * @return Country
     */
    public function setPopulation($population)
    {
        $this->population = $population;
    
        return $this;
    }

    /**
     * Get population
     *
     * @return integer 
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set populationUnderFive
     *
     * @param integer $populationUnderFive
     * @return Country
     */
    public function setPopulationUnderFive($populationUnderFive)
    {
        $this->populationUnderFive = $populationUnderFive;
    
        return $this;
    }

    /**
     * Get populationUnderFive
     *
     * @return integer 
     */
    public function getPopulationUnderFive()
    {
        return $this->populationUnderFive;
    }

    /**
     * Get tracksPneumonia
     *
     * @return boolean
     */
    public function getTracksPneumonia()
    {
        return $this->tracksPneumonia;
    }

    /**
     * Set tracksPneumonia
     *
     * @param boolean $tracksPneumonia
     * @return Country
     */
    public function setTracksPneumonia($tracksPneumonia)
    {
        $this->tracksPneumonia = $tracksPneumonia;

        return $this;
    }

    /**
     * Get hasReferenceLab
     *
     * @return boolean
     */
    public function hasReferenceLab()
    {
        return $this->hasReferenceLab;
    }

    /**
     * Get hasNationalLab
     *
     * @return boolean
     */
    public function hasNationalLab()
    {
        return $this->hasNationalLab;
    }

    /**
     * Set hasReferenceLab
     * 
     * @param boolean $hasReferenceLab
     * @return Country
     */
    public function setHasReferenceLab($hasReferenceLab)
    {
        $this->hasReferenceLab = $hasReferenceLab;
        return $this;
    }

    /**
     * Set hasNationalLab
     *
     * @param boolean $hasNationalLab
     * @return Country
     */
    public function setHasNationalLab($hasNationalLab)
    {
        $this->hasNationalLab = $hasNationalLab;
        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->code,
            $this->isActive,
            $this->name,
            $this->gaviEligible,
            $this->population,
            $this->populationUnderFive,
            $this->region,
            $this->hasNationalLab,
            $this->hasReferenceLab,
            $this->tracksPneumonia,
            ));
    }

    public function unserialize($serialized)
    {
        list($this->id,
            $this->code,
            $this->isActive,
            $this->name,
            $this->gaviEligible,
            $this->population,
            $this->populationUnderFive,
            $this->region,
            $this->hasNationalLab,
            $this->hasReferenceLab,
            $this->tracksPneumonia
            ) = unserialize($serialized);
    }

    /**
     * Get hasReferenceLab
     *
     * @return boolean 
     */
    public function getHasReferenceLab()
    {
        return $this->hasReferenceLab;
    }

    /**
     * Get hasNationalLab
     *
     * @return boolean 
     */
    public function getHasNationalLab()
    {
        return $this->hasNationalLab;
    }
}