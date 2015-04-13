<?php

namespace NS\SentinelBundle\Form\Types;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of CTValue
 *
 * @author gnat
 */
class CTValueType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            -3 => 'No CT Value',
            -2 => 'Negative',
            -1 => 'Undetermined',
        );

        $builder
            ->add('choice','choice',array('choices'=>$choices,'empty_value'=>'','label'=>'Non-result choices'))
            ->add('number','number',array('precision'=>2,'label'=>''))

        ->addModelTransformer(new \NS\SentinelBundle\Form\Transformer\CTValueTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'CTValueType';
    }
}
