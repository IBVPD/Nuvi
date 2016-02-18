<?php

namespace NS\SentinelBundle\Form\IBD;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NationalLabType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\IBD\NationalLab'
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
    public function getBlockPrefix()
    {
        return 'ibd_nationallab';
    }
}
