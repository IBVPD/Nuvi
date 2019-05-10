<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of IsolateViable
 *
 */
class IsolateViable extends TranslatableArrayChoice
{
    public const YES            = 1;
    public const NO             = 2;
    public const NOT_APPLICABLE = 3;

    protected $values = [
        self::YES            => 'Yes',
        self::NO             => 'No',
        self::NOT_APPLICABLE => 'Not Applicable',
    ];
}
