<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeOutcome;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OutcomeType
 * @package NS\SentinelBundle\Form\Rota
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
            ->add('dischargeOutcome',           DischargeOutcome::class,        ['required'=>false, 'label'=>'rotavirus-form.dischargeOutcome'])
            ->add('dischargeDate',              DatePickerType::class,          ['required'=>false, 'label'=>'rotavirus-form.dischargeDate'])
            ->add('dischargeClassification',    DischargeClassification::class, ['required'=>false,'label'=>'rotavirus-form.dischargeClassification'])
            ->add('dischargeClassOther',        null,               ['required'=>false, 'label'=>'rotavirus-form.dischargeClassOther'])
            ->add('comment',                    null,               ['required'=>false, 'label'=>'rotavirus-form.comment'])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'NS\SentinelBundle\Entity\RotaVirus'
        ]);
    }
}
