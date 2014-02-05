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
 * @author https://github.com/digitalBush/jquery.maskedinput
 */
class MaskedType extends AbstractType
{
    private $defaults;

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $this->defaults = array(
            'mask'            => false,//ex: "99/99/9999", "(999) 999-9999", "99-999-9999-99"; ? = optional, ex: "(999) 999-9999? x999" for optional phone extension
            'placeholder'     => '_', //Placeholder to use for masked characters
            'definitions'     => false, //Custom mask definitions. Array.
            /*
             * 'defintions' => array(
             *      'h' => '[A-Fa-f0-9]' //"h" in mask will allow only hex characters: "99-hh-9999" = number number hex hex number number number number
             *      '~' => '[+-]' //"~" char represents a plus or minus sign: Temp: "~99" = +15, -22
             * )
             */
        );

        $resolver->setDefaults($this->defaults);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $options['definitions'] = json_encode($options['definitions']);

        foreach($this->defaults as $opt => $val)
            $view->vars['attr']['data-'.$opt] = $options[$opt];
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'masked';
    }
}
