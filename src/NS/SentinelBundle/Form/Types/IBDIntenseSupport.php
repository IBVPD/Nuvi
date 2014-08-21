<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\ArrayChoice;

/**
 * Description of IBDIntenseSupport
 *
 */
class IBDIntenseSupport extends ArrayChoice
{
    const NO           = 0;
    const YES          = 1;
    const NO_MONITORED = 2;


    protected $values = array(
                                self::NO            => 'No',
                                self::NO_MONITORED  => 'No (But monitored by WHO)',
                                self::YES           => 'YEs'
                             );

    public function getName()
    {
        return 'IBDIntenseSupport';
    }
}
