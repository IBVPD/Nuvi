<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Region
 *
 * @ORM\Table(name="regions")
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\Region")
 */
class Region implements \Serializable
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
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255,nullable=true)
     */
    private $website;

    /**
     * @var Country
     * 
     * @ORM\OneToMany(targetEntity="Country",mappedBy="region")
     */

    private $countries;
    
    /**
     *
     * @var Meningitis
     * @ORM\OneToMany(targetEntity="Meningitis",mappedBy="region")
     */
    private $meningitisCases;

    /**
     * @var RotaVirus
     * @ORM\OneToMany(targetEntity="RotaVirus",mappedBy="region")
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

    /**
     * Set name
     *
     * @param string $name
     * @return Region
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
        $this->countries       = new ArrayCollection();
        $this->meningitisCases = new ArrayCollection();
        $this->rotavirusCases  = new ArrayCollection();
    }
    
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Add countries
     *
     * @param \NS\SentinelBundle\Entity\Country $countries
     * @return Region
     */
    public function addCountrie(\NS\SentinelBundle\Entity\Country $countries)
    {
        $this->countries[] = $countries;
    
        return $this;
    }

    /**
     * Remove countries
     *
     * @param \NS\SentinelBundle\Entity\Country $countries
     */
    public function removeCountrie(\NS\SentinelBundle\Entity\Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Region
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
     * @return Region
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
     * Set website
     *
     * @param string $website
     * @return Region
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

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->code,
            $this->website,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->name,
            $this->code,
            $this->website,
             ) = unserialize($serialized);
    }
}