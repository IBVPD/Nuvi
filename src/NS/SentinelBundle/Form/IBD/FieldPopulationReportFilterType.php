<?php

namespace NS\SentinelBundle\Form\IBD;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of ReportFilterType
 *
 * @author gnat
 */
class FieldPopulationReportFilterType extends AbstractType
{
    public function setDefaults(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('site_type'         => 'advanced',
                                     'validation_groups' => array('FieldPopulation'),
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
