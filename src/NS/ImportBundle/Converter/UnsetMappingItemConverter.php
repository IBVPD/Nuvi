<?php

namespace NS\ImportBundle\Converter;

use \Ddeboer\DataImport\ItemConverter\MappingItemConverter;

/**
 * Description of UnsetMappingItemConverter
 *
 * @author gnat
 */
class UnsetMappingItemConverter extends MappingItemConverter
{
    /**
     * Add a mapping
     *
     * @param string       $from Field to map from
     * @param string|array $to   Field name or array to map to
     *
     * @return $this
     */
    public function addMapping($from, $to = 'null')
    {
        $this->mappings[$from] = $to;

        return $this;
    }

    protected function applyMapping(array $item, $from, $to)
    {
        if(isset($item[$from]))
            unset($item[$from]);

        return $item;
    }
}
