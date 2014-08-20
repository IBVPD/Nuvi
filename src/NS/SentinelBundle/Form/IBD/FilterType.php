<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\EmbeddedFilterTypeInterface;

class FilterType extends AbstractType implements EmbeddedFilterTypeInterface
{
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('status', 'CaseStatus', array('required'=>false, 'label' => 'filter-case-status'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'NS\SentinelBundle\Filter\IBD',
            'csrf_protection' => false,
        ));
    }

    public function getParent()
    {
        return 'base_filter_form';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ibd_filter_form';
    }
}
