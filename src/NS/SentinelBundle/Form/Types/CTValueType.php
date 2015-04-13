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
            ->add('number','number',array('precision'=>2))
            ->add('choice','choice',array('choices'=>$choices))
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
