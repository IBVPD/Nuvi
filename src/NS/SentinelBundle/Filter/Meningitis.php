<?php

namespace NS\SentinelBundle\Filter;

use NS\SentinelBundle\Form\Types\CaseStatus;

/**
 * Description of Meningitis
 * @author gnat
 */
class Meningitis
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
     * @var
     */
    private $siteLab;

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
    private $admDate;

    /**
     * @var $status
     */
    private $status;
    private $lab;
    private $referenceLab;

    public function getId()
    {
        return $this->id;
    }

    public function getCaseId()
    {
        return $this->caseId;
    }

    public function getRegion()
    {
        return $this->region;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getSiteLab()
    {
        return $this->siteLab;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getAdmDate()
    {
        return $this->admDate;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getLab()
    {
        return $this->lab;
    }

    public function getReferenceLab()
    {
        return $this->referenceLab;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;
        return $this;
    }

    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    public function setSiteLab($siteLab)
    {
        $this->siteLab = $siteLab;
        return $this;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function setAdmDate($admDate)
    {
        $this->admDate = $admDate;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setLab($lab)
    {
        $this->lab = $lab;
        return $this;
    }

    public function setReferenceLab($referenceLab)
    {
        $this->referenceLab = $referenceLab;
        return $this;
    }
}