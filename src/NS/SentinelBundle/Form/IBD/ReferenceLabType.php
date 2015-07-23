<?php

namespace NS\SentinelBundle\Form\IBD;

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
            'data_class' => 'NS\SentinelBundle\Entity\IBD\ReferenceLab'
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'ibd_base_lab';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ibd_referencelab';
    }
}
