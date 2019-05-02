<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of DischargeClassification
 *
 */
class DischargeClassification extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        CONFIRMED_HI      = 1,
        CONFIRMED_SPN     = 2,
        CONFIRMED_NM      = 3,
        CONFIRMED_OTHER   = 4,
        PROBABLE          = 5,
        SUSPECT           = 6,
        INCOMPLETE        = 7,
        DISCARDED         = 8,
        SEPSIS            = 9,
        UNKNOWN           = 99;

    protected $values = [
        self::CONFIRMED_HI => 'Lab-confirmed for HI',
        self::CONFIRMED_SPN => 'Lab-confirmed for Spn',
        self::CONFIRMED_NM => 'Lab-confirmed for Nm',
        self::CONFIRMED_OTHER => 'Lab-confirmed for other organism',
        self::SEPSIS => 'Sepsis',
        self::PROBABLE => 'Probable',
        self::SUSPECT => 'Suspect',
        self::INCOMPLETE => 'Incomplete investigation',
        self::DISCARDED => 'Discarded case',
        self::UNKNOWN => 'Unknown',
    ];
}
