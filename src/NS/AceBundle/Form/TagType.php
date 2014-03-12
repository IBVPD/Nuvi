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
class TagType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults( array(
            'source'              => [],
            'caseInsensitive'     => true,
            'allowDuplicates'     => false,
            'autocompleteOnComma' => false
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        
        sort($options['source']);
        $view->vars['attr']['data-source']                = json_encode($options['source']);
        $view->vars['attr']['data-case-insensitive']      = $options['caseInsensitive'];
        $view->vars['attr']['data-allow-duplicates']      = $options['allowDuplicates'];
        $view->vars['attr']['data-autocomplete-on-comma'] = $options['autocompleteOnComma'];
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'tag';
    }
}
