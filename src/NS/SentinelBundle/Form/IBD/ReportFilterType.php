<?php

namespace NS\SentinelBundle\Form\IBD;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of ReportFilterType
 *
 * @author gnat
 */
class ReportFilterType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'NS\SentinelBundle\Filter\IBD'));
    }

    public function getParent()
    {
        return 'BaseReportFilterType';
    }

    public function getName()
    {
        return 'IBDReportFilterType';
    }
}
