<?php

namespace NS\SentinelBundle\Form\Meningitis\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class CSFAppearance extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const CLEAR          = 1;
    public const TURBID         = 2;
    public const BLOODY         = 3;
    public const XANTHROCHROMIC = 4;
    public const OTHER          = 5;
    public const NOT_ASSESSED   = 6;
    public const UNKNOWN        = 99;

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
