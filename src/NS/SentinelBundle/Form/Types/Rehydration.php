<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of Rehydration
 *
 */
class Rehydration extends TranslatableArrayChoice
{
    const ORAL    = 1;
    const IV      = 2;
    const OTHER   = 3;
    const UNKNOWN = 99;

    protected $values = array(
                                self::ORAL      => 'Oral',
                                self::IV        => 'IV',
                                self::OTHER     => 'Other',
                                self::UNKNOWN   => 'Unknown',
                             );

    public function getName()
    {
        return 'Rehydration';
    }
}
