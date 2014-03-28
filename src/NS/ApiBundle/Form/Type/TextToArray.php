<?php

namespace NS\ApiBundle\Form\Type;

use \Symfony\Component\Form\AbstractType;
use NS\ApiBundle\Form\Transformer\TextToArrayTransformer;

/**
 * Description of TextToArray
 *
 * @author gnat
 */
class TextToArray extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new TextToArrayTransformer());
    }

    public function getName()
    {
        return 'TextToArray';
    }

    public function getParent()
    {
        return 'text';
    }
}
