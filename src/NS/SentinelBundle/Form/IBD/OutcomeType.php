<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use NS\SentinelBundle\Form\Types\DischargeDiagnosis;

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
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dischOutcome', 'DischargeOutcome',           array('required'=>false, 'label'=>'meningitis-form.discharge-outcome'))
            ->add('dischDx',      'DischargeDiagnosis',         array('required'=>false, 'label'=>'meningitis-form.discharge-diagnosis',       'attr' => array('data-context-child'=>'dischargeDiagnosis')))
            ->add('dischDxOther', null,                         array('required'=>false, 'label'=>'meningitis-form.discharge-diagnosis-other', 'attr' => array('data-context-parent'=>'dischargeDiagnosis', 'data-context-value'=>DischargeDiagnosis::OTHER)))
            ->add('dischClass',   'DischargeClassification',    array('required'=>false, 'label'=>'meningitis-form.discharge-class'))
            ->add('comment',      null,                         array('required'=>false, 'label'=>'meningitis-form.comment'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\Meningitis'
        ));
    }

    public function getName() 
    {
        return 'ibd_outcome';
    }
}
