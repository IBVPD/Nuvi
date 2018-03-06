<?php

namespace NS\SentinelBundle\Form\Pneumonia\Types;

use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of CXRResult
 *
 */
class CXRResult extends TranslatableArrayChoice implements TranslationContainerInterface
{
    const NORMAL       = 0;
    const CONSISTENT   = 1;
    const INCONCLUSIVE = 2;
    const OTHER        = 3;
    const UNKNOWN      = 99;
 
    protected $values = [
        self::NORMAL => 'Normal',
        self::CONSISTENT => 'Consistent with Bacterial Pneumonia',
        self::INCONCLUSIVE => 'Inconclusive',
        self::OTHER => 'Other',
        self::UNKNOWN => 'Unknown'
    ];
}
