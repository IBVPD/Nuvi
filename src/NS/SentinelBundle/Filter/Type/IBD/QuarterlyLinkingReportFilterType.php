<?php

namespace NS\SentinelBundle\Filter\Type\IBD;

use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;

class QuarterlyLinkingReportFilterType extends BaseQuarterlyFilterType
{
    protected $fieldName = 'sample_collection_date';

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'IBDQuarterlyLinkingReportFilter';
    }
}
