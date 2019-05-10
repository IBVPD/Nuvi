<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class Diagnosis extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        SUSPECTED_MENINGITIS       = 1,
        SUSPECTED_PNEUMONIA        = 2,
        SUSPECTED_SEVERE_PNEUMONIA = 3,
        SUSPECTED_SEPSIS           = 4,
        MULTIPLE                   = 5,
        OTHER                      = 6,
        UNKNOWN                    = 99;

    protected $values = [
        self::SUSPECTED_MENINGITIS => 'Suspected meningitis',
        self::SUSPECTED_PNEUMONIA => 'Suspected pneumonia',
        self::SUSPECTED_SEVERE_PNEUMONIA => 'Suspected severe pneumonia',
        self::SUSPECTED_SEPSIS => 'Suspected sepsis',
        self::MULTIPLE => 'Multiple (i.e. suspected meningitis and/or pneumonia and/or sepsis)',
        self::OTHER => 'Other',
        self::UNKNOWN => 'Unknown'
    ];
}
