<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Diagnosis extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const SUSPECTED_MENINGITIS       = 1;
    const SUSPECTED_PNEUMONIA        = 2;
    const SUSPECTED_SEVERE_PNEUMONIA = 3;
    const SUSPECTED_SEPSIS           = 4;
    const MULTIPLE                   = 5;
    const OTHER                      = 6;
    const UNKNOWN                    = 99;

    protected $values = array(
                            self::SUSPECTED_MENINGITIS       => 'Suspected meningitis',
                            self::SUSPECTED_PNEUMONIA        => 'Suspected pneumonia',
                            self::SUSPECTED_SEVERE_PNEUMONIA => 'Suspected severe pneumonia',
                            self::SUSPECTED_SEPSIS           => 'Suspected sepsis',
                            self::MULTIPLE                   => 'Multiple (i.e. suspected meningitis and/or pneumonia and/or sepsis)',
                            self::OTHER                      => 'Other',
                            self::UNKNOWN                    => 'Unknown');
}
