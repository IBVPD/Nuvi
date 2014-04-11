<?php

namespace NS\SentinelBundle\Form;

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
            'data_class' => 'NS\SentinelBundle\Entity\ReferenceLab'
        ));
    }

    public function getParent()
    {
        return 'ibd_base_lab';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'meningitis_referencelab';
    }
}
