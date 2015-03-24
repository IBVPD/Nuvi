<?php

namespace NS\ImportBundle\Converter;

use \Ddeboer\DataImport\Exception\InvalidArgumentException;
use \Ddeboer\DataImport\ItemConverter\MappingItemConverter as BaseItemConverter;

/**
 * Description of MappingItemConverter
 *
 * @author gnat
 */
class MappingItemConverter extends BaseItemConverter
{

    /**
     * Applies a mapping to an item
     *
     * @param array  $item
     * @param string $from
     * @param string $to
     *
     * @return array
     */
    protected function applyMapping(array $item, $from, $to)
    {
        // skip fields that dont exist
        if (!array_key_exists($from, $item)) {
            return $item;
        }

        if (strpos($to, ".") !== false) { // convert to sub array
            $fields = explode(".", $to);
            if (count($fields) > 2) {
                throw new InvalidArgumentException(sprintf("Only one dimension arrays are supported"));
            }

            if (!isset($item[$fields[0]])) {
                $item[$fields[0]] = array($fields[1] => $item[$from]);
            }
            else {
                $item[$fields[0]][$fields[1]] = $item[$from];
            }

            unset($item[$from]);
            return $item;
        }

        return parent::applyMapping($item, $from, $to);
    }

    /**
     * 
     * @return array
     */
    public function getMappings()
    {
        return $this->mappings;
    }

}
