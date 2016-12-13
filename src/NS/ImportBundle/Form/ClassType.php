<?php

namespace NS\ImportBundle\Form;

use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of ClassType
 *
 * @author gnat
 */
class ClassType extends AbstractType
{
    private $choices;

    /**
     * Constructor
     *
     * @param array $choices
     */
    public function __construct(array $choices = [])
    {
        $this->choices = $choices;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices'     => $this->choices,
            'placeholder' => ' '
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
