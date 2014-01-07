<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of DatePickerType
 *
 * @author gnat
 */
class DatePickerType extends AbstractType
{
    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'widget'    => 'single_text',
            'compound'  => false,
            'format'    => 'yyyy-MM-dd',
        ));
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if(isset($view->vars['attr']['class']))
            $view->vars['attr']['class'] .= 'form-control date-picker';
        else
            $view->vars['attr']['class'] = 'form-control date-picker';
        
        $view->vars['attr']['data-date-format'] = $options['format'];
        $view->vars['type'] = 'text';
    }
    
    public function getName()
    {
        return 'acedatepicker';
    }
    
    public function getParent()
    {
        return 'date';
    }
}
