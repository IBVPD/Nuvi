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
 */
class FileUploadType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults( array('multiple' => false));

        $resolver->setAllowedValues(array('multiple'=>  array(true, false)));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

//        if($options['multiple'])
//            $view->vars['attr']['multiple'] = true;
    }

    public function getName()
    {
        return 'fileupload';
    }
}
