<?php

namespace NS\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use OAuth2\OAuth2;

class ApiClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('redirectUris',new Type\TextToArray())
            ->add('allowedGrantTypes','choice',array('multiple'=>true,'choices'=>array(
                                                                                    OAuth2::GRANT_TYPE_AUTH_CODE          => 'authorization_code',
                                                                                    OAuth2::GRANT_TYPE_IMPLICIT           => 'token',
                                                                                    OAuth2::GRANT_TYPE_USER_CREDENTIALS   => 'password',
                                                                                    OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS => 'client_credentials',
                                                                                    OAuth2::GRANT_TYPE_REFRESH_TOKEN      => 'refresh_token',
                                                                                    OAuth2::GRANT_TYPE_EXTENSIONS         => 'extensions',
                                                                                    )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NS\ApiBundle\Entity\ApiClient'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ns_apibundle_client';
    }
}
