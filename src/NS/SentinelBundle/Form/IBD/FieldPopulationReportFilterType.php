<?php

namespace NS\SentinelBundle\Form\IBD;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ReportFilterType
 *
 * @author gnat
 */
class FieldPopulationReportFilterType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('site_type'         => 'advanced',
                                     'validation_groups' => array('FieldPopulation'),
                                    ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'IBDFieldPopulationFilterType';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'IBDReportFilterType';
    }
}
