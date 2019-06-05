<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class IsolateType extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        SPN   = 1,
        HI    = 2,
        NM    = 3,
        OTHER = 4;

    protected $values = [
        self::SPN => 'Spn',
        self::HI => 'Hi',
        self::NM => 'Nm',
        self::OTHER => 'Other',
    ];
}
