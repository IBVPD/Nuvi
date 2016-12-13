<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeOutcome;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;

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
            ->add('dischOutcome', DischargeOutcome::class, ['required' => false, 'label' => 'ibd-form.discharge-outcome'])
            ->add('dischDx', DischargeDiagnosis::class, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis', 'hidden-child' => 'dischargeDiagnosis'])
            ->add('dischDxOther', null, ['required' => false, 'label' => 'ibd-form.discharge-diagnosis-other', 'hidden-parent' => 'dischargeDiagnosis', 'hidden-value' => DischargeDiagnosis::OTHER])
            ->add('dischClass', DischargeClassification::class, ['required' => false, 'label' => 'ibd-form.discharge-class'])
            ->add('comment', null, ['required' => false, 'label' => 'ibd-form.comment']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'NS\SentinelBundle\Entity\IBD'
        ]);
    }
}
