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

    private $sorted = false;

    /**
     *
     */
    public function __construct()
    {
        $this->converters = array();
        $this->values     = array();
    }

    /**
     *
     * @param string $id
     * @param NamedValueConverterInterface $converter
     */
    public function addConverter($id, NamedValueConverterInterface $converter)
    {
        $this->converters[$id] = $converter;
        $this->values[$id]     = $converter->getName();
    }

    /**
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        if (!$this->sorted) {
            asort($this->values);
            $this->sorted = true;
        }

        $resolver->setDefaults(array(
            'empty_value' => 'Please Select...',
            'choices'     => $this->values,
        ));
    }

    /**
     *
     * @param array $field
     * @return string|null
     */
    public function getConverterForField(array $field)
    {
        foreach ($this->values as $id => $converter) {
            if ($converter == $field['type']) {
                return $id;
            }
        }

        return null;
    }

    /**
     *
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ConverterChoice';
    }
}