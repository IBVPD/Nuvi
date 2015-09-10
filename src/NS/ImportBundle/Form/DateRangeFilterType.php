<?php

namespace NS\ImportBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of DateRangeFilterType
 *
 * @author gnat
 */
class DateRangeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('left_date', 'ns_filter_date', $options['left_date_options']);
        $builder->add('right_date', 'ns_filter_date', $options['right_date_options']);

        $builder->setAttribute('filter_value_keys', array(
            'left_date'  => $options['left_date_options'],
            'right_date' => $options['right_date_options'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ns_filter_date_range';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'filter_date_range';
    }
}
