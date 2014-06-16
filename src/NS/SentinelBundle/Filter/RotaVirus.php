<?php

namespace NS\SentinelBundle\Filter;

use NS\SentinelBundle\Form\Types\TripleChoice;
use NS\SentinelBundle\Form\Types\DischargeOutcome;
use NS\SentinelBundle\Form\Types\Gender;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationReceived;
use NS\SentinelBundle\Form\Types\RotavirusVaccinationType;
use NS\SentinelBundle\Form\Types\ElisaResult;

/**
 * Description of RotaVirus
 * @author gnat
 */
class RotaVirus
{
    /**
     * @var string id
     */
    private $id;

//i. Sentinel Site Information
    /**
     * @var Region $region
     */
    private $region;

    /**
     * @var Country $country
     */
    private $country;

    /**
     * @var Site $site
     */
    private $site;

    //ISO3_code
    //site_code

    /**
     * @var siteLab
     */
    private $siteLab;

//ii. Case-based Demographic Data
    /**
     * case_ID
     * @var string $caseId
     */
    private $caseId;

//iii. Case-based Clinical Data
    /**
     * adm_date
     * @var DateTime $admDate
     */
    private $admDate;

    public function getId()
    {
        return $this->id;
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

    public function getCaseId()
    {
        return $this->caseId;
    }

    public function getAdmDate()
    {
        return $this->admDate;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function setCaseId($caseId)
    {
        $this->caseId = $caseId;
        return $this;
    }

    public function setAdmDate($admDate)
    {
        $this->admDate = $admDate;
        return $this;
    }
}
