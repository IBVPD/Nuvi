<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class OtherSpecimen extends TranslatableArrayChoice
{
    public const
        NONE    = 0,
        PLEURAL = 1,
        JOINT   = 2,
        OTHER   = 3;

    protected $values = [
        self::NONE => 'None',
        self::PLEURAL => 'Pleural',
        self::JOINT => 'Joint',
        self::OTHER => 'Other',
    ];
}
