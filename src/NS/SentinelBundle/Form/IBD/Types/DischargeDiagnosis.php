<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class DischargeDiagnosis extends TranslatableArrayChoice implements TranslationContainerInterface
{
    public const
        BACTERIAL_MENINGITIS       = 1,
        BACTERIAL_PNEUMONIA        = 2,
        SEPSIS                     = 3,
        MULTIPLE                   = 4,
        OTHER                      = 5,
        OTHER_MENINGITIS           = 6,
        OTHER_PNEUMONIA            = 7,
        UNKNOWN                    = 99;

    protected $values = [
        self::BACTERIAL_MENINGITIS => 'Bacterial meningitis',
        self::BACTERIAL_PNEUMONIA => 'Bacterial pneumonia',
        self::SEPSIS => 'Sepsis',
        self::MULTIPLE => 'Multiple (i.e. Meningitis and/or Pneumonia and/or Sepsis)',
        self::OTHER => 'Other Diagnosis',
        self::OTHER_MENINGITIS => 'Other meningitis',
        self::OTHER_PNEUMONIA => 'Other pneumonia',
        self::UNKNOWN => 'Unknown',
    ];
}
