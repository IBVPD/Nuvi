<?php

namespace NS\SentinelBundle\Form\Rota;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ReportFilterType
 *
 * @author gnat
 */
class ReportFilterType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'NS\SentinelBundle\Filter\RotaVirus'));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'BaseReportFilterType';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'RotaVirusReportFilterType';
    }
}
