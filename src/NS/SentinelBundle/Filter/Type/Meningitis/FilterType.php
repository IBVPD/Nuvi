<?php

namespace NS\SentinelBundle\Filter\Type\Meningitis;

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

/**
 * Class FilterType
 * @package NS\SentinelBundle\Filter\Type\Meningitis
 */
class FilterType extends AbstractType implements EmbeddedFilterTypeInterface
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return BaseFilterType::class;
    }
}
