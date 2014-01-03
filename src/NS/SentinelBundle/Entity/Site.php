<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \NS\SecurityBundle\Annotation\Secured;
use \NS\SecurityBundle\Annotation\SecuredCondition;

/**
 * Site
 *
 * @ORM\Table(name="sites")
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\Site")
 * @Secured(conditions={
 *      @SecuredCondition(roles={"ROLE_REGION"},relation="region",through={"country"},class="NSSentinelBundle:Region"),
 *      @SecuredCondition(roles={"ROLE_COUNTRY"},relation="country",class="NSSentinelBundle:Country"),
 *      @SecuredCondition(roles={"ROLE_SITE","ROLE_LAB"},field="id"),
 *      }) 
 */
class Site
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
     */
    private $rvYearIntro;

    /**
     * @var integer $ibdYearIntro
     * @ORM\Column(name="ibdYearIntro",type="integer",nullable=true)
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
     */
    private $website;    

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

    public function __toString()
    {
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
        $this->cases = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add cases
     *
     * @param \NS\SentinelBundle\Entity\Meningitis $cases
     * @return Site
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
}