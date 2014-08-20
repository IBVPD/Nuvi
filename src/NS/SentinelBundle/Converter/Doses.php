<?php

namespace NS\SentinelBundle\Converter;

/**
 * Description of Doses
 *
 * @author gnat
 */
class Doses extends ArrayChoice
{
    public function convert($input)
    {
        return ($input == 0) ? null : parent::convert ($input);
    }
}
