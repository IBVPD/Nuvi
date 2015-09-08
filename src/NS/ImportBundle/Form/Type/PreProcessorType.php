<?php

namespace NS\ImportBundle\Form\Type;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;

/**
 * Description of ClassType
 *
 * @author gnat
 */
class PreProcessorType extends AbstractType
{
//    private $choices;

    /**
     * Constructor
     *
     * @param array $choices
     */
    public function __construct()
    {
//        $this->choices = $choices;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['operators'] = $options['operators'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'operators'     => array('equal', 'not_equal', 'in', 'not_in', 'less', 'less_or_equal', 'greater', 'greater_or_equal', 'is_empty', 'is_null'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'PreProcessorType';
    }
}
