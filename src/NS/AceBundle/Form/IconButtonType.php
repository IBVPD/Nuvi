<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\ButtonTypeInterface;

/**
 * Description of IconButtonType
 *
 * @author gnat
 */
class IconButtonType extends AbstractType implements ButtonTypeInterface
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setOptional(array('icon','type'));
        $resolver->setAllowedTypes(array('icon'=>'string'));
        $resolver->setAllowedValues(array('type'=>array('button','submit','reset')));
    }

    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        if(isset($options['icon']))
            $view->vars['icon'] = $options['icon'];

        if(isset($options['type']))
            $view->vars['type'] = $options['type'];
    }

    public function getParent()
    {
        return 'button';
    }

    public function getName()
    {
        return 'iconbutton';
    }
}
