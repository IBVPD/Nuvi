<?php

namespace NS\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RemoteType
 * @package NS\ApiBundle\Form
 */
class RemoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
            ->add('create','Symfony\Component\Form\Extension\Core\Type\SubmitType',array('label'=>'form.remote-submit-button','attr'=>array('class'=> 'btn btn-sm btn-success')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
