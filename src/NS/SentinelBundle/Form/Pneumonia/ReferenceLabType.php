<?php

namespace NS\SentinelBundle\Form\Pneumonia;

use NS\SentinelBundle\Entity\Pneumonia\ReferenceLab;
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
