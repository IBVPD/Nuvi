<?php

namespace NS\ImportBundle\Converter;

use \Ddeboer\DataImport\Step\MappingStep;

/**
 * Description of UnsetMappingItemConverter
 *
 * @author gnat
 */
class UnsetMappingItemConverter extends MappingStep
{
    /**
     * {@inheritdoc}
     */
    public function process(&$item)
    {
        foreach (array_keys($this->mappings) as $from) {
            if(isset($item[$from])) {
                unset($item[$from]);
            }
        }
    }
}
