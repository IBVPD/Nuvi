<?php

namespace NS\SentinelBundle\Form;

use Lunetics\LocaleBundle\Form\Extension\Type\LocaleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use NS\SentinelBundle\Entity\User;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('plainPassword', RepeatedType::class,
                     [
                         'type'            => PasswordType::class,
                         'invalid_message' => 'The password fields must match.',
                         'options'         => ['attr' => ['class' => 'password-field', 'autocomplete' => 'off']],
                         'required'        => false,
                         'first_options'   => ['label' => 'Password'],
                         'second_options'  => ['label' => 'Repeat Password'],
                     ]
                 )
            ->add('language', LocaleType::class, ['label'=>'Preferred Language'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
