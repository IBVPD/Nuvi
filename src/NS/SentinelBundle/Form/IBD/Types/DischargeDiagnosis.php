<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Description of DischargeDiagnosis
 *
 */
class DischargeDiagnosis extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const BACTERIAL_MENINGITIS       = 1;
    const BACTERIAL_PNEUMONIA        = 2;
    const SEPSIS                     = 3;
    const MULTIPLE                   = 4;
    const OTHER                      = 5;
    const OTHER_MENINGITIS           = 6;
    const OTHER_PNEUMONIA            = 7;
    const UNKNOWN                    = 99;

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
