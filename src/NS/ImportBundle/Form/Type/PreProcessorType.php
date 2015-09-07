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
//        parent::buildView($view, $form, $options);
        
        $view->vars['csv_fields'] = $options['csv_fields'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csv_fields'     => array('name', 'address', 'phone_number'),
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
