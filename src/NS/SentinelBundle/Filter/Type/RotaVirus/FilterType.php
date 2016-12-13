<?php

namespace NS\SentinelBundle\Filter\Type\RotaVirus;

use NS\SentinelBundle\Filter\Type\BaseFilterType;
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
        $resolver->setDefaults([
            'data_class'      => 'NS\SentinelBundle\Filter\Entity\RotaVirus',
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
