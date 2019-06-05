<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class LatResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        NEGATIVE  = 0,
        SPN       = 1,
        HI        = 2,
        NM        = 3,
        OTHER     = 4,
        INCONCLUSIVE = 5,
        UNKNOWN   = 99;

    protected $values = [
        self::NEGATIVE => 'Negative',
        self::SPN => 'Spn',
        self::HI => 'Hi',
        self::NM => 'Nm',
        self::OTHER => 'Other',
        self::INCONCLUSIVE => 'Inconclusive',
        self::UNKNOWN => 'Unknown',
    ];
}
