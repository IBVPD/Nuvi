<?php

namespace NS\SentinelBundle\Form\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of DischargeDiagnosis
 *
 */
class DischargeDiagnosis extends TranslatableArrayChoice
{
    const BACTERIAL_MENINGITIS       = 1;
    const BACTERIAL_PNEUMONIA        = 2;
    const SEPSIS                     = 3;
    const MULTIPLE                   = 4;
    const OTHER_DIAGNOSIS            = 5;
    const OTHER_MENINGITIS           = 6;
    const OTHER_PNEUMONIA            = 7;
    const UNKNOWN                    = 99;

    protected $values = array(
                            self::BACTERIAL_MENINGITIS       => 'Bacterial meningitis',
                            self::BACTERIAL_PNEUMONIA        => 'Bacterial pneumonia',
                            self::SEPSIS                     => 'Sepsis',
                            self::MULTIPLE                   => 'Multiple (i.e. Meningitis and/or Pneumonia and/or Sepsis)',
                            self::OTHER_DIAGNOSIS            => 'Other Diagnosis',
                            self::OTHER_MENINGITIS           => 'Other Meningitis',
                            self::OTHER_PNEUMONIA            => 'Other Pneumonia',
                            self::UNKNOWN                    => 'Unknown',
                             );

    public function getName()
    {
        return 'DischargeDiagnosis';
    }
}
