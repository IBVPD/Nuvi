<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NationalLabType extends AbstractType
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\SentinelBundle\Entity\Rota\NationalLab'
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
        return 'rotavirus_nationallab';
    }
}
