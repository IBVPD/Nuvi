<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class BinaxResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        NEGATIVE     = 0,
        POSITIVE     = 1,
        INCONCLUSIVE = 2,
        UNKNOWN      = 99;

    protected $values = [
        self::NEGATIVE => 'Negative',
        self::POSITIVE => 'Positive',
        self::INCONCLUSIVE => 'Inconclusive',
        self::UNKNOWN => 'Unknown',
    ];
}
