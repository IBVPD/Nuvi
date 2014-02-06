<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $cases;

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
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add cases
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $cases
     * @return Region
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