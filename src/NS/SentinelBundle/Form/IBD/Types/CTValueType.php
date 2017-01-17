<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\SentinelBundle\Form\IBD\Transformer\CTValueTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of CTValue
 *
 * @author gnat
 */
class CTValueType extends AbstractType
{
    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [
            'No CT Value' => -3,
            'Negative' => -2,
            'Undetermined' => -1,
        ];

        $builder
            ->add('choice', ChoiceType::class, ['choices' => $choices, 'placeholder' => "[Enter value]", 'label' => 'Non-result choices', 'attr' => ['class' => 'inputOrSelect']])
            ->add('number', NumberType::class, ['scale' => 2, 'label' => '', 'attr' => ['class' => 'inputOrSelect' ,'placeholder'=>'Enter value or Select']])
            ->addModelTransformer(new CTValueTransformer());
    }
}
