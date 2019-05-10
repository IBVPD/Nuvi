<?php

namespace NS\SentinelBundle\Form\Pneumonia;

use NS\SentinelBundle\Entity\Pneumonia\ReferenceLab;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceLabType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReferenceLab::class,
        ]);
    }

    public function getParent(): string
    {
        return BaseLabType::class;
    }
}
