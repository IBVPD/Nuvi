<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use NS\SecurityBundle\Annotation as Security;
use JMS\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Region
 *
 * @ORM\Table(name="regions",uniqueConstraints={@ORM\UniqueConstraint(name="region_code_idx",columns={"code"})})
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\RegionRepository")
 * @Security\Secured(conditions={
 *      @Security\SecuredCondition(roles={"ROLE_REGION"},field="id"),
 *      })
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @UniqueEntity(fields={"code"})
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
     * @Groups({"api"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     * @Groups({"api"})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255,nullable=true)
     * @Assert\Url()
     */
    private $website;

    /**
     * @var Country
     * 
     * @ORM\OneToMany(targetEntity="Country",mappedBy="region")
     */

    private $countries;

    /**
     * @param null $id
     * @param null $name
     */
    public function __construct($id = null, $name = null, $code = null)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->code      = $code;
        $this->countries = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

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
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Add countries
     *
     * @param Country $countries
     * @return Region
     */
    public function addCountrie(Country $countries)
    {
        $this->countries[] = $countries;
    
        return $this;
    }

    /**
     * Remove countries
     *
     * @param Country $countries
     */
    public function removeCountrie(Country $countries)
    {
        $this->countries->removeElement($countries);
    }

    /**
     * Get countries
     *
     * @return Collection
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

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->code,
            $this->website,
        ));
    }

    /**
     * @param string $serialized
     */
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
