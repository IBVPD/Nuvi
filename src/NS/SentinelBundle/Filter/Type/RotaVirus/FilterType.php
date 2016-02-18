<?php

namespace NS\SentinelBundle\Filter\Type\RotaVirus;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FilterType
 * @package NS\SentinelBundle\Form\Rota
 */
class FilterType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'NS\SentinelBundle\Filter\Entity\RotaVirus',
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'base_filter_form';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rotavirus_filter_form';
    }
}
