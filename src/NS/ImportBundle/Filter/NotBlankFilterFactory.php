<?php

namespace NS\ImportBundle\Filter;

/**
 * Description of NotBlankFilterFactory
 *
 * @author gnat
 */
class NotBlankFilterFactory extends AbstractFilterFactory
{
    public function __construct()
    {
        $caseFields = array('caseId', 'site');
        $labFields  = array('caseFile', 'labId');

        $this->setTypelist(array(
            'NS\SentinelBundle\Entity\IBD'               => $caseFields,
            'NS\SentinelBundle\Entity\IBD\SiteLab'       => $labFields,
            'NS\SentinelBundle\Entity\IBD\ReferenceLab'  => $labFields,
            'NS\SentinelBundle\Entity\IBD\NationalLab'   => $labFields,
            'NS\SentinelBundle\Entity\RotaVirus'         => $caseFields,
            'NS\SentinelBundle\Entity\Rota\SiteLab'      => $labFields,
            'NS\SentinelBundle\Entity\Rota\ReferenceLab' => $labFields,
            'NS\SentinelBundle\Entity\Rota\NationalLab'  => $labFields,
        ));
        $this->setFilterClass('\NS\ImportBundle\Filter\NotBlank');
    }
}