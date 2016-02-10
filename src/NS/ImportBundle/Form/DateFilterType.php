<?php

namespace NS\ImportBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of DateFilterType
 *
 * @author gnat
 */
class DateFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'required'               => false,
                'data_extraction_method' => 'default',
            ))
            ->setAllowedValues('data_extraction_method',array('default'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'acedatepicker';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ns_filter_date';
    }
}
