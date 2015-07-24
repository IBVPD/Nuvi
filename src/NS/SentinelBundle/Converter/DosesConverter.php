<?php

namespace NS\SentinelBundle\Converter;

/**
 * Class DosesConverter
 * @package NS\SentinelBundle\Converter
 */
class DosesConverter extends ArrayChoiceConverter
{
    /**
     * @param mixed $input
     * @return null|object
     */
    public function __invoke($input)
    {
        return ($input == 0) ? null : parent::__invoke($input);
    }
}
