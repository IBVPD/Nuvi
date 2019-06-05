<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class ElisaResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        NEGATIVE = 0,
        POSITIVE = 1,
        INDETERMINATE = 2,
        NOT_PROCESSED = 98,
        UNKNOWN = 99;

    protected $values = [
        self::NEGATIVE => 'Negative',
        self::POSITIVE => 'Positive',
        self::INDETERMINATE => 'Indeterminate',
        self::NOT_PROCESSED => 'Not processed',
        self::UNKNOWN => 'Unknown',
    ];
}
