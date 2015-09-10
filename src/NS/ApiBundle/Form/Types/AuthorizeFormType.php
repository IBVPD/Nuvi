<?php

namespace NS\ApiBundle\Form\Types;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of AuthorizeFormType
 *
 * @author gnat
 */
class AuthorizeFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('allowAccess', 'checkbox', array('label' => 'Allow access',))
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'NS\ApiBundle\Form\Model\Authorize'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'api_oauth_server_authorize';
    }
}
