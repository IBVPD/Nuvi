<?php

namespace NS\SentinelBundle\Filter\Type\Pneumonia;

use NS\SentinelBundle\Filter\Entity\Pneumonia;
use NS\SentinelBundle\Filter\Type\BaseFilterType;
use NS\SentinelBundle\Form\IBD\Types\CaseResult;
use NS\SentinelBundle\Form\Types\CaseStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;

/**
 * Class FilterType
 * @package NS\SentinelBundle\Filter\Type\Pneumonia
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
            'data_class' => Pneumonia::class,
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
