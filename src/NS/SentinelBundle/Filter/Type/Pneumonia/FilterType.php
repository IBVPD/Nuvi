<?php

namespace NS\SentinelBundle\Filter\Type\Pneumonia;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;
use NS\SentinelBundle\Filter\Type\BaseFilterType;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\IBD\Types\Diagnosis;
use NS\SentinelBundle\Form\IBD\Types\DischargeClassification;
use NS\SentinelBundle\Form\IBD\Types\DischargeDiagnosis;
use NS\SentinelBundle\Form\Types\CaseStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType implements EmbeddedFilterTypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adm_dx', Diagnosis::class, [
                'label' => 'Admission Diagnosis',
                'required' => false,])
            ->add('disch_dx', DischargeDiagnosis::class, [
                'label' => 'Discharge Diagnosis',
                'required' => false,])
            ->add('disch_class', DischargeClassification::class, [
                'label' => 'Discharge Classification',
                'required' => false,])
            ->add('status', CaseStatus::class, ['required' => false, 'label' => 'filter-case-status'])
            ->add('result', CaseResult::class, ['required' => false, 'label' => 'filter-case-result'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getParent(): string
    {
        return BaseFilterType::class;
    }
}
