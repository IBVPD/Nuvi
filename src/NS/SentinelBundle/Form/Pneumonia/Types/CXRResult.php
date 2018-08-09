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
    const VIRAL_PNEUMONIA = 10;
    const VIRAL_BACTERIAL = 11;
    const INCONCLUSIVE = 2;
    const OTHER        = 3;
    const UNKNOWN      = 99;
 
    protected $values = [
        self::NORMAL => 'Normal',
        self::CONSISTENT => 'Consistent with Bacterial Pneumonia',
        self::VIRAL_PNEUMONIA => 'Consistent with Viral Pneumonia',
        self::VIRAL_BACTERIAL => 'Consistent with Viral/Bacterial Co-infection',
        self::INCONCLUSIVE => 'Inconclusive',
        self::OTHER => 'Other',
        self::UNKNOWN => 'Unknown'
    ];
}
