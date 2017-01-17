<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\SentinelBundle\Entity\RotaVirus\ReferenceLab;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceLabType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceLab::class,
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
