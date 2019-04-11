<?php

namespace NS\SentinelBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use NS\SentinelBundle\Form\Types\SurveillanceConducted;

/**
 * @ORM\Entity()
 * @ORM\Table(name="reference_labs")
 */
class ReferenceLab
{
    /**
     * @var string $id
     * @ORM\Column(name="id",type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /** @var mixed */
    private $userId;

    /**
     * @var string $name
     * @ORM\Column(name="name",type="string")
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(name="location",type="string",nullable=true)
     */
    private $location;

    /**
     * @var Collection $countries
     * @ORM\OneToMany(targetEntity="Country",mappedBy="referenceLab")
     */
    private $countries;

    /**
     * @var SurveillanceConducted|null
     * @ORM\Column(name="type",type="SurveillanceConducted",nullable=true)
     */
    private $type;

    /**
     * @var Collection $users
     * @ORM\OneToMany(targetEntity="User",mappedBy="referenceLab")
     */
    private $users;

    /**
     * @var Collection $ibdCases
     * @ORM\OneToMany(targetEntity="NS\SentinelBundle\Entity\IBD\ReferenceLab",mappedBy="lab")
     */
    private $ibdCases;

    /**
     * @var Collection $rotaCases
     * @ORM\OneToMany(targetEntity="NS\SentinelBundle\Entity\RotaVirus\ReferenceLab",mappedBy="lab")
     */
    private $rotaCases;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
        $this->ibdCases  = new ArrayCollection();
        $this->rotaCases = new ArrayCollection();
        $this->users     = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getType(): ?SurveillanceConducted
    {
        return $this->type;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getIbdCases(): Collection
    {
        return $this->ibdCases;
    }

    public function getRotaCases(): Collection
    {
        return $this->rotaCases;
    }

    public function setIbdCases(Collection $ibdCases)
    {
        $this->ibdCases = $ibdCases;
    }

    public function addIbdCase(IBD $case)
    {
        $case->setLab($this);
        $this->ibdCases->add($case);
    }

    public function removeIbdCase(IBD $case)
    {
        $this->ibdCases->removeElement($case);
    }

    public function setRotaCases(Collection $rotaCases)
    {
        $this->rotaCases = $rotaCases;
    }

    public function addRotaCase(RotaVirus $case)
    {
        $case->setLab($this);
        $this->rotaCases->add($case);
    }

    public function removeRotaCase(RotaVirus $case)
    {
        $this->rotaCases->removeElement($case);
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users)
    {
        $this->users = $users;
    }

    public function addUser(User $user)
    {
        $user->setReferenceLab($this);
        $this->users->add($user);
    }

    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setLocation(string $location)
    {
        $this->location = $location;
    }

    public function setType(SurveillanceConducted $type)
    {
        $this->type = $type;
    }

    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function setCountries($countries)
    {
        $this->countries = new ArrayCollection();

        foreach ($countries as $country) {
            $this->addCountry($country);
        }
    }

    public function addCountry(Country $country)
    {
        $country->setReferenceLab($this);
        $this->countries->add($country);
    }

    public function removeCountry(Country $country)
    {
        if ($this->countries->contains($country)) {
            $this->countries->removeElement($country);
        }
    }
}
