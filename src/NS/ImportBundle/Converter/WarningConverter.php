<?php

namespace NS\ImportBundle\Converter;

use NS\UtilBundle\Form\Types\ArrayChoice;

class WarningConverter
{
    /**
     * @param array $item
     * @return array
     */
    public function __invoke(array $item)
    {
        foreach($item as $value) {
            if($value instanceof ArrayChoice) {
                if($value->equal(ArrayChoice::OUT_OF_RANGE)) {
                    $item['warning'] = true;
                    return $item;
                }
            }
        }

        return $item;
    }
}
