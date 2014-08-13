<?php

namespace NS\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',               null,               array('label'=>'form.client-name'))
            ->add('redirectUris',       'tag',              array('label'=>'form.client.redirect-uris', 'arrayOutput'=>true))
            ->add('allowedGrantTypes',  'OAuthGrantTypes',  array('label'=>'form.client.allowed-grant-types'))
            ->add('create',             'submit',           array('label'=>'form.client.submit-button','attr'=>array('class'=> 'btn btn-sm btn-success')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\ApiBundle\Entity\Client'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CreateApiClient';
    }
}
