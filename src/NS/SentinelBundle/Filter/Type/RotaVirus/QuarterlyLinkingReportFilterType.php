<?php

namespace NS\SentinelBundle\Filter\Type\RotaVirus;


use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;

class QuarterlyLinkingReportFilterType extends BaseQuarterlyFilterType
{
    protected $fieldName = 'specimenCollectionDate';

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'RotaVirusQuarterlyLinkingReportFilter';
    }
}
