<?php

namespace NS\SentinelBundle\Form\Rota;

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
            'data_class'      => 'NS\SentinelBundle\Filter\RotaVirus',
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
    public function getName()
    {
        return 'rotavirus_filter_form';
    }
}
