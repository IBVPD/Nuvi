<?php

namespace NS\ImportBundle\Filter;

/**
 * Description of DuplicateFactory
 *
 * @author gnat
 */
class DuplicateFilterFactory
{
    protected $typelist;

    public function __construct()
    {
        $caseFields = array('getcode' => 'site', 1 => 'caseId');
        $labFields  = array('getid' => 'caseFile', 1 => 'id');

        $this->typelist = array(
            'NS\SentinelBundle\Entity\IBD'               => $caseFields,
            'NS\SentinelBundle\Entity\IBD\SiteLab'       => $labFields,
            'NS\SentinelBundle\Entity\IBD\ReferenceLab'  => $labFields,
            'NS\SentinelBundle\Entity\IBD\NationalLab'   => $labFields,
            'NS\SentinelBundle\Entity\RotaVirus'         => $caseFields,
            'NS\SentinelBundle\Entity\Rota\SiteLab'      => $labFields,
            'NS\SentinelBundle\Entity\Rota\ReferenceLab' => $labFields,
            'NS\SentinelBundle\Entity\Rota\NationalLab'  => $labFields,
            'NS\SentinelBundle\Entity\Rota\SiteLab'      => $labFields,
        );
    }

    public function createDuplicateFilter($className)
    {
        if (!$className)
        {
            return null;
        }

        if (!array_key_exists($className, $this->typelist))
        {
            throw new \InvalidArgumentException("$className is not valid type");
        }

        return new Duplicate($this->typelist[$className]);
    }
}