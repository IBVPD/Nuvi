<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Security\Core\SecurityContext;
use \Symfony\Component\Form\FormEvent;
use \Symfony\Component\Form\FormEvents;
use \Doctrine\Common\Persistence\ObjectManager;
use \Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use NS\SentinelBundle\Entity\User;

class MeningitisFilter extends AbstractType
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'NS\SentinelBundle\Filter\Meningitis',
            'csrf_protection' => false,
        ));
    }

    public function getParent()
    {
        return 'base_filter_form';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'meningitis_filter_form';
    }
}
