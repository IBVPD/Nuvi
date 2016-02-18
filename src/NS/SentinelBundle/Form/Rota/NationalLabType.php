<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NationalLabType
 * @package NS\SentinelBundle\Form\Rota
 */
class NationalLabType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\Rota\NationalLab'
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'rotavirus_base_lab';
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'rotavirus_nationallab';
    }
}
