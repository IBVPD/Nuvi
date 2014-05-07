<?php

namespace NS\SentinelBundle\Form\Rota;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReferenceLabType extends AbstractType
{    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
