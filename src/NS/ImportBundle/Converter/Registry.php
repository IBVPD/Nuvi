<?php

namespace NS\ImportBundle\Converter;

use NS\ImportBundle\Converter\NamedValueConverterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of Registry
 *
 * @author gnat
 */
class Registry extends AbstractType
{
    private $converters;
    private $values;

    public function __construct()
    {
        $this->converters = array();
        $this->values     = array();
    }

    public function addConverter($id, NamedValueConverterInterface $converter)
    {
        $this->converters[$id] = $converter;
        $this->values[$id]     = $converter->getName();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'empty_value' => 'Please Select...',
            'choices'     => $this->values,
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'ConverterChoice';
    }
}
