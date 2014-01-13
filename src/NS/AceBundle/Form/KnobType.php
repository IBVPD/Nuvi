<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of KnobType
 *
 * @author gnat
 * @author mark
 * @author http://anthonyterrien.com/knob/
 */
class KnobType extends AbstractType
{
    private $defaults;

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $this->defaults = array(
            'min'             => false,
            'max'             => false,
            'width'           => 80,
            'height'          => 80,
            'thickness'       => 0.2,
            'fgColor'         => false,
            'displayPrevious' => false,
            'angleArc'        => false,
            'angleOffset'     => false,
            'displayInput'    => true,
            'linecap'         => 'butt', //"round"
        );

        $resolver->setDefaults($this->defaults);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        foreach($this->defaults as $opt => $val)
            $view->vars['attr']['data-'.$opt] = $options[$opt];
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'knob';
    }
}
