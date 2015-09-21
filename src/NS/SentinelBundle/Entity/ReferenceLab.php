<?php

namespace NS\SentinelBundle\Entity;

use \Doctrine\Common\Collections\Collection;
use \Doctrine\ORM\Mapping as ORM;
use \NS\SentinelBundle\Form\Types\SurveillanceConducted;

/**
 * Description of ReferenceLab
 *
 * @author gnat
 * @ORM\Entity()
 * @ORM\Table(name="reference_labs")
 */
class ReferenceLab
{
    /**
     * @var string $id
     * @ORM\Column(name="id",type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\NS\SentinelBundle\Entity\Generator\ReferenceLabGenerator")
     */
    private $id;

    /**
     * @var
     */
    private $userId;

    /**
     * @var string $name
     * @ORM\Column(name="name",type="string")
     */
    private $name;

    /**
     * @var string $location
     * @ORM\Column(name="location",type="string",nullable=true)
     */
    private $location;

    /**
     * @var Country $country
     * @ORM\OneToMany(targetEntity="Country",mappedBy="referenceLab")
     */
    private $country;

    /**
     * @var SurveillanceConducted $type
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
     * @ORM\OneToMany(targetEntity="NS\SentinelBundle\Entity\Rota\ReferenceLab",mappedBy="lab")
     */
    private $rotaCases;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return ReferenceLabType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return Collection
     */
    public function getIbdCases()
    {
        return $this->ibdCases;
    }

    /**
     * @return Collection
     */
    public function getRotaCases()
    {
        return $this->rotaCases;
    }

    /**
     * @param Collection $ibdCases
     * @return $this
     */
    public function setIbdCases(Collection $ibdCases)
    {
        $this->ibdCases = $ibdCases;
        return $this;
    }

    /**
     * @param IBD $case
     * @return $this
     */
    public function addIbdCase(IBD $case)
    {
        $case->setLab($this);
        $this->ibdCases->add($case);

        return $this;
    }

    /**
     * @param IBD $case
     * @return $this
     */
    public function removeIbdCase(IBD $case)
    {
        $this->ibdCases->removeElement($case);

        return $this;
    }

    /**
     * @param Collection $rotaCases
     * @return $this
     */
    public function setRotaCases(Collection $rotaCases)
    {
        $this->rotaCases = $rotaCases;
        return $this;
    }

    /**
     * @param RotaVirus $case
     * @return $this
     */
    public function addRotaCase(RotaVirus $case)
    {
        $case->setLab($this);
        $this->rotaCases->add($case);

        return $this;
    }

    /**
     * @param RotaVirus $case
     * @return $this
     */
    public function removeRotaCase(RotaVirus $case)
    {
        $this->rotaCases->removeElement($case);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     * @return $this
     */
    public function setUsers(Collection $users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user)
    {
        $user->setReferenceLab($this);
        $this->users->add($user);

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @param string $userId
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param string $id
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $location
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @param \NS\SentinelBundle\Entity\Country $country
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param \NS\SentinelBundle\Forms\Type\SurveillanceConducted $type
     * @return \NS\SentinelBundle\Entity\ReferenceLab
     */
    public function setType(SurveillanceConducted $type)
    {
        $this->type = $type;
        return $this;
    }
}
