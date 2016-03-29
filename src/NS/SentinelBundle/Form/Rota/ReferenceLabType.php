<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReferenceLabType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\Rota\ReferenceLab'
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
    public function getName()
    {
        return 'rotavirus_referencelab';
    }
}
