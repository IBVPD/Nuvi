<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use NS\SecurityBundle\Annotation as Security;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Region
 *
 * @ORM\Table(name="regions")
 * @ORM\Entity(repositoryClass="\NS\SentinelBundle\Repository\RegionRepository")
 * @Security\Secured(conditions={
 *      @Security\SecuredCondition(roles={"ROLE_SUPER_ADMIN"},enabled=false),
 *      @Security\SecuredCondition(roles={"ROLE_REGION"},field="code"),
 *      @Security\SecuredCondition(roles={"ROLE_COUNTRY"},through={"countries"},field="code"),
 *      })
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @UniqueEntity(fields={"code"})
 */
class Region implements \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=15)
     * @ORM\Id
     * @Groups({"api"})
     */
    private $code;

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
     * @param string|null $code
     * @param string|null $name
     */
    public function __construct($code = null, $name = null)
    {
        $this->name      = $name;
        $this->code      = $code;
        $this->countries = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name ?? '';
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->code;
    }

    /**
     * @param $id
     *
     * @return Region
     */
    public function setId($id)
    {
        return $this->setCode($id);
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
    public function addCountry(Country $countries)
    {
        $this->countries[] = $countries;
    
        return $this;
    }

    /**
     * Remove countries
     *
     * @param Country $countries
     */
    public function removeCountry(Country $countries)
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
        return serialize([
            $this->code,
            $this->name,
            $this->website,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        [$this->code, $this->name, $this->website] = unserialize($serialized, [__CLASS__]);
    }
}
