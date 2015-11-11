<?php

namespace NS\ImportBundle\Converter;

/**
 * Class TrimInputConverter
 * @package NS\ImportBundle\Converter
 */
class TrimInputConverter
{
    /**
     * @param array $item
     * @return array
     */
    public function __invoke(array $item)
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
