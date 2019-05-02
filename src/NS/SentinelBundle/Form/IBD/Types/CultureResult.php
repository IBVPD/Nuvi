<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of CultureResult
 *
 */
class CultureResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const NEGATIVE      = 0;
    public const SPN           = 1;
    public const HI            = 2;
    public const NM            = 3;
    public const OTHER         = 4;
    public const CONTAMINANT   = 5;
    public const UNKNOWN       = 99;

    protected $values = [
        self::NEGATIVE => 'Negative',
        self::SPN => 'Spn',
        self::HI => 'Hi',
        self::NM => 'Nm',
        self::OTHER => 'Other',
        self::CONTAMINANT => 'Contaminant',
        self::UNKNOWN => 'Unknown',
    ];
}
