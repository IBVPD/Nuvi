<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class CaseResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const 
        UNKNOWN   = 0,
        SUSPECTED = 1,
        PROBABLE  = 2,
        CONFIRMED = 3;

    protected $values = [
        self::UNKNOWN => 'Unknown',
        self::SUSPECTED => 'Suspected',
        self::PROBABLE => 'Probable',
        self::CONFIRMED => 'Confirmed',
    ];
}
