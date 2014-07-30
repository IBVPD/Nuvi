<?php

namespace NS\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RemoteType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('clientId')
            ->add('clientSecret')
            ->add('tokenEndpoint')
            ->add('authEndpoint')
            ->add('redirectUrl')
            ->add('create','submit',array('label'=>'api-create-remote-submit','attr'=>array('class'=> 'btn btn-sm btn-success')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\ApiBundle\Entity\Remote'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CreateApiRemote';
    }
}
