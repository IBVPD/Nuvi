<?php

namespace NS\SentinelBundle\Filter\Type;

use NS\AceBundle\Form\DatePickerType;
use NS\SentinelBundle\Entity\IBD;
use NS\SentinelBundle\Entity\Meningitis\Meningitis;
use NS\SentinelBundle\Entity\Pneumonia\Pneumonia;
use NS\SentinelBundle\Entity\RotaVirus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class ZeroReportFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = ['Meningitis' => Meningitis::class, 'Pneumonia' => Pneumonia::class, 'RotaVirus' => RotaVirus::class, 'IBD' => IBD::class];
        $builder
            ->add('type', ChoiceType::class, ['choices' => $choices, 'placeholder' => 'Please Select...'])
            ->add('from', DatePickerType::class)
            ->add('to', DatePickerType::class);
    }
}

