<?php

namespace NS\SentinelBundle\Filter\Type\RotaVirus;

use NS\SentinelBundle\Filter\Entity\RotaVirus;
use NS\SentinelBundle\Filter\Type\BaseFilterType;
use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FilterType
 * @package NS\SentinelBundle\Form\Rota
 */
class FilterType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('disch_class', DischargeClassification::class, ['label' => 'Discharge Classification', 'required' => false,]);
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
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseFilterType::class;
    }
}
