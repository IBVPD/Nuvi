<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of BinaxResult
 *
 */
class BinaxResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const NEGATIVE     = 0;
    public const POSITIVE     = 1;
    public const INCONCLUSIVE = 2;
    public const UNKNOWN      = 99;

    protected $values = [
        self::NEGATIVE => 'Negative',
        self::POSITIVE => 'Positive',
        self::INCONCLUSIVE => 'Inconclusive',
        self::UNKNOWN => 'Unknown',
    ];
}
