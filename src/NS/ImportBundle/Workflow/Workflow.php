<?php

namespace NS\ImportBundle\Workflow;

use ArrayAccess;
use Ddeboer\DataImport\Exception\ExceptionInterface;
use Ddeboer\DataImport\Exception\UnexpectedTypeException;
use Ddeboer\DataImport\Exception\ValueConversionException;
use Ddeboer\DataImport\ValueConverter\ValueConverterInterface;
use Ddeboer\DataImport\Workflow as BaseWorkflow;
use RuntimeException;
use Traversable;

/**
 * Description of Workflow
 *
 * @author gnat
 */
class Workflow extends BaseWorkflow
{
    protected $objectLinkers = array();

    /**
     * 
     * @param array $item
     * @return Traversable
     * @throws UnexpectedTypeException
     * @throws ValueConversionException
     */
    protected function convertItem($item)
    {
        foreach ($this->itemConverters as $converter) {
            $item = $converter->convert($item);
            if ($item && !(is_array($item) || ($item instanceof ArrayAccess && $item instanceof Traversable))) {
                throw new UnexpectedTypeException($item, 'false or array');
            }

            if (!$item) {
                return $item;
            }
        }

        if ($item && !(is_array($item) || ($item instanceof ArrayAccess && $item instanceof Traversable))) {
            throw new UnexpectedTypeException($item, 'false or array');
        }

        foreach ($this->valueConverters as $property => $converters) {
            if (strpos($property, '.') !== FALSE) {
                $properties = explode('.',$property);
                $subItem = &$item;
                foreach($properties as $sproperty)
                {
                    if (!isset($subItem[$sproperty]) || !array_key_exists($sproperty, $subItem)) {
                        throw new RuntimeException(sprintf('Unable to find %s at %s - %s',$property,$sproperty,print_r($item,true)));
                    }
                    $subItem = &$subItem[$sproperty];
                }

                foreach ($converters as $converter) {
                    try {
                        $subItem = $converter->convert($subItem);
                    }
                    catch (ExceptionInterface $e) {
                        throw new ValueConversionException($sproperty, $e);
                    }
                }
            }

            // isset() returns false when value is null, so we need
            // array_key_exists() too. Combine both to have best performance,
            // as isset() is much faster.
            if (isset($item[$property]) || array_key_exists($property, $item)) {
                foreach ($converters as $converter) {
                    try {
                        $item[$property] = $converter->convert($item[$property]);
                    }
                    catch (ExceptionInterface $e) {
                        throw new ValueConversionException($property, $e);
                    }
                }
            }
        }

        foreach($this->objectLinkers as $linker){
            $item = $linker->convert($item);
        }
            
        return $item;
    }

    public function findSubfield($property, $item)
    {
        if (isset($item[$property]) || array_key_exists($property, $item)) {
            return $item[$property];
        }
    }

    public function getValueConverters()
    {
        return $this->valueConverters;
    }

    public function getItemConverters()
    {
        return $this->itemConverters;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getAfterConversionFilters()
    {
        return $this->afterConversionFilters;
    }

    public function getObjectLinkers()
    {
        return $this->objectLinkers;
    }

    public function setObjectLinkers($objectLinkers)
    {
        $this->objectLinkers = $objectLinkers;
        return $this;
    }

    public function addObjectLinker(ValueConverterInterface $linker)
    {
        $this->objectLinkers[] = $linker;
    }
}
