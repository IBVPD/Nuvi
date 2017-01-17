<?php

namespace NS\SentinelBundle\Form\IBD;

use NS\SentinelBundle\Entity\IBD\NationalLab;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
