<?php

namespace NS\ImportBundle\Converter;

use Ddeboer\DataImport\Step\ConverterStep;

/**
 * Class TrimInputConverter
 * @package NS\ImportBundle\Converter
 */
class TrimInputConverter extends ConverterStep
{
    /**
     * @param $item
     * @return mixed
     */
    public function __invoke($item)
    {
        return $this->trim($item);
    }

    /**
     * @param $item
     * @return mixed
     */
    public function trim($item)
    {
        foreach ($item as &$value) {
            if (is_array($value)) {
                $value = $this->trim($value);
            } elseif (!is_object($value)) {
                $value = trim($value);
            }
        }

        return $item;
    }
}
