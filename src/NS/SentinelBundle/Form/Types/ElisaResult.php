<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of ElisaResult
 *
 */
class ElisaResult extends TranslatableArrayChoice
{
    const NEGATIVE = 0;
    const POSITIVE = 1;
    const INDETERMINATE = 2;
    const UNKNOWN = 99;

    protected $values = array(
                                self::NEGATIVE => 'Negative',
                                self::POSITIVE => 'Positive',
                                self::INDETERMINATE => 'Indeterminate',
                                self::UNKNOWN => 'Unknown',
                             );

    public function getName()
    {
        return 'ElisaResult';
    }
}
