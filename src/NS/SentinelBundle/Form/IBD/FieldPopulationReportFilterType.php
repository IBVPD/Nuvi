<?php

namespace NS\SentinelBundle\Form\IBD;

use \NS\SentinelBundle\Form\Filters\BaseReportFilterType;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of ReportFilterType
 *
 * @author gnat
 */
class FieldPopulationReportFilterType extends BaseReportFilterType
{
    public function setDefaults(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('site_type'        => 'advanced',
                                     'data_class'       => '\NS\SentinelBundle\Filter\IBD',
                                     'validation_groups'=> array('FieldPopulation'),
                                    ));
    }

    public function getName()
    {
        return 'IBDFieldPopulationFilterType';
    }

    public function getParent()
    {
        return 'IBDReportFilterType';
    }
}
