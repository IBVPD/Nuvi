<?php

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('password',RepeatedType::class,[
            'type' => PasswordType::class,
            'first_options' => ['label' => false, 'attr' => ['placeholder' => 'New Password', 'class' => 'form-control']],
            'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Re-Enter Password', 'class' => 'form-control']]
        ]);
    }
}
