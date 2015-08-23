<?php

namespace NS\ImportBundle\Converter;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of Registry
 *
 * @author gnat
 */
class Registry extends AbstractType
{
    private $values;

    private $sorted = false;

    /**
     *
     */
    public function __construct()
    {
        $this->values     = array();
    }

    /**
     *
     * @param string $id
     * @param NamedValueConverterInterface $converter
     */
    public function addConverter($id, NamedValueConverterInterface $converter)
    {
        $this->values[$id]     = $converter->getName();
    }

    /**
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
    public function getConverterForField($field)
    {
        foreach ($this->values as $id => $converter) {
            if ($converter == $field) {
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