<?php

namespace NS\ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
            ->add('name',           null,   ['label'=>'form.remote-name'])
            ->add('clientId',       null,   ['label'=>'form.remote-client-id'])
            ->add('clientSecret',   null,   ['label'=>'form.remote-client-secret'])
            ->add('tokenEndpoint',  null,   ['label'=>'form.remote-token-endpoint'])
            ->add('authEndpoint',   null,   ['label'=>'form.remote-auth-endpoint'])
            ->add('redirectUrl',    null,   ['label'=>'form.remote-redirect-url'])
            ->add('create', SubmitType::class, ['label'=>'form.remote-submit-button', 'attr'=> ['class'=> 'btn btn-sm btn-success']])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'NS\ApiBundle\Entity\Remote'
        ]);
    }
}
