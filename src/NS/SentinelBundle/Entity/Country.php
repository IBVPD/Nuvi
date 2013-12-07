<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\GAVIEligible;

/**
 * Country
 *
 * @ORM\Table(name="countries",uniqueConstraints={@ORM\UniqueConstraint(name="code_idx", columns={"code"})})
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\Country")
 */
class Country
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
     *
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
     *
     * @var Meningitis
     * @ORM\OneToMany(targetEntity="Meningitis",mappedBy="country")
     */
    private $cases;
    
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
    public function __construct()
    {
        $this->sites = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add cases
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $cases
     * @return Country
     */
    public function addCase(\NS\SentinelBundle\Entity\Meningitis $cases)
    {
        $this->cases[] = $cases;
    
        return $this;
    }

    /**
     * Remove cases
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $cases
     */
    public function removeCase(\NS\SentinelBundle\Entity\Meningitis $cases)
    {
        $this->cases->removeElement($cases);
    }

    /**
     * Get cases
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCases()
    {
        return $this->cases;
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
}