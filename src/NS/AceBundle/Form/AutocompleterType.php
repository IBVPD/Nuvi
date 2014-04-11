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
class AutocompleterType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults( array(
            'autocompleteUrl' => false,
            'method'          =>'POST',
            'queryParam'      =>'q',
            'minChars'        => 2,
            'prePopulate'     => null,
            'hintText'        => 'Enter a search term',
            'noResultsText'   => 'No results',
            'searchingText'   => 'Searching'
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $opts = array();
        
        foreach(array('method', 'queryParam', 'minChars', 'prePopulate', 'hintText', 'noResultsText', 'searchingText') as $opt)
            $opts[$opt] = $options[$opt];

        if($options['autocompleteUrl'])
            $view->vars['attr']['data-autocompleteUrl'] = $options['autocompleteUrl'];

        $view->vars['attr']['data-options'] = json_encode($opts);
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'autocompleter';
    }
}
