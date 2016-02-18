<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

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
    const UNKNOWN                    = 99;

    protected $values = array(
                            self::BACTERIAL_MENINGITIS  => 'Bacterial meningitis',
                            self::BACTERIAL_PNEUMONIA   => 'Bacterial pneumonia',
                            self::SEPSIS                => 'Sepsis',
                            self::MULTIPLE              => 'Multiple (i.e. Meningitis and/or Pneumonia and/or Sepsis)',
                            self::OTHER                 => 'Other Diagnosis',
                            self::UNKNOWN               => 'Unknown',
                             );
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'DischargeDiagnosis';
    }
}
