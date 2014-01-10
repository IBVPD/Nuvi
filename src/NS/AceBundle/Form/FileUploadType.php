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
        $resolver->setDefaults( array('uploadUrl' => false, 'viewUrl'=>false));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if($options['uploadUrl'])
            $view->vars['attr']['data-uploadurl'] = $options['uploadUrl'];

        if($options['viewUrl'])
            $view->vars['attr']['data-viewurl'] = $options['viewUrl'];
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'fileupload';
    }
}
