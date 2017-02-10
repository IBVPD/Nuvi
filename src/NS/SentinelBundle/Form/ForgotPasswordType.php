<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 27/01/17
 * Time: 2:45 PM
 */

namespace NS\SentinelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForgotPasswordType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email',EmailType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'Email']]);
    }
}
