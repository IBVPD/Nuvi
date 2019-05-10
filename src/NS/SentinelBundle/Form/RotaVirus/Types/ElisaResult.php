<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of ElisaResult
 *
 */
class ElisaResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const NEGATIVE = 0;
    public const POSITIVE = 1;
    public const INDETERMINATE = 2;
    public const UNKNOWN = 99;

    protected $values = [
        self::NEGATIVE => 'Negative',
        self::POSITIVE => 'Positive',
        self::INDETERMINATE => 'Indeterminate',
        self::UNKNOWN => 'Unknown',
    ];
}
