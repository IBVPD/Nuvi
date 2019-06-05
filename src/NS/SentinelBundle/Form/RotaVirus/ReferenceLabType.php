<?php

namespace NS\SentinelBundle\Form\RotaVirus;

use NS\SentinelBundle\Entity\RotaVirus\ReferenceLab;
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
