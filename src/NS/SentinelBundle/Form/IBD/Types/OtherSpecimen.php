<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of OtherSpecimen
 *
 */
class OtherSpecimen extends TranslatableArrayChoice
{
    const NONE    = 0;
    const PLEURAL = 1;
    const JOINT   = 2;
    const OTHER   = 3;

    protected $values = [
        self::NONE => 'None',
        self::PLEURAL => 'Pleural',
        self::JOINT => 'Joint',
        self::OTHER => 'Other',
    ];
}
