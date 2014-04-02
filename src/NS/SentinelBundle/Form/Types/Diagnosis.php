<?php

namespace NS\SentinelBundle\Form\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of TripleChoice
 *
 * @author gnat
 */
class Diagnosis extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NA         = 0;
    const MENINGITIS = 1;
    const PNEUMONIA  = 2;
    const SEPSIS     = 3;
    const OTHER      = 4;
    const UNKNOWN    = 99;

    protected $values = array(
                            self::NA          => 'N/A',
                            self::MENINGITIS  => 'Meningitis', 
                            self::PNEUMONIA   => 'Pneumonia', 
                            self::SEPSIS      => 'Sepsis',
                            self::OTHER       => 'Other',
                            self::UNKNOWN     => 'Unknown');

    public function getName()
    {
        return 'Diagnosis';
    }
}