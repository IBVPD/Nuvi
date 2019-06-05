<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class IsolateViable extends TranslatableArrayChoice
{
    public const
        YES            = 1,
        NO             = 2,
        NOT_APPLICABLE = 3;

    protected $values = [
        self::YES            => 'Yes',
        self::NO             => 'No',
        self::NOT_APPLICABLE => 'Not Applicable',
    ];
}
