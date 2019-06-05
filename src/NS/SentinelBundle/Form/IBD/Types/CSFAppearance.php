<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class CSFAppearance extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        CLEAR          = 1,
        TURBID         = 2,
        BLOODY         = 3,
        XANTHROCHROMIC = 4,
        OTHER          = 5,
        NOT_ASSESSED   = 6,
        UNKNOWN        = 99;

    protected $values = [
        self::CLEAR => 'Clear',
        self::TURBID => 'Turbid/Cloudy',
        self::BLOODY => 'Bloody',
        self::XANTHROCHROMIC => 'Xanthrochromic',
        self::OTHER => 'Other',
        self::NOT_ASSESSED => 'Not assessed',
        self::UNKNOWN => 'Unknown'
    ];
}
