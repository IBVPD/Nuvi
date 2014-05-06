<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

class MeningitisFilter extends AbstractType implements \Lexik\Bundle\FormFilterBundle\Filter\Extension\Type\EmbeddedFilterTypeInterface
{
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('status', 'CaseStatus', array('required'=>false, 'label' => 'filter-case-status'));
        $builder->add('lab','site_lab_filter',array('required'=>false));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'NS\SentinelBundle\Filter\Meningitis',
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
        return 'meningitis_filter_form';
    }
}
