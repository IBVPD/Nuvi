<?php

namespace NS\SentinelBundle\Form\IBD;

use \NS\SentinelBundle\Form\Filters\BaseReportFilterType;
use \Symfony\Component\Form\FormBuilderInterface;
use \NS\SentinelBundle\Form\Filters\SiteFilterType;

/**
 * Description of ReportFilterType
 *
 * @author gnat
 */
class FieldPopulationReportFilterType extends BaseReportFilterType
{
    public function setDefaults(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('site_type'=>'advanced'));
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
