<?php

namespace NS\SentinelBundle\Filter\Type\IBD;

use NS\SentinelBundle\Filter\Type\BaseQuarterlyFilterType;


class QuarterlyReportFilterType extends BaseQuarterlyFilterType
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'QuarterlyReportFilter';
    }

}
