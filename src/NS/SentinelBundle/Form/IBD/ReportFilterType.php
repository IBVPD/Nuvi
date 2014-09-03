<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\SentinelBundle\Form\Filters\BaseReportFilterType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of ReportFilterType
 *
 * @author gnat
 */
class ReportFilterType extends BaseReportFilterType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'NS\SentinelBundle\Filter\IBD'));
    }

    public function getName()
    {
        return 'IBDReportFilterType';
    }
}
