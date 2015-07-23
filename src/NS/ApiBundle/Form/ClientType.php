<?php

namespace NS\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ClientType
 * @package NS\ApiBundle\Form
 */
class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
