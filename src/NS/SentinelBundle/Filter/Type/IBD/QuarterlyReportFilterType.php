<?php

namespace NS\SentinelBundle\Filter\Type\IBD;

use Symfony\Component\Form\AbstractType;

class QuarterlyReportFilterType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return 'BaseQuarterlyFilter';
    }


    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'QuarterlyReportFilter';
    }

}
