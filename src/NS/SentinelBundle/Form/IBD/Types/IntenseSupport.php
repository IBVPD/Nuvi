<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of IntenseSupport
 *
 */
class IntenseSupport extends ArrayChoice
{
    const NO           = 0;
    const YES          = 1;
    const NO_MONITORED = 2;


    protected $values = [
                                self::NO            => 'No',
                                self::NO_MONITORED  => 'No (But monitored by WHO)',
                                self::YES           => 'Yes'
    ];
}
