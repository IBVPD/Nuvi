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
            ->add('name',           null,   array('label'=>'form.remote-name'))
            ->add('clientId',       null,   array('label'=>'form.remote-client-id'))
            ->add('clientSecret',   null,   array('label'=>'form.remote-client-secret'))
            ->add('tokenEndpoint',  null,   array('label'=>'form.remote-token-endpoint'))
            ->add('authEndpoint',   null,   array('label'=>'form.remote-auth-endpoint'))
            ->add('redirectUrl',    null,   array('label'=>'form.remote-redirect-url'))
            ->add('create','submit',array('label'=>'form.remote-submit-button','attr'=>array('class'=> 'btn btn-sm btn-success')))
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
