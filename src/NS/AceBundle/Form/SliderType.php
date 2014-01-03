<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of SliderType
 *
 * @author gnat
 */
class SliderType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults( array('color' => 'green'));
        
        $resolver->setAllowedValues(array('color'=>  array('green','red','purple','orange','dark')));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if(isset($view->vars['attr']['class']))
            $view->vars['attr']['class'] .= 'ace ace-switch ace-switch-'.$options['switchtype'];
        else
            $view->vars['attr']['class'] = 'ace ace-switch ace-switch-'.$options['switchtype'];
    }

    public function getName()
    {
        return 'slider';
    }

    public function getParent()
    {
        return 'checkbox';
    }
}
