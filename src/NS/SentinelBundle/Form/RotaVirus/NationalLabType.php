<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\SentinelBundle\Entity\RotaVirus\NationalLab;
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
        $resolver->setDefaults([
            'data_class' => NationalLab::class
        ]);
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return BaseLabType::class;
    }
}
