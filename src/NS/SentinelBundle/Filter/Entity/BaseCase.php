<?php

namespace NS\SentinelBundle\Filter\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Description of BaseCase
 *
 * @author gnat
 */
class BaseCase
{
    /**
     * @var string $id
     */
    private $id;

    /**
     * @var string $caseId
     */
    private $caseId;

    /**
     * @var $region
     */
    private $region;

    /**
     * @var $country
     */
    private $country;

    /**
     * @var $site
     */
    private $site;

    /**
     * @var string $firstName
     */
    private $firstName;

    /**
     * @var string $lastName
     */
    private $lastName;

    /**
     * @var $admDate
     */
    private $adm_date;

    /**
     * @var $status
     */
    private $status;

    /**
     * @var $createdAt
     */
    private $createdAt;

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
    public function getCaseId()
    {
        return $this->caseId;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getAdmDate()
    {
        return $this->adm_date;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param $caseId
     * @return $this
     */
    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;
        return $this;
    }

    /**
     * @param $region
     * @return $this
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @param $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param $site
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @param $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param $admDate
     * @return $this
     */
    public function setAdmDate($admDate)
    {
        $this->adm_date = $admDate;
        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param ExecutionContextInterface $context
     * @Assert\Callback(groups={"FieldPopulation"})
     */
    public function fieldPopulationValidation(ExecutionContextInterface $context)
    {
        if (empty($this->adm_date['left_date']) && empty($this->adm_date['right_date']) && empty($this->createdAt['left_date']) && empty($this->createdAt['right_date'])) {
            $context->buildViolation('You must select a date range for either created at or admission date')->addViolation();
        }
    }
}
