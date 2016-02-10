<?php

namespace NS\ImportBundle\Converter;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of Registry
 *
 * @author gnat
 */
class Registry extends AbstractType
{
    /**
     * @var array
     */
    private $values = array();

    /**
     * @var array
     */
    private $converters = array();
    /**
     * @var bool
     */
    private $sorted = false;

    /**
     *
     * @param string $id
     * @param NamedValueConverterInterface $converter
     */
    public function addConverter($id, NamedValueConverterInterface $converter)
    {
        $this->values[$id]     = $converter->getName();
        $this->converters[$id] = $converter;
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
            'placeholder' => 'Please Select...',
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

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        if(!isset($this->converters[$id])) {
            throw new ServiceNotFoundException($id);
        }

        return $this->converters[$id];
    }
}
