<?php

namespace NS\ApiBundle\Form;

use NS\AceBundle\Form\TagType;
use NS\ApiBundle\Form\Types\OAuthGrantTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('name',               null,                   array('label'=>'form.client-name'))
            ->add('redirectUris',       TagType::class,         array('label'=>'form.client.redirect-uris', 'arrayOutput'=>true))
            ->add('allowedGrantTypes',  OAuthGrantTypes::class, array('label'=>'form.client.allowed-grant-types'))
            ->add('create',             SubmitType::class,      array('label'=>'form.client.submit-button', 'attr'=>array('class'=> 'btn btn-sm btn-success')))
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
}
