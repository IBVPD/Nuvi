<?php

namespace NS\ApiBundle\Form\Type;

use \Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

/**
 * Description of AuthorizeFormType
 *
 * @author gnat
 */
class AuthorizeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('allowAccess', 'checkbox', array(
            'label' => 'Allow access',
        ));
    }

    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'NS\ApiBundle\Form\Model\Authorize'));
    }

    public function getName()
    {
        return 'api_oauth_server_authorize';
    }
}