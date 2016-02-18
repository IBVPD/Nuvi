<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\SentinelBundle\Form\Types\DischargeDiagnosis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of OutcomeType
 *
 * @author gnat
 */
class OutcomeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dischOutcome', 'DischargeOutcome', array('required' => false,
                'label' => 'ibd-form.discharge-outcome'))
            ->add('dischDx', 'DischargeDiagnosis', array('required' => false, 'label' => 'ibd-form.discharge-diagnosis',
                'attr' => array('data-context-child' => 'dischargeDiagnosis')))
            ->add('dischDxOther', null, array('required' => false, 'label' => 'ibd-form.discharge-diagnosis-other',
                'attr' => array('data-context-parent' => 'dischargeDiagnosis', 'data-context-value' => DischargeDiagnosis::OTHER)))
            ->add('dischClass', 'DischargeClassification', array('required' => false,
                'label' => 'ibd-form.discharge-class'))
            ->add('comment', null, array('required' => false, 'label' => 'ibd-form.comment'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\IBD'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() 
    {
        return 'ibd_outcome';
    }
}
