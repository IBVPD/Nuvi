<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\SentinelBundle\Form\IBD\Transformer\CTValueTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class CTValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [
            'No CT Value' => -3,
            'Negative' => -2,
            'Undetermined' => -1,
        ];

        $builder
            ->add('number', NumberType::class, ['scale' => 2, 'label' => '', 'attr' => ['class' => 'inputOrSelect form-control' ,'placeholder'=>'Enter value or Select']])
            ->add('choice', ChoiceType::class, ['choices' => $choices, 'placeholder' => "[Enter value]", 'label' => 'Non-result choices', 'attr' => ['class' => 'inputOrSelect form-control']])
            ->addModelTransformer(new CTValueTransformer());
    }
}
