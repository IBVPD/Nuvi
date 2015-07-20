<?php

namespace NS\ImportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices'     => $this->choices,
            'empty_value' => ' '
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
