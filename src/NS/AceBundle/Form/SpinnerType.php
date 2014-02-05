<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of SwitchType
 *
 * @author gnat
 * @author mark
 * @author http://loopj.com/jquery-tokeninput/
 */
class SpinnerType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults( array(
            'min'           => 0,
            'max'           => 100,
            'step'          => 1,
            'touchscreen'   => false, //built for touchscreen
            'on_sides'      => false,
            'pos_neg'       => false
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $opts = array();

        foreach(array('min', 'max', 'step', 'on_sides') as $opt)
            $opts[$opt] = $options[$opt];

        if($options['pos_neg'])
            $opts += array('icon_up' => 'icon-plus smaller-75', 'icon_down' => 'icon-minus smaller-75', 'btn_up_class' => 'btn-success' , 'btn_down_class' => 'btn-danger');

        if($options['touchscreen'])
            $opts['touch_spinner'] = true;

        $view->vars['attr']['data-options'] = json_encode($opts);
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'spinner';
    }
}
