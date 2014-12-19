<?php

namespace NS\ImportBundle\Converter;

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
        if (!isset($item[$from]) && !array_key_exists($from, $item))
            return $item;

        if (strpos($to, ".") !== false) // convert to sub array
        {
            $fields = explode(".", $to);
            $index  = &$item;

            foreach ($fields as $f)
            {
                if (!isset($index[$f]))
                {
                    $index[$f] = array();
                    $index     = &$index[$f];
                }
            }

            unset($item[$from]);
            return $item;
        }
        else
            return parent::applyMapping($item, $from, $to);
    }
}