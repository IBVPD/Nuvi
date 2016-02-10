<?php

namespace NS\ImportBundle\Form;

use \Symfony\Component\Form\AbstractType;
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
    public function __construct(array $choices = array())
    {
        $this->choices = $choices;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices'     => $this->choices,
            'placeholder' => ' '
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ClassType';
    }
}
