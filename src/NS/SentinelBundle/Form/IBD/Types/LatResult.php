<?php

namespace NS\SentinelBundle\Form\IBD\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of LatResult
 *
 */
class LatResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NEGATIVE  = 0;
    const SPN       = 1;
    const HI        = 2;
    const NM        = 3;
    const OTHER     = 4;
    const INCONCLUSIVE = 5;
    const UNKNOWN   = 99;
    
    protected $values = [
                                self::NEGATIVE      => 'Negative',
                                self::SPN           => 'Spn',
                                self::HI            => 'Hi',
                                self::NM            => 'Nm',
                                self::OTHER         => 'Other',
                                self::INCONCLUSIVE  => 'Inconclusive',
                                self::UNKNOWN       => 'Unknown',
    ];
}
