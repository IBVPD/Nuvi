<?php

namespace NS\SentinelBundle\Form\RotaVirus;

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
            'data_class' => 'NS\SentinelBundle\Entity\RotaVirus\NationalLab'
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'NS\SentinelBundle\Form\RotaVirus\BaseLabType';
    }
}
