<?php

namespace NS\SentinelBundle\Form\Meningitis;

use NS\SentinelBundle\Entity\Meningitis\ReferenceLab;
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
